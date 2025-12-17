<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReceiveNote;
use App\Models\StockTransfer;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    // MAIN STORE DASHBOARD
    public function mainStoreDashboard()
    {
        if (!session()->has('loggedUser')) {
            return redirect('/login');
        }

        $user = session('loggedUser');

        if ($user->u_role !== 'main_store') {
            return redirect('/login');
        }

        // EXPIRED SOON (within 180 days)
        $expiredItems = ReceiveNote::select(
                'receive_notes.*',
                'items.i_name',
                'items.i_stockID'
            )
            ->join('items', 'receive_notes.item_id', '=', 'items.item_id') // adjust column names if different
            ->whereNotNull('receive_notes.grn_itemExpiredDate')
            ->where('receive_notes.grn_itemExpiredDate', '<=', now()) // already expired
            ->orderBy('receive_notes.grn_itemExpiredDate', 'asc')
            ->get();


        // ALREADY EXPIRED count
        $expiredCount = ReceiveNote::whereNotNull('grn_itemExpiredDate')
            ->where('grn_itemExpiredDate', '<=', now())
            ->count();

        // OTHER DASHBOARD COUNTS
        $stockReceived = Item::count();
        $stockTransferred = StockTransfer::count();
        $lowStockItems = Item::whereColumn('i_quantity_in_stock', '<=', 'i_minLevel')->count();

        $lowStockList = Item::whereColumn('i_quantity_in_stock', '<=', 'i_minLevel')
        ->orderBy('i_quantity_in_stock', 'asc')
        ->get();

    

        return view('main store.dashboard', compact(
            'user',
            'expiredItems',
            'expiredCount',
            'stockReceived',
            'stockTransferred',
            'lowStockItems',
            'lowStockList'
        
        ));
    }

    // SUB DEPARTMENT DASHBOARD
    public function subDeptDashboard()
    {
        if (!session()->has('loggedUser')) {
            return redirect('/login');
        }

        $user = session('loggedUser');

        if ($user->u_role !== 'sub_department') {
            return redirect('/login');
        }

        $userId = $user->user_id;

        // 1) Pending Requests
        $pendingRequests = DB::table('stock_requests')
            ->where('rq_requested_by', $userId)
            ->where('rq_status', 'Pending')
            ->count();

        // 2) Received Stocks (Approved)
        $inhand = DB::table('subdepartment_stocks')
            ->where('user_id', $userId)
            ->count();

        // 3) Low Stock Items
        $lowStockItems = DB::table('subdepartment_stocks AS sds')
            ->join('items AS i', 'sds.item_id', '=', 'i.item_id')
            ->select(
                'sds.item_id',
                'i.i_name',
                'i.i_stockID',
                'i.i_minLevel',
                DB::raw('SUM(sds.sd_quantityInHand) AS net_quantity')
            )
            ->where('sds.user_id', $userId)
            ->groupBy('sds.item_id', 'i.i_name', 'i.i_stockID', 'i.i_minLevel')
            ->havingRaw('SUM(sds.sd_quantityInHand) <= i.i_minLevel')
            ->get();

        $lowStockCount = $lowStockItems->count();

        return view('sub_department.dashboard', [
            'user' => $user,
            'pendingRequests' => $pendingRequests,
            'inhand' => $inhand,
            'lowStockCount' => $lowStockCount,
            'lowStockItems' => $lowStockItems
        ]);
    }


}
