<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockTransfer;
use Illuminate\Support\Facades\DB;

class StockTransferController extends Controller
{
    // MAIN STORE METHODS
    // Show the stock transfer-out form
    public function transferOut()
    {
        $items = \App\Models\Item::with(['receiveNotes' => function($q) {
            $q->select('item_id', 'grn_itemBatchNumber', 'grn_itemExpiredDate')->distinct();
        }])->orderByDesc('created_at')->get();
        return view('main store.stock transfer.transfer-out', compact('items'));
    }
    // Show the stock transfer-in form
    public function transferIn()
    {
        $items = \App\Models\Item::with(['receiveNotes' => function($q) {
            $q->select('item_id', 'grn_itemBatchNumber', 'grn_itemExpiredDate')->distinct();
        }])->orderByDesc('created_at')->get();
        return view('main store.stock transfer.transfer-in', compact('items'));
    }

    // Store stock transfer-in
    // Store stock transfer-in (main store receiving from sub-department)
    public function storeTransferIn(Request $request)
    {
        $request->validate([
            'tr_from_unit' => 'required',
            'tr_destination' => 'required',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,item_id',
            'items.*.batch_expiry' => 'required',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $userId = session('loggedUser') ? session('loggedUser')->user_id : 1;
        $remarks = $request->input('tr_remarks');
        $dateRequested = now()->toDateString();
        $dateReceived = now()->toDateString(); // current date for received
        foreach ($request->items as $itemRow) {
            $itemId = $itemRow['item_id'];
            $quantity = $itemRow['quantity'];
            // Parse batch and expiry
            list($batch, $expiry) = explode('|', $itemRow['batch_expiry']);
            // Ensure expiry is full date (YYYY-MM-DD)
            if (substr_count($expiry, '-') == 1) {
                $expiry .= '-01';
            }

            \App\Models\StockTransfer::create([
                'item_id' => $itemId,
                'tr_from_unit' => $request->tr_from_unit,
                'tr_destination' => $request->tr_destination,
                'tr_in_quantity'     => $quantity,
                'tr_out_quantity'    => 0,
                'tr_transfer_status' => 'Received',
                'tr_requested_by' => $userId ?? 'system',
                'tr_received_by' => $userId,
                'tr_date_requested' => $dateRequested,
                'tr_date_received' => $dateReceived,
                'tr_remarks' => $remarks,
                'user_id' => $userId,
                'tr_batchNumber' => $batch,
                'tr_expiryDate' => $expiry,
            ]);

            // Update batch quantity in receive_notes (add)
            $receiveNote = \App\Models\ReceiveNote::where('item_id', $itemId)
                ->where('grn_itemBatchNumber', $batch)
                ->where('grn_itemExpiredDate', 'like', substr($expiry, 0, 7) . '%')
                ->orderBy('grn_id', 'asc')
                ->first();
            if ($receiveNote) {
                $receiveNote->grn_available_qty = (int)$receiveNote->grn_available_qty + (int)$quantity;
                $receiveNote->save();
            }

            // Update item quantity (add)
            $item = \App\Models\Item::find($itemId);
            if ($item) {
                $item->i_quantity_in_stock = (int)$item->i_quantity_in_stock + (int)$quantity;
                $item->save();
            }
        }

        return redirect()->route('stock.transfer.in')->with('success', 'Stock transfer(s) recorded and item quantities updated.');
    }


    // Store stock transfer-out (stub)
    /*public function storeTransferOut(Request $request)
    {
        $request->validate([
            'tr_from_unit' => 'required',
            'tr_destination' => 'required',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,item_id',
            'items.*.batch_expiry' => 'required',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $userId = session('loggedUser') ? session('loggedUser')->user_id : 1;
        $remarks = $request->input('tr_remarks');
        $dateRequested = now()->toDateString();

        foreach ($request->items as $itemRow) {
            $itemId = $itemRow['item_id'];
            $quantity = $itemRow['quantity'];
            // Parse batch and expiry
            list($batch, $expiry) = explode('|', $itemRow['batch_expiry']);

            \App\Models\StockTransfer::create([
                'item_id' => $itemId,
                'tr_from_unit' => $request->tr_from_unit,
                'tr_destination' => $request->tr_destination,
                'tr_quantity' => -abs($quantity), // store as negative for transfer-out
                'tr_transfer_status' => 'Pending',
                'tr_requested_by' => $userId ?? 'system',
                'tr_received_by' => null,
                'tr_date_requested' => $dateRequested,
                'tr_date_received' => null,
                'tr_remarks' => $remarks,
                'user_id' => $userId,
            ]);

            // Update batch quantity in receive_notes (deduct)
            $receiveNote = \App\Models\ReceiveNote::where('item_id', $itemId)
                ->where('grn_itemBatchNumber', $batch)
                ->where('grn_itemExpiredDate', $expiry)
                ->orderBy('grn_id', 'asc')
                ->first();
            if ($receiveNote) {
                $receiveNote->grn_available_qty = max(0, (int)$receiveNote->grn_available_qty - (int)$quantity);
                $receiveNote->save();
            }

            // Update item quantity (deduct)
            $item = \App\Models\Item::find($itemId);
            if ($item) {
                $item->i_quantity_in_stock = max(0, (int)$item->i_quantity_in_stock - (int)$quantity);
                $item->save();
            }
        }

        return redirect()->route('stock.transfer.out')->with('success', 'Stock transfer-out recorded and item quantities updated.');
    }*/
    public function storeTransferOut(Request $request)
    {
        $request->validate([
            'tr_from_unit' => 'required',
            'tr_destination' => 'required',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,item_id',
            'items.*.batch_expiry' => 'required',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $userId = session('loggedUser')->user_id ?? 1;
        $remarks = $request->input('tr_remarks');
        $dateRequested = now()->toDateString();

        foreach ($request->items as $itemRow) {

            $itemId   = $itemRow['item_id'];
            $quantity = (int) $itemRow['quantity'];
            list($batch, $expiry) = explode('|', $itemRow['batch_expiry']);
            // Ensure expiry is full date (YYYY-MM-DD)
            if (substr_count($expiry, '-') == 1) {
                $expiry .= '-01';
            }

            // 1️⃣ CREATE TRANSFER RECORD
            \App\Models\StockTransfer::create([
                'item_id'           => $itemId,
                'tr_from_unit'      => $request->tr_from_unit,
                'tr_destination'    => $request->tr_destination,
                'tr_in_quantity'    => 0,
                'tr_out_quantity'   => $quantity,
                'tr_transfer_status'=> 'Pending',
                'tr_requested_by'   => $userId,
                'tr_received_by'    => null,
                'tr_date_requested' => $dateRequested,
                'tr_date_received'  => null,
                'tr_remarks'        => $remarks,
                'user_id'           => $userId,
                'tr_batchNumber'    => $batch,
                'tr_expiryDate'     => $expiry,
            ]);

            // 2️⃣ DEDUCT MAIN STORE BATCH QUANTITY
            $receiveNote = \App\Models\ReceiveNote::where('item_id', $itemId)
                ->where('grn_itemBatchNumber', $batch)
                ->where('grn_itemExpiredDate', 'like', substr($expiry, 0, 7) . '%')
                ->orderBy('grn_id', 'asc')
                ->first();

            if ($receiveNote) {
                $receiveNote->grn_available_qty = max(0, (int)$receiveNote->grn_available_qty - $quantity);
                $receiveNote->save();
            }

            // 3️⃣ DEDUCT MAIN STORE TOTAL ITEM QUANTITY
            $item = \App\Models\Item::find($itemId);
            if ($item) {
                $item->i_quantity_in_stock = max(0, (int)$item->i_quantity_in_stock - $quantity);
                $item->save();
            }

            // 4️⃣ ADD STOCK TO DESTINATION SUBDEPARTMENT
            $destinationUser = \App\Models\User::where('u_name', $request->tr_destination)->first();

            if ($destinationUser) {
                $subStock = \App\Models\SubdepartmentStock::firstOrNew([
                    'user_id'        => $destinationUser->user_id,
                    'item_id'        => $itemId,
                    'sd_batchNumber' => $batch,
                    'sd_expiryDate'  => $expiry,
                ]);

                // Initialize if null
                $subStock->sd_quantityInHand = $subStock->sd_quantityInHand ?? 0;

                // Increment safely
                $subStock->sd_quantityInHand += $quantity;
                $subStock->save();
            }
        }

        return redirect()->route('stock.transfer.out')
            ->with('success', 'Stock transfer-out recorded and item quantities updated.');
    }


    // Example: list all transfers
    public function index()
    {
        $transfers = StockTransfer::all();
        return view('main store.stock-transfer-list', compact('transfers'));
    }
    // Show the stock transfer form
    public function create()
    {
        $items = \App\Models\Item::with(['receiveNotes' => function($q) {
            $q->select('item_id', 'grn_itemBatchNumber', 'grn_itemExpiredDate')->distinct();
        }])->orderByDesc('created_at')->get();
        return view('main store.stock transfer.transfer-out', compact('items'));
    }
        // Show the stock transfer list
    public function transferList()
    {
        $transfers = \DB::table('stock_transfers as st')
            ->join(\DB::raw('(SELECT tr_from_unit, MAX(tr_date_requested) as latest_date 
                            FROM stock_transfers 
                            GROUP BY tr_from_unit) latest'),
                function($join) {
                    $join->on('st.tr_from_unit', '=', 'latest.tr_from_unit')
                            ->on('st.tr_date_requested', '=', 'latest.latest_date');
                })
            ->orderBy('st.tr_date_requested', 'desc')
            ->get();

        foreach ($transfers as $transfer) {
            $note = \App\Models\ReceiveNote::where('item_id', $transfer->item_id)->first();
            $transfer->batch = $note->grn_itemBatchNumber ?? '-';
            $transfer->expiry = $note->grn_itemExpiredDate ?? '-';
        }

        return view('main store.stock transfer.transfer-list', compact('transfers'));
    }


    // SUB DEPARTMENT METHODS
    public function createSubDept()
    {
        if (!session()->has('loggedUser')) {
            return redirect('/login'); 
        }

        $loggedUser = session('loggedUser');
        $unitName   = $loggedUser->u_unit;

        // Get current in-hand stock from the subdepartment_stocks table
        $inHandStock = \App\Models\SubdepartmentStock::with('item')
            ->where('user_id', $loggedUser->user_id)
            ->where('sd_quantityInHand', '>', 0)
            ->get();

        // Get unique in-hand items
        $inHandItems = $inHandStock->pluck('item')->unique('item_id');

        // Prepare batch expiry data
        $batchExpiryData = [];
        foreach ($inHandStock as $stock) {
            $batchExpiryData[$stock->item_id][] = [
                'batch' => $stock->sd_batchNumber,
                'expiry' => $stock->sd_expiryDate,
            ];
        }

        return view('sub_department.stock transfer.sd-transfer', compact(
            'inHandStock', 
            'inHandItems',
            'batchExpiryData',
            'unitName'
        ));
    }

    public function storeSubDept(Request $request)
    {
        $request->validate([
            'tr_from_unit' => 'required',
            'tr_destination' => 'required',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,item_id',
            'items.*.batch_expiry' => 'required',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $userId = session('loggedUser')->user_id ?? 1;
        $remarks = $request->input('tr_remarks');
        $dateRequested = now()->toDateString();
        $dateReceived = now()->toDateString();

        foreach ($request->items as $itemRow) {
            $itemId = $itemRow['item_id'];
            $quantity = (int)$itemRow['quantity'];
            list($batch, $expiry) = explode('|', $itemRow['batch_expiry']);
            // Ensure expiry is full date (YYYY-MM-DD)
            if (substr_count($expiry, '-') == 1) {
                $expiry .= '-01';
            }

            // 1️⃣ Record the transfer request (always Pending)
            \App\Models\StockTransfer::create([
                'item_id'           => $itemId,
                'tr_from_unit'      => $request->tr_from_unit,
                'tr_destination'    => $request->tr_destination,
                'tr_in_quantity'    => 0,
                'tr_out_quantity'   => $quantity,
                'tr_transfer_status'=> 'Pending',
                'tr_requested_by'   => $userId,
                'tr_received_by'    => null,
                'tr_date_requested' => $dateRequested,
                'tr_date_received'  => null,
                'tr_remarks'        => $remarks,
                'user_id'           => $userId,
                'tr_batchNumber'    => $batch,
                'tr_expiryDate'     => $expiry,
            ]);

            // 2️⃣ Update subdepartment stock (deduct)
            $subStock = \App\Models\SubdepartmentStock::where('user_id', $userId)
                ->where('item_id', $itemId)
                ->where('sd_batchNumber', $batch)
                ->where('sd_expiryDate', $expiry)
                ->first();

            if ($subStock) {
                $subStock->sd_quantityInHand = max(0, $subStock->sd_quantityInHand - $quantity);
                $subStock->save();
            }

            // 3️⃣ Add to main store batch quantity
            $receiveNote = \App\Models\ReceiveNote::where('item_id', $itemId)
                ->where('grn_itemBatchNumber', $batch)
                ->where('grn_itemExpiredDate', 'like', substr($expiry, 0, 7) . '%')
                ->orderBy('grn_id', 'asc')
                ->first();

            if ($receiveNote) {
                $receiveNote->grn_available_qty = (int)$receiveNote->grn_available_qty + (int)$quantity;
                $receiveNote->save();
            }

            // 4️⃣ Add to main store total item quantity
            $item = \App\Models\Item::find($itemId);
            if ($item) {
                $item->i_quantity_in_stock = (int)$item->i_quantity_in_stock + (int)$quantity;
                $item->save();
            }
        }

        return redirect()->route('stock.transfer.subdept')
            ->with('success', 'Stock transfer(s) submitted and in-hand stock updated.');
    }




    
    /*public function getInHandStock()
    {
        if (!session()->has('loggedUser')) {
            return collect(); // safe fallback
        }

        $loggedUser = session('loggedUser');
        $userId     = $loggedUser->user_id;      // requests are tied to the user
        $unitName   = $loggedUser->u_unit; // transfers are by department/unit

        // 1) Sum of approved requests for THIS user
        $approvedStock = DB::table('stock_requests')
            ->join('items', 'stock_requests.item_id', '=', 'items.item_id')
            ->select('items.item_id', 'items.i_name', DB::raw('SUM(stock_requests.rq_qty_approved) as qty'))
            ->where('stock_requests.rq_status', 'Approved')
            ->where('stock_requests.rq_requested_by', $userId)
            ->groupBy('items.item_id', 'items.i_name');

        // 2) Transfers INTO this unit (approved only)
        $transfersIn = DB::table('stock_transfers')
            ->select('item_id', DB::raw('SUM(tr_quantity) as qty'))
            ->where('tr_transfer_status', 'Approved')
            ->where('tr_destination', $unitName)
            ->groupBy('item_id');

        // 3) Transfers OUT from this unit (approved only)
        $transfersOut = DB::table('stock_transfers')
            ->select('item_id', DB::raw('SUM(tr_quantity) as qty'))
            ->where('tr_transfer_status', 'Received')
            ->where('tr_from_unit', $unitName)
            ->groupBy('item_id');

        // 4) Combine to get real-time in-hand stock
        return DB::table('items')
            ->leftJoinSub($approvedStock, 'approvedStock', function ($join) {
                $join->on('items.item_id', '=', 'approvedStock.item_id');
            })
            ->leftJoinSub($transfersIn, 'transfersIn', function ($join) {
                $join->on('items.item_id', '=', 'transfersIn.item_id');
            })
            ->leftJoinSub($transfersOut, 'transfersOut', function ($join) {
                $join->on('items.item_id', '=', 'transfersOut.item_id');
            })
            ->select(
                'items.item_id',
                'items.i_name',
                DB::raw('
                    COALESCE(approvedStock.qty, 0)
                    + COALESCE(transfersIn.qty, 0)
                    - COALESCE(transfersOut.qty, 0)
                    AS in_hand_stock
                ')
            )
            ->having('in_hand_stock', '>', 0)
            ->get();
    }*/
    public function getInHandStock()
    {
        if (!session()->has('loggedUser')) {
            return collect();
        }

        $loggedUser = session('loggedUser');

        return \App\Models\SubdepartmentStock::with('item')
            ->where('user_id', $loggedUser->user_id)
            ->where('sd_quantityInHand', '>', 0)
            ->get();
    }



    //tunjuk transfer slip
    public function show($id)
    {
        // Find the specific transfer by ID
        $transfer = StockTransfer::findOrFail($id);

        // Optionally, you can include related item details if your model has a relation
        // Example:
        // $transfer = StockTransfer::with('item')->findOrFail($id);

        return view('main store.stock transfer.transfer-slip', compact('transfer'));
    }

    public function searchTransfers(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            return redirect()->route('transfer.list');
        }

        // Search by multiple columns
        $filteredTransfers = \App\Models\StockTransfer::where('tr_from_unit', 'LIKE', "%{$query}%")
            ->orWhere('tr_destination', 'LIKE', "%{$query}%")
            ->orWhere('tr_transfer_status', 'LIKE', "%{$query}%")
            ->orWhere('tr_remarks', 'LIKE', "%{$query}%")
            ->orWhere('tr_requested_by', 'LIKE', "%{$query}%")
            ->orWhere('tr_received_by', 'LIKE', "%{$query}%")
            ->orWhere('transfer_id', 'LIKE', "%{$query}%")
            ->orderByDesc('tr_date_requested')
            ->get();

        // Add batch and expiry from related ReceiveNote (optional)
        foreach ($filteredTransfers as $transfer) {
            $note = \App\Models\ReceiveNote::where('item_id', $transfer->item_id)->first();
            $transfer->batch = $note->grn_itemBatchNumber ?? '-';
            $transfer->expiry = $note->grn_itemExpiredDate ?? '-';
        }

        return view('main store.stock transfer.transfer-list', [
            'transfers' => $filteredTransfers,
            'query' => $query
        ]);
    }





}
