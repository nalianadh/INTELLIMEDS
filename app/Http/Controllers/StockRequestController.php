<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\StockRequest;
use App\Models\User; 
use App\Models\SubdepartmentStock;
use App\Models\ReadStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class StockRequestController extends Controller
{
    public function create(Request $request)
    {
        if (!session()->has('loggedUser')) {
            return redirect('/login');
        }

        $items = Item::orderBy('i_name')->get();
        $prefilledItem = null;
        if ($request->has('item_id')) {
            $prefilledItem = Item::find($request->item_id);
        }
        return view('sub_department.stock_request.st-req', compact('items', 'prefilledItem'));
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

            // Mark as read
            \App\Models\ReadStatus::firstOrCreate([
                'user_id' => $userId,
                'messageable_type' => StockRequest::class,
                'messageable_id' => $id,
            ]);

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

            // ==============================
            // CREATE SUPPLY TRANSACTION LOG
            // ==============================
            try {
                $response = Http::post('http://127.0.0.1:8000/predict', [
                    'stock'          => $stockRequest->item->i_name,
                    'brand'          => $stockRequest->item->i_description ?? '',
                    'site_supplier'  => 'Main Store',
                    'activity'       => 'SUPPLY',
                    'quantity'       => $stockRequest->rq_qty_approved,
                    'unit'           => $stockRequest->item->i_unit ?? '',
                    'year'           => now()->year,
                    'month'          => now()->month,
                ]);

                if ($response->successful()) {
                    $demandLevel = $response->json()['predicted_demand'] ?? null;
                } else {
                    $demandLevel = null;
                }
            } catch (\Exception $e) {
                $demandLevel = null;
            }

            \App\Models\SupplyTransaction::create([
                'ref_request_id' => $stockRequest->request_id, // store as reference only
                'Date'           => now(),
                'Stock_ID'       => $stockRequest->item->i_stockID,
                'Stock'          => $stockRequest->item->i_name,
                'Brand'          => $stockRequest->item->i_description ?? null,
                'Site_Supplier'  => 'Main Store',
                'Activity'       => 'SUPPLY',
                'Quantity'       => $stockRequest->rq_qty_approved,
                'Unit'           => $stockRequest->item->i_unit ?? null,
                'Demand_Level'   => $demandLevel,
            ]);
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
