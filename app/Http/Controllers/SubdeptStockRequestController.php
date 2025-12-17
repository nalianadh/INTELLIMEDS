<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\StockRequest;
use Illuminate\Support\Facades\Auth;

class SubdeptStockRequestController extends Controller
{
    // Show the stock request form
    public function create()
    {
        $items = Item::orderBy('i_name')->get();
        return view('sub_department.stock request.st-req', compact('items'));
    }

    // Handle the stock request submission
    public function store(Request $request)
    {
        $request->validate([
            'department' => 'required',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        return redirect()->back()->with('success', 'Stock request submitted successfully!');
    }

    // ✅ Show in-hand stock from approved requests
    /*public function inHandStock()
    {
        $userId = session('loggedUser')->user_id;

        // In-hand from approved requests
        $approvedRequests = \DB::table('stock_requests')
            ->join('items', 'stock_requests.item_id', '=', 'items.item_id')
            ->select('items.i_name', \DB::raw('SUM(stock_requests.rq_qty_approved) as total_quantity'))
            ->where('stock_requests.rq_status', 'Approved')
            ->where('stock_requests.rq_requested_by', $userId)
            ->groupBy('items.i_name');

        // In-hand from accepted transfers
        $acceptedTransfers = \DB::table('stock_transfers')
            ->join('items', 'stock_transfers.item_id', '=', 'items.item_id')
            ->select('items.i_name', \DB::raw('SUM(stock_transfers.tr_quantity) as total_quantity'))
            ->where('stock_transfers.tr_transfer_status', 'Received')
            ->where('stock_transfers.tr_received_by', $userId)
            ->groupBy('items.i_name');

        // Union both queries
        $inHand = $approvedRequests->unionAll($acceptedTransfers);

        // Final aggregation (sum both request + transfer quantities)
        $finalStock = \DB::table(\DB::raw("({$inHand->toSql()}) as combined"))
            ->mergeBindings($inHand)
            ->select('i_name', \DB::raw('SUM(total_quantity) as total_quantity'))
            ->groupBy('i_name')
            ->get();

        return view('sub_department.in-hand-stock.inhand-st', compact('finalStock'));
    }*/
    public function inHandStock()
    {
        $userId = session('loggedUser')->user_id;

        // New logic: Read from subdepartment_stocks table directly
        $finalStock = \DB::table('subdepartment_stocks')
            ->join('items', 'subdepartment_stocks.item_id', '=', 'items.item_id')
            ->select(
                'items.i_name',
                'subdepartment_stocks.sd_batchNumber',
                'subdepartment_stocks.sd_expiryDate',
                'subdepartment_stocks.sd_quantityInHand'
            )
            ->where('subdepartment_stocks.user_id', $userId)
            ->orderBy('items.i_name')
            ->get();

        return view('sub_department.in-hand-stock.inhand-st', compact('finalStock'));
    }



}
