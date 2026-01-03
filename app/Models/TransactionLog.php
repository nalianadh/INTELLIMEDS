<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TransactionLog extends Model
{
    public $timestamps = false;

    public static function getItemTransactions($itemId)
    {
        // Stock Requests
        $requests = DB::table('stock_requests')
            ->leftJoin('users as requester', 'stock_requests.rq_requested_by', '=', 'requester.user_id')
            ->leftJoin('users as approver', 'stock_requests.rq_approved_by', '=', 'approver.user_id')
            ->select(
                'stock_requests.request_id as id',
                DB::raw("'Request' as type"),
                'stock_requests.rq_quantity_requested as quantity',
                DB::raw("NULL as quantity_in"),
                DB::raw("NULL as quantity_out"),
                'stock_requests.rq_status as status',
                'stock_requests.rq_date_requested as date',
                'requester.u_name as requested_by',
                'approver.u_name as approved_by',
                'stock_requests.rq_remarks as remarks'
            )
            ->where('stock_requests.item_id', $itemId);

        // Stock Transfers
        $transfers = DB::table('stock_transfers')
            ->leftJoin('users as requester', 'stock_transfers.tr_requested_by', '=', 'requester.user_id')
            ->leftJoin('users as receiver', 'stock_transfers.tr_received_by', '=', 'receiver.user_id')
            ->select(
                'stock_transfers.transfer_id as id',
                DB::raw("'Transfer' as type"),
                DB::raw("NULL as quantity"),
                'stock_transfers.tr_in_quantity as quantity_in',
                'stock_transfers.tr_out_quantity as quantity_out',
                'stock_transfers.tr_transfer_status as status',
                'stock_transfers.tr_date_requested as date',
                'requester.u_name as requested_by',
                'receiver.u_name as approved_by',
                'stock_transfers.tr_remarks as remarks'
            )
            ->where('stock_transfers.item_id', $itemId);

        // Receive Notes
        $receives = DB::table('receive_notes')
            ->select(
                'grn_id as id',
                DB::raw("'Receive' as type"),
                'grn_quantity_received as quantity',
                DB::raw("NULL as quantity_in"),
                DB::raw("NULL as quantity_out"),
                DB::raw("'Received' as status"),
                'grn_date_received as date',
                'grn_received_by as requested_by',
                DB::raw("NULL as approved_by"),
                'grn_remarks as remarks'
            )
            ->where('item_id', $itemId);

        // Combine all transactions
        return $requests
            ->unionAll($transfers)
            ->unionAll($receives)
            ->orderBy('date', 'desc')
            ->get();
    }
}