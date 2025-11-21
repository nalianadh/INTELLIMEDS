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

        // Validate request
        $request->validate([
            'i_name' => 'required',
            'i_description' => 'nullable',
            'i_reorderLevel' => 'nullable|integer',
            'i_stockID' => 'required',
            'i_unit' => 'required',
            'i_expirationDate' => 'nullable|date',
            'i_batchNumber' => 'nullable|string',
        ]);

        // Create item with i_quantity_in_stock = 0
        Item::create([
            'i_name' => $request->i_name,
            'i_description' => $request->i_description,
            'i_reorderLevel' => $request->i_reorderLevel,
            'i_quantity_in_stock' => 0, // always start at 0
            'i_expirationDate' => $request->i_expirationDate,
            'i_batchNumber' => $request->i_batchNumber,
            'i_stockID' => $request->i_stockID,
            'i_unit' => $request->i_unit,
        ]);

        return redirect()->route('items.create')->with('success', 'Item registered successfully!');
    }

    // Display the item list with calculated quantities
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
                  ->orWhere('i_reorderLevel', 'like', "%$search%");
            });
        }

        $items = $query->orderByDesc('created_at')->paginate(10);

        // Calculate total quantity dynamically for each item
        /*foreach ($items as $item) {
            $item->total_quantity = $this->calculateTotalQuantity($item->item_id);
        }*/

        return view('main store.item-list', compact('items'));
    }

    // View item details
    public function view($itemId)
    {
        $item = \App\Models\Item::findOrFail($itemId);
        $batches = \App\Models\ReceiveNote::where('item_id', $itemId)
            ->select('grn_itemBatchNumber', 'grn_itemExpiredDate', 'grn_available_qty')
            ->get();
        return view('main store.item-view', compact('item', 'batches'));
    }


    // Edit item
    public function edit($id)
    {
        $item = Item::findOrFail($id);
        return view('main store.item-edit', compact('item'));
    }

    // Update item
    public function update(Request $request, $id)
    {
        $request->validate([
            'i_stockID' => 'required',
            'i_name' => 'required',
            'i_description' => 'required',
        ]);

        $item = Item::findOrFail($id);
        $item->update($request->only([
            'i_name', 'i_description', 'i_reorderLevel', 'i_stockID', 'i_unit', 'i_expirationDate', 'i_batchNumber'
        ]));

        return redirect()->route('items.list')->with('success', 'Item updated successfully.');
    }

    // Delete item
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->route('items.list')->with('success', 'Item deleted successfully.');
    }

    //search item
    public function searchItem(Request $request)
    {
        $search = $request->input('search');

        $items = Item::where('i_name', 'like', "%$search%")
            ->orWhere('i_stockID', 'like', "%$search%")
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('main store.item-list', compact('items'));

    }

    //nak sync data dengan supply_transaction table untuk display dekat list
    public function syncImportedItems()
    {
        $uniqueItems = DB::table('supply_transaction')
            ->select('Stock_ID', 'Stock', 'Unit', 'Brand')
            ->groupBy('Stock_ID', 'Stock', 'Unit', 'Brand')
            ->get();

        foreach ($uniqueItems as $item) {
            \App\Models\Item::updateOrCreate(
                [
                    'i_stockID' => $item->Stock_ID
                ],
                [
                    'i_name' => $item->Stock,
                    'i_unit' => $item->Unit,
                    'i_description' => $item->Brand,
                ]
            );
        }


        return redirect()->back()->with('success', 'Items synced successfully!');
    }

}
