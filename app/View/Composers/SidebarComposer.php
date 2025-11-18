<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\ReadStatus;
use App\Models\StockTransfer;
use App\Models\StockRequest;
use Illuminate\Support\Facades\Auth; // Use Auth facade if you can, otherwise use session helper

class SidebarComposer
{
    public function compose(View $view)
    {
        // 1. Get the current user
        // NOTE: Use the correct method to get the user object based on your custom session logic
        $user = session('loggedUser'); 
        
        if (!$user) {
            $view->with(['unreadInbox' => 0, 'unreadRequests' => 0]);
            return;
        }

        $user_id = $user->user_id;
        $department = $user->u_unit;

        // --- Logic copied from your Controller ---
        
        // Fetch all transfers and requests for this user/department
        $transferMessages = StockTransfer::where('tr_destination', $department)
            ->get()
            ->map(fn ($msg) => ['type' => 'transfer', 'id' => $msg->transfer_id]);

        $requestMessages = StockRequest::whereHas('user', function ($q) use ($department) {
                $q->where('u_unit', $department);
            })
            ->get()
            ->map(fn ($req) => ['type' => 'request', 'id' => $req->request_id]);

        $messages = $transferMessages->merge($requestMessages);

        // Fetch all read message keys
        $readRecords = ReadStatus::where('user_id', $user_id)->get();
        
        $readKeys = $readRecords->map(function ($record) {
            $type = '';
            // NOTE: Ensure these class names match your ReadStatus storage
            if ($record->messageable_type === StockTransfer::class) {
                $type = 'transfer';
            } elseif ($record->messageable_type === StockRequest::class) {
                $type = 'request';
            }
            return !empty($type) ? $type . '_' . $record->messageable_id : null;
        })->filter()->toArray();

        // Apply read/unread status
        $messages = $messages->map(function ($msg) use ($readKeys) {
            $key = $msg['type'].'_'.$msg['id'];
            $msg['read_status'] = in_array($key, $readKeys) ? 'Read' : 'Unread';
            return $msg;
        });
        
        // Calculate counts
        $unreadInbox = $messages->where('read_status', 'Unread')->count();
        $unreadRequests = $messages->where('type', 'request')->where('read_status', 'Unread')->count();

        // --- END Logic copied from your Controller ---

        // Share variables with the view
        $view->with([
            'unreadInbox' => $unreadInbox,
            'unreadRequests' => $unreadRequests
        ]);
    }
}