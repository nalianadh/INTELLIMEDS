<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplyTransaction;
use App\Models\StockRequest;

class ReportController extends Controller
{
    public function index()
    {
        return view('main store.report.report-menu'); 
    }
    // Supply Transaction Report
    public function supplyTransaction(Request $request)
    {
        $query = \App\Models\SupplyTransaction::with('item')->orderBy('Date');

        // Filter by month
        if ($request->has('month') && $request->month != '') {
            $date = $request->month;
            $query->whereYear('Date', substr($date, 0, 4))
                ->whereMonth('Date', substr($date, 5, 2));
        }

        // Search by keyword
        if ($request->has('search') && $request->search != '') {
            $keyword = $request->search;
            $query->where(function($q) use ($keyword) {
                $q->where('Stock_ID', 'like', "%{$keyword}%")
                ->orWhere('Brand', 'like', "%{$keyword}%")
                ->orWhere('Site_Supplier', 'like', "%{$keyword}%")
                ->orWhere('Activity', 'like', "%{$keyword}%")
                ->orWhereHas('item', function($q2) use ($keyword) {
                    $q2->where('Stock', 'like', "%{$keyword}%"); // search item name
                });
            });
        }

        // Paginate results (15 per page)
        $transactions = $query->paginate(15)->withQueryString();

        return view('main store.report.report-supply-transaction', compact('transactions'));
    }

    public function SRlist(Request $request)
    {
        $search = $request->search;

        $requests = StockRequest::when($search, function ($query) use ($search) {
            $query->where('rq_requested_by', 'like', '%' . $search . '%');
        })
        ->orderBy('rq_date_requested', 'desc')
        ->paginate(15);

        return view('main store.report.SR.report-stock-request', compact('requests'));
    }

    public function showStockRequestSlip($id)
    {
        // FIX: Changed eager load from 'item' (singular) to 'items' (plural)
        $stockRequest = StockRequest::with(['requestedBy', 'item']) 
            ->where('request_id', $id)
            ->firstOrFail();

        return view('main store.report.SR.report-sr-view', compact('stockRequest'));
    }



}
