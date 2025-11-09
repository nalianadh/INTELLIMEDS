<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\StockTransfer;
use App\Models\StockRequest;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            $user = session('loggedUser');

            if ($user) {
                $department = $user->u_unit;

                // Unread Inbox (transfer + requests)
                $readMessages = session('read_messages', []);

                // Retrieve all relevant transfer and request messages
                $transferMessages = StockTransfer::where('tr_destination', $department)->get();
                $requestMessages = StockRequest::whereHas('user', function ($q) use ($department) {
                    $q->where('u_unit', $department);
                })->get();

                // Map data to simple arrays for easier merging
                $transferMsgs = $transferMessages->map(function ($msg) {
                    return [
                        'type' => 'transfer',
                        'id' => $msg->transfer_id
                    ];
                });

                $requestMsgs = $requestMessages->map(function ($req) {
                    return [
                        'type' => 'request',
                        'id' => $req->request_id
                    ];
                });

                // ✅ Merge safely as normal collections (not Eloquent collections)
                $messages = collect($transferMsgs)->merge($requestMsgs);

                // Count unread inbox messages
                $unreadInbox = $messages->filter(function ($msg) use ($readMessages) {
                    $key = $msg['type'] . '_' . $msg['id'];
                    return !in_array($key, $readMessages);
                })->count();

                // Count unread stock requests only
                $unreadRequests = $requestMessages->filter(function ($req) use ($readMessages) {
                    $key = 'request_' . $req->request_id;
                    return !in_array($key, $readMessages);
                })->count();

                // Share data with all views
                $view->with(compact('unreadInbox', 'unreadRequests'));
            }
        });
    }
}
