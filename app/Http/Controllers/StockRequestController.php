<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\StockRequest;
use App\Models\User; // ✅ Needed for relations
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

        $pendingRequests = StockRequest::with('requestedBy')
            ->where('rq_status', 'Pending')
            ->orderBy('rq_date_requested', 'desc')
            ->get();

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

        DB::transaction(function () use ($request, $id, $userId) { // 👈 added $userId
            // Find the stock request
            $stockRequest = StockRequest::findOrFail($id);

            // Update request approval details
            $stockRequest->rq_qty_approved = $request->rq_qty_approved;
            $stockRequest->rq_remarks = $request->rq_remarks;
            $stockRequest->rq_status = 'Approved';
            $stockRequest->rq_date_approved = now();
            $stockRequest->rq_approved_by = $userId; // 👈 works now
            $stockRequest->save();

            // Deduct from item stock
            $item = Item::findOrFail($stockRequest->item_id);
            $item->i_quantity_in_stock -= $request->rq_qty_approved;

            // Optional: Prevent negative stock
            if ($item->i_quantity_in_stock < 0) {
                $item->i_quantity_in_stock = 0;
            }

            $item->save();
        });

        return redirect()
            ->route('stock.request.list')
            ->with('success', 'Stock request approved and stock updated successfully.');
    }

    public function view($id)
    {
        $stockRequest = StockRequest::with(['item', 'requestedBy'])->where('request_id', $id)->firstOrFail();

        return view('main store.stock request.view_request', compact('stockRequest'));
    }


}
