<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\StockRequest;
use App\Models\User; 
use App\Models\SubdepartmentStock;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StockRequestController extends Controller
{
    public function create()
    {
        if (!session()->has('loggedUser')) {
            return redirect('/login');
        }

        $items = Item::orderBy('i_name')->get();
        return view('sub_department.stock_request.st-req', compact('items'));
    }

    public function store(Request $request)
    {
        if (!session()->has('loggedUser')) {
            return redirect('/login');
        }

        $request->validate([
            'department' => 'required',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,item_id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $user = session('loggedUser');
        $userId = $user->user_id;

        foreach ($request->items as $item) {
            StockRequest::create([
                'rq_requested_by'        => $userId,
                'user_id'                => $userId,
                'item_id'                => $item['item_id'],
                'rq_quantity_requested'  => $item['quantity'],
                'rq_status'              => 'Pending',
                'rq_date_requested'      => Carbon::now()->toDateString(),
                'rq_qty_approved'        => null,
                'rq_date_approved'       => null,
                'rq_approved_by'         => null,
                'rq_remarks'             => null,
            ]);
        }

        return redirect()->back()->with('success', 'Stock request submitted successfully!');
    }

    public function listPendingRequests(Request $request)
    {
        $query = StockRequest::where('rq_status', 'Pending')
                            ->with('user')
                            ->orderBy('rq_date_requested', 'desc');

        if ($request->has('search') && !empty($request->search)) {
            $query->where('rq_id', 'LIKE', '%' . $request->search . '%');
        }

        $pendingRequests = $query->get();

        return view('main store.stock request.stock-request', compact('pendingRequests'));
    }

    public function showRequestList()
    {
        $pendingRequests = StockRequest::where('rq_status', 'Pending')->get();
        return view('main store.stock request.stock-request', compact('pendingRequests'));
    }

    // ✅ View specific request details for main store
    public function show($id)
    {
        $stockRequest = StockRequest::with(['item', 'requestedBy'])->find($id);

        if (!$stockRequest) {
            abort(404, 'Stock Request not found');
        }

        return view('main_store.stock-request-view', compact('stockRequest'));
    }

    // ✅ Handle approval update
    public function update(Request $request, $id)
    {
        $user = session('loggedUser');
        $userId = $user->user_id;

        $request->validate([
            'rq_qty_approved' => 'required|integer|min:1',
            'batches' => 'required|array',
            'batches.*' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $id, $userId) {
            $stockRequest = StockRequest::where('request_id', $id)->firstOrFail();

            $stockRequest->rq_qty_approved = $request->rq_qty_approved;
            $stockRequest->rq_remarks = $request->rq_remarks;
            $stockRequest->rq_status = 'Approved';
            $stockRequest->rq_date_approved = now();
            $stockRequest->rq_approved_by = $userId;
            $stockRequest->save();

            $totalApproved = 0;
            $firstGrn = null;

            foreach ($request->batches as $grnId => $qty) {
                $qty = (int) $qty;

                if ($qty > 0) {
                    $grn = \App\Models\ReceiveNote::findOrFail($grnId);

                    if ($qty > $grn->grn_available_qty) {
                        throw new \Exception("Quantity for batch {$grn->grn_itemBatchNumber} exceeds available stock.");
                    }

                    $grn->grn_available_qty -= $qty;
                    $grn->save();

                    $totalApproved += $qty;

                    if (!$firstGrn) {
                        $firstGrn = $grn;
                    }
                }
            }

            $item = $stockRequest->item;
            $item->i_quantity_in_stock = $item->receiveNotes()->sum('grn_available_qty');
            $item->save();

            if ($totalApproved != $request->rq_qty_approved) {
                throw new \Exception("Total approved quantity does not match sum of batch quantities.");
            }

            // ⭐ UPDATE SUBDEPARTMENT STOCK ⭐
            $existingStock = SubdepartmentStock::where('user_id', $stockRequest->rq_requested_by)
                ->where('item_id', $stockRequest->item_id)
                ->first();

            if ($existingStock) {
                $existingStock->sd_quantityInHand += $stockRequest->rq_qty_approved;
            } else {
                $existingStock = new SubdepartmentStock([
                    'user_id'           => $stockRequest->rq_requested_by,
                    'item_id'           => $stockRequest->item_id,
                    'sd_quantityInHand' => $stockRequest->rq_qty_approved,
                ]);
            }

            // Use batch and expiry from the first supplied batch
            if ($firstGrn) {
                $expiry = $firstGrn->grn_itemExpiredDate;
                // Ensure expiry is full date (YYYY-MM-DD)
                if (substr_count($expiry, '-') == 1) {
                    $expiry .= '-01';
                }
                $existingStock->sd_batchNumber = $firstGrn->grn_itemBatchNumber;
                $existingStock->sd_expiryDate = $expiry;
            }

            $existingStock->save();
        });

        return redirect()
            ->route('stock.request.list')
            ->with('success', 'Stock request approved and batches updated successfully.');
    }

    public function view($id)
    {
        $stockRequest = StockRequest::with(['item', 'requestedBy'])->where('request_id', $id)->firstOrFail();

        return view('main store.stock request.view_request', compact('stockRequest'));
    }
}
