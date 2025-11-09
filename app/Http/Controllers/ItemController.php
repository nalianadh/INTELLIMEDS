<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\DB;


class ItemController extends Controller
{
    // Show the item registration form
    public function create()
    {
        if (!session()->has('loggedUser') || session('loggedUser')->u_role !== 'main_store') {
            return redirect('/login');
        }

        $items = Item::orderByDesc('created_at')->get();
        return view('main store.register_item', compact('items'));
    }

    // Store the new item
    public function store(Request $request)
    {
        if (!session()->has('loggedUser') || session('loggedUser')->u_role !== 'main_store') {
            return redirect('/login');
        }

        Item::create([
            'i_name' => $request->i_name,
            'i_description' => $request->i_description,
            'i_reorderLevel' => $request->i_reorderLevel,
            'i_quantity_in_stock' => $request->i_quantity_in_stock,
            'i_expirationDate' => $request->i_expirationDate,
            'i_batchNumber' => $request->i_batchNumber,
            'i_stockID' => $request->i_stockID,
            'i_unit' => $request->i_unit,
        ]);

        // After storing, redirect back to the form with updated items list
        return redirect()->route('items.create')->with('success', 'Item registered successfully!');
    }

    // Display the item list with search and filter capabilities
    public function itemList(Request $request)
    {
        $query = Item::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('i_name', 'like', "%$search%")
                ->orWhere('i_stockID', 'like', "%$search%")
                ->orWhere('i_batchNumber', 'like', "%$search%")
                ->orWhere('i_description', 'like', "%$search%")
                ->orWhere('i_expirationDate', 'like', "%$search%")
                ->orWhere('i_quantity_in_stock', 'like', "%$search%")
                ->orWhere('i_reorderLevel', 'like', "%$search%");
            });
        }

        $items = $query->orderByDesc('created_at')->get();

        // Append total quantity for each item
        foreach ($items as $item) {
            $item->total_quantity = $this->calculateTotalQuantity($item->item_id);
        }

        return view('main store.item-list', compact('items'));
    }
    public function view($itemId)
    {
        $item = \App\Models\Item::findOrFail($itemId);
        $batches = \App\Models\ReceiveNote::where('item_id', $itemId)
            ->select('grn_itemBatchNumber', 'grn_itemExpiredDate', 'grn_quantity_received')
            ->get();
        return view('main store.item-view', compact('item', 'batches'));
    }
    public function calculateTotalQuantity($itemId)
    {
        // Get the base quantity from Item table
        $itemQty = DB::table('items')
            ->where('item_id', $itemId)
            ->value('i_quantity_in_stock');

        // Get total quantity received from Receive Notes
        $grnQty = DB::table('receive_notes')
            ->where('item_id', $itemId)
            ->sum('grn_quantity_received');

        // Get total quantity from Stock Transfers
        $transferQty = DB::table('stock_transfers')
            ->where('item_id', $itemId)
            ->sum('tr_quantity');

        // Get total approved request quantity from Stock Requests
        $approvedRequestsQty = DB::table('stock_requests')
            ->where('item_id', $itemId)
            ->where('rq_status', 'Approved')
            ->sum('rq_qty_approved');

        // Formula: Item stock + Received + Transfers - Approved Requests
        $totalQty = $itemQty + $grnQty + $transferQty - $approvedRequestsQty;

        return $totalQty;
    }
}
