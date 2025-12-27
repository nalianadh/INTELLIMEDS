<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\ReadStatus;
use App\Models\StockTransfer;
use App\Models\StockRequest;

class SidebarComposer
{
    public function compose(View $view)
    {
        // 1. Get the current user from session
        $user = session('loggedUser');

        if (!$user) {
            // If no user, default counts
            $view->with([
                'unreadInbox' => 0,
                'unreadRequests' => 0,
                'pendingRequests' => 0
            ]);
            return;
        }

        $user_id = $user->user_id;
        $department = $user->u_unit;

        // --- Fetch messages ---
        $transferMessages = StockTransfer::where('tr_destination', $department)
            ->get()
            ->map(fn ($msg) => ['type' => 'transfer', 'id' => $msg->transfer_id]);

        $requestMessages = StockRequest::where('rq_status', 'Pending')
            ->get()
            ->map(fn ($req) => ['type' => 'request', 'id' => $req->request_id]);

        // Merge arrays safely using concat()
        $messages = $transferMessages->concat($requestMessages);

        // --- Fetch read messages for this user ---
        $readKeys = ReadStatus::where('user_id', $user_id)
            ->get()
            ->map(function ($record) {
                $type = match ($record->messageable_type) {
                    StockTransfer::class => 'transfer',
                    StockRequest::class => 'request',
                    default => null,
                };
                return $type ? $type . '_' . $record->messageable_id : null;
            })
            ->filter() // remove nulls
            ->toArray();

        // --- Apply read/unread status ---
        $messages = $messages->map(function ($msg) use ($readKeys) {
            $key = $msg['type'] . '_' . $msg['id'];
            $msg['read_status'] = in_array($key, $readKeys) ? 'Read' : 'Unread';
            return $msg;
        });

        // --- Calculate counts ---
        $unreadInbox = $messages->where('read_status', 'Unread')->count();
        $unreadRequests = $messages->where('type', 'request')->where('read_status', 'Unread')->count();
        $pendingRequests = StockRequest::where('rq_status', 'Pending')->count();

        // --- Share variables with the view ---
        $view->with([
            'unreadInbox' => $unreadInbox,
            'unreadRequests' => $unreadRequests,
            'pendingRequests' => $pendingRequests
        ]);
    }
}
