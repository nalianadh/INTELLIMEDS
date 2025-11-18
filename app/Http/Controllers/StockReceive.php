<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ReceiveNote;

class StockReceive extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'grn_purchase_order_id' => 'required',
            'grn_supplier_company' => 'required',
            'item_received' => 'required',
        ]);

        $itemIds = $request->item_received;
        $existingQtys = $request->existing_qty_received ?? [];
        $existingBatches = $request->existing_batch_number ?? [];
        $existingExpires = $request->existing_expired_date ?? [];
        $newNames = $request->new_item_name ?? [];
        $newDescs = $request->new_item_description ?? [];
        $newQtys = $request->new_qty_received ?? [];
        $newBatches = $request->new_batch_number ?? [];
        $newExpires = $request->new_expired_date ?? [];

        $createdNotes = [];
        foreach ($itemIds as $i => $itemId) {
            if ($itemId === 'new') {
                $item = Item::create([
                    'i_name' => $newNames[$i] ?? null,
                    'i_description' => $newDescs[$i] ?? null,
                    'i_quantity_in_stock' => $newQtys[$i] ?? 0,
                    // batch/expiry are now tracked in ReceiveNote, not Item
                ]);
                $quantityReceived = $newQtys[$i] ?? 0;
            } else {
                $item = Item::find($itemId);
                $quantityReceived = $existingQtys[$i] ?? 0;
                // Update item quantity
                $item->i_quantity_in_stock += (int) $quantityReceived;
                $item->save();
            }

            // Save to ReceiveNote
            $note = ReceiveNote::create([
                'grn_received_by' => session('loggedUser')->u_username ?? 'system',
                'item_id' => $item->item_id,
                'grn_quantity_received' => $quantityReceived,
                'grn_available_qty' => $quantityReceived, // nak makesure quantity available sama dengan received
                'grn_date_received' => now(),
                'grn_supplier' => $request->grn_supplier_company,
                'grn_po_number' => $request->grn_purchase_order_id,
                'grn_remarks' => $request->grn_remarks ?? null,
                'grn_itemBatchNumber' => $itemId === 'new' ? ($newBatches[$i] ?? null) : ($existingBatches[$i] ?? null),
                'grn_itemExpiredDate' => $itemId === 'new' ? ($newExpires[$i] ?? null) : ($existingExpires[$i] ?? null),
            ]);
            $createdNotes[] = $note->grn_id;
        }

        // Redirect to GRN view for this PO and supplier
        return redirect()->route('stock.receive.grn', [
            'po' => $request->grn_purchase_order_id,
            'supplier' => $request->grn_supplier_company
        ]);
    // To display all batch numbers and expiry dates for an item in the item detail view:
    // Query ReceiveNote::where('item_id', $itemId)->select('grn_itemBatchNumber', 'grn_itemExpiredDate')->get();
    }

    public function grn(Request $request)
    {
        $po = $request->get('po') ?? $request->po;
        $supplier = $request->get('supplier') ?? $request->supplier;
        $receiveNotes = \App\Models\ReceiveNote::with('item')
            ->where('grn_po_number', $po)
            ->where('grn_supplier', $supplier)
            ->orderBy('grn_id', 'asc')
            ->get();
        return view('main store.grn', compact('receiveNotes'));
    }

    public function create()
    {
        if (!session()->has('loggedUser')) {
            return redirect('/login');
        }
        $user = session('loggedUser');
        if ($user->u_role !== 'main_store') {
            return redirect('/login');
        }
        $items = \App\Models\Item::orderByDesc('created_at')->get();
        return view('main store.stock-receive', compact('user', 'items'));
    }

    public function grnList()
    {
        // Group by PO number, supplier, and received by (to avoid duplicate rows for same PO/supplier)
        $allNotes = \App\Models\ReceiveNote::orderByDesc('grn_date_received')->get();
        $grnGroups = $allNotes->groupBy(function($note) {
            return $note->grn_po_number . '|' . $note->grn_supplier . '|' . $note->grn_received_by;
        });
        return view('main store.grn-list', compact('grnGroups'));
    }

    //search function GRN list (Main Store)
    public function searchGRN(Request $request)
    {
        $query = $request->input('query');

        // If search input is empty, return all records grouped
        if (empty($query)) {
            $allNotes = \App\Models\ReceiveNote::orderByDesc('grn_date_received')->get();
            $grnGroups = $allNotes->groupBy(function($note) {
                return $note->grn_po_number . '|' . $note->grn_supplier . '|' . $note->grn_received_by;
            });
            return view('main store.grn-list', compact('grnGroups'));
        }

        // Search by PO number, supplier, or received_by
        $filteredNotes = \App\Models\ReceiveNote::where('grn_po_number', 'LIKE', "%{$query}%")
            ->orWhere('grn_supplier', 'LIKE', "%{$query}%")
            ->orWhere('grn_received_by', 'LIKE', "%{$query}%")
            ->orderByDesc('grn_date_received')
            ->get();

        // Group the results for display
        $grnGroups = $filteredNotes->groupBy(function($note) {
            return $note->grn_po_number . '|' . $note->grn_supplier . '|' . $note->grn_received_by;
        });

        return view('main store.grn-list', compact('grnGroups', 'query'));
    }

}
