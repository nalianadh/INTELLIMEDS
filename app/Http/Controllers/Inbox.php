<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockTransfer;
use App\Models\StockRequest;
use App\Models\Item;
use App\Models\ReadStatus; 

class Inbox extends Controller
{
    //INBOX SUB DEPARTMENT - ( Manage semua action from sub department inbox )
    // Show inbox for logged-in user (e.g., ICU department)
    public function index()
    {
        $user = session('loggedUser');
        $department = $user->u_unit; // e.g. "ICU"

        // Fetch all transfers addressed to that department
        $transferMessages = StockTransfer::where('tr_destination', $department)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($msg) {
                return [
                    'type' => 'transfer',
                    'id' => $msg->transfer_id,
                    'from' => $msg->tr_from_unit,
                    'date' => $msg->created_at,
                    'status' => $msg->tr_transfer_status,
                ];
            });

        // Fetch all stock requests made by this department
        $requestMessages = StockRequest::whereHas('user', function ($q) use ($department) {
                $q->where('u_unit', $department);
            })
            ->orderBy('rq_date_requested', 'desc')
            ->get()
            ->map(function ($req) {
                return [
                    'type' => 'request',
                    'id' => $req->request_id,
                    'item_id' => $req->item_id,
                    'qty_requested' => $req->rq_quantity_requested,
                    'qty_approved' => $req->rq_qty_approved,
                    'date' => $req->rq_date_requested,
                    'status' => $req->rq_status,
                ];
            });

        // ✅ Convert both to base collections before merging
        $messages = collect($transferMessages)
            ->merge(collect($requestMessages))
            ->sortByDesc('date')
            ->values(); // optional, to reset array keys

        return view('sub_department.inbox_SD.inbox', compact('messages'));
    }


    // Show details of one transfer message
    public function show(Request $request, $id)
    {
        $type = $request->query('type'); // read the "type" from URL ?type=request

        if ($type === 'transfer') {
            $transfer = StockTransfer::findOrFail($id);
            return view('sub_department.inbox_SD.view-inbox-transfer', compact('transfer'));

        } elseif ($type === 'request') {
            $requestData = StockRequest::findOrFail($id);
            return view('sub_department.inbox_SD.view-inbox-request', compact('requestData'));
        }

        abort(404, 'Message type not found');
    }

    // Accept transfer → deduct stock from main store
    public function accept($id)
    {
        $transfer = StockTransfer::findOrFail($id);

        // Deduct from main store stock
        $mainItem = Item::where('item_id', $transfer->item_id)->first();
        if ($mainItem) {
            $mainItem->i_quantity_in_stock -= abs($transfer->tr_quantity);
            $mainItem->save();
        }

        // Update transfer details
        $transfer->tr_transfer_status = 'Received';
        $transfer->tr_date_received = now();
        $transfer->tr_received_by = session('loggedUser')->user_id; // use session
        $transfer->save();

        return redirect()->route('subdept.inbox')
            ->with('success', 'Stock transfer accepted successfully.');
    }


    // Reject transfer
    public function reject($id)
    {
        $transfer = StockTransfer::findOrFail($id);
        $transfer->tr_transfer_status = 'Rejected';
        $transfer->tr_date_received = now();
        $transfer->tr_received_by = session('loggedUser')->user_id; // use session
        $transfer->save();

        return redirect()->route('subdept.inbox')->with('error', 'Stock transfer rejected.');
    }

    //INBOX MAIN STORE - ( Manage semua action from MAIN STORE inbox )
    // Show inbox for logged-in user (e.g., ICU department)
    public function indexMS()
    {
        $user = session('loggedUser');
        $department = $user->u_unit; // e.g. "ICU"

        // Fetch transfers
        $transferMessages = StockTransfer::where('tr_destination', $department)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($msg) {
                return [
                    'type' 	 => 'transfer',
                    'id' 	 => $msg->transfer_id,
                    'from' 	 => $msg->tr_from_unit,
                    'date' 	 => $msg->created_at,
                    'status' => $msg->tr_transfer_status,
                ];
            });

        // Fetch requests
        $requestMessages = StockRequest::whereHas('user', function ($q) use ($department) {
                $q->where('u_unit', $department);
            })
            ->orderBy('rq_date_requested', 'desc')
            ->get()
            ->map(function ($req) {
                return [
                    'type' 			=> 'request',
                    'id' 			=> $req->request_id,
                    'item_id' 		=> $req->item_id,
                    'qty_requested' => $req->rq_quantity_requested,
                    'qty_approved' 	=> $req->rq_qty_approved,
                    'date' 			=> $req->rq_date_requested,
                    'status' 		=> $req->rq_status,
                ];
            });

        // Merge messages
        $messages = $transferMessages->merge($requestMessages)->sortByDesc('date');

        $user_id = $user->user_id; // Get the ID of the current logged-in user

        // 1. Fetch all read message keys for this user from the DATABASE
        $readRecords = ReadStatus::where('user_id', $user_id)->get();
        
        // 2. Map the database records into an array of simple keys (e.g., 'transfer_123')
        $readKeys = $readRecords->map(function ($record) {
            // Check which model the messageable_type belongs to
            $type = str_contains($record->messageable_type, 'StockTransfer') ? 'transfer' : 'request';
            return $type . '_' . $record->messageable_id;
        })->toArray();

        // 3. Apply read/unread status using the persistent database keys
        $messages = $messages->map(function ($msg) use ($readKeys) {
            $key = $msg['type'].'_'.$msg['id'];
            $msg['read_status'] = in_array($key, $readKeys) ? 'Read' : 'Unread';
            return $msg;
        });

        // ✅ Count unread
        $unreadInbox = $messages->where('read_status', 'Unread')->count();

       // ✅ Count unread stock requests only
       $unreadRequests = $messages->where('type', 'request')
                                 ->where('read_status', 'Unread')
                                 ->count();

        return view('main store.inbox_MS.inbox-mainstore', compact('messages', 'unreadInbox', 'unreadRequests'));
    }

    // Show details of one message (MS)
    public function showMS(Request $request, $id)
    {
        $type = $request->query('type'); // ?type=request
        
        // Determine the model class name
        $modelClass = $type === 'transfer' ? StockTransfer::class : StockRequest::class;
        $user_id = session('loggedUser')->user_id;

        // ✅ NEW DATABASE LOGIC: Mark as read persistently
        // firstOrCreate() ensures a record is only created if it doesn't already exist.
        ReadStatus::firstOrCreate([
            'user_id' => $user_id,
            'messageable_type' => $modelClass,
            'messageable_id' => $id,
        ]);
        
        // This old session logic has been removed.
        /* $readMessages = session('read_messages', []);
        $key = $type.'_'.$id;
        if (!in_array($key, $readMessages)) {
            $readMessages[] = $key;
            session(['read_messages' => $readMessages]);
        }
        */

        if ($type === 'transfer') {
            $transfer = StockTransfer::findOrFail($id);
            return view('main store.inbox_MS.inbox-transfer', compact('transfer'));

        } elseif ($type === 'request') {
            $requestData = StockRequest::findOrFail($id);
            return view('main store.inbox_MS.inbox-req', compact('requestData'));
        }

        abort(404, 'Message type not found');
    }

    // Show details of one message
    /*public function showMS(Request $request, $id)
    {
        // ... (The commented out version is identical to the one above it, I am removing it)
    }*/


    // Accept transfer → Add stock from other department to main store
    public function acceptMS($id)
    {
        $transfer = StockTransfer::findOrFail($id);

        // Deduct from main store stock
        $mainItem = Item::where('item_id', $transfer->item_id)->first();
        if ($mainItem) {
            $mainItem->i_quantity_in_stock += abs($transfer->tr_quantity);
            $mainItem->save();
        }

        // Update transfer details
        $transfer->tr_transfer_status = 'Received';
        $transfer->tr_date_received = now();
        $transfer->tr_received_by = session('loggedUser')->user_id; // use session
        $transfer->save();

        return redirect()->route('mainstore.inbox')
            ->with('success', 'Stock transfer accepted and added to main store successfully.');
    }

    //Reject transfer from other department
    public function rejectMS($id)
    {
        $transfer = StockTransfer::findOrFail($id);
        $transfer->tr_transfer_status = 'Rejected';
        $transfer->tr_date_received = now();
        $transfer->tr_received_by = session('loggedUser')->user_id; // use session
        $transfer->save();

        return redirect()->route('mainstore.inbox')->with('error', 'Stock transfer rejected.');
    }

    public function markAsRead($type, $id) 
    {
        $user = auth()->user(); // Assuming standard Laravel auth
        
        // Determine the correct model class
        $modelClass = ($type === 'transfer') ? App\Models\StockTransfer::class : App\Models\StockRequest::class;

        // Check if a read status already exists to prevent duplicates
        $readStatus = ReadStatus::firstOrCreate([
            'user_id' => $user->user_id, // Use your custom user ID field
            'messageable_type' => $modelClass,
            'messageable_id' => $id,
        ]);

        return redirect()->route('mainstore.inbox.index')->with('success', 'Message marked as read!');
    }
}
