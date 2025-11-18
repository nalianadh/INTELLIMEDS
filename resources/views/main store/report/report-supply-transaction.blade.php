@extends('layouts.main_store_layout')

@section('title', 'Report')

@section('content')
<div class="main">
    <div class="header">
        <h2>Supply Transaction Report</h2>
        <p>Home / Report - Supply Transaction Report</p>
    </div>

    <form method="GET" action="{{ route('reports.supply-transaction') }}" 
          style="background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
                 padding: 20px 24px; border-radius: 12px; 
                 margin-bottom: 24px; border: 1px solid #e9ecef;">
        <div style="display: flex; flex-wrap: wrap; gap: 16px; align-items: end;">
            <div style="flex: 1; min-width: 200px;">
                <label for="month" style="display: block; font-weight: 600; margin-bottom: 6px; color: #212529; font-size: 13px;">
                    <i class="fas fa-calendar-alt" style="margin-right: 6px; color: #6c757d;"></i>
                    Select Month
                </label>
                <input type="month" id="month" name="month" value="{{ request('month') }}" 
                    style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 8px; font-size: 14px;">
            </div>

            <div style="flex: 2; min-width: 250px;">
                <label for="search" style="display: block; font-weight: 600; margin-bottom: 6px; color: #212529; font-size: 13px;">
                    <i class="fas fa-search" style="margin-right: 6px; color: #6c757d;"></i>
                    Search Keyword
                </label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                    placeholder="Search by stock ID, item name, or department..." 
                    style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 8px; font-size: 14px;">
            </div>

            <div style="display: flex; gap: 10px; align-items: flex-end; padding-top: 4px;">
                <button type="submit" 
                        style="background: #0f3e59; color: white; border: none; border-radius: 8px; padding: 10px 22px; font-size: 14px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>
                <a href="{{ route('reports.supply-transaction') }}" 
                   style="background: #6c757d; color: white; border: none; border-radius: 8px; padding: 10px 22px; font-size: 14px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
                    <i class="fas fa-redo"></i>
                    Reset
                </a>
            </div>
        </div>
    </form>

    <div style="margin-bottom: 12px; display: flex; justify-content: flex-end; gap: 10px;">
        <a href="#" 
           style="background: #198754; color: white; border-radius: 6px; padding: 8px 16px; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
        <a href="#" 
           style="background: #dc3545; color: white; border-radius: 6px; padding: 8px 16px; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>

    <div style="background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #e9ecef;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="margin: 0; color: #0f3e59; font-size: 18px; font-weight: 600;">Supply Transactions</h3>
            <span style="color: #6c757d; font-size: 13px;">{{ $transactions->total() }} results</span>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f1f5f9;">
                    <tr>
                        <th style="padding: 10px 14px; text-align: left;">Date</th>
                        <th style="padding: 10px 14px; text-align: left;">Stock ID</th>
                        <th style="padding: 10px 14px; text-align: left;">Item Name</th>
                        <th style="padding: 10px 14px; text-align: center;">Quantity</th>
                        <th style="padding: 10px 14px; text-align: left;">Brand</th>
                        <th style="padding: 10px 14px; text-align: left;">Department</th>
                        <th style="padding: 10px 14px; text-align: left;">Activity</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr style="border-bottom: 1px solid #e9ecef;">
                            <td style="padding: 10px 14px;">{{ $transaction->Date }}</td>
                            <td style="padding: 10px 14px;">{{ $transaction->Stock_ID }}</td>
                            <td style="padding: 10px 14px;">{{ $transaction->item->Stock ?? $transaction->Stock }}</td>
                            <td style="padding: 10px 14px; text-align: center;">{{ $transaction->Quantity }}</td>
                            <td style="padding: 10px 14px;">{{ $transaction->Brand }}</td>
                            <td style="padding: 10px 14px;">{{ $transaction->Site_Supplier }}</td>
                            <td style="padding: 10px 14px;">{{ $transaction->Activity }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px;">
                                <i class="fas fa-box-open" style="font-size: 48px; color: #adb5bd; display: block; margin-bottom: 12px;"></i>
                                <p style="margin: 0; color: #6c757d; font-size: 14px;">No transactions found for the selected criteria</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top: 24px; display: flex; justify-content: space-between; align-items: center;">
        <a href="{{ url()->previous() }}" 
           style="background: #6c757d; color: white; border: none; border-radius: 6px; padding: 10px 20px; font-size: 14px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>

        <div class="pagination-container">
            {{ $transactions->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<style>
    table tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Pagination Styling */
    .pagination-container nav {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .pagination {
        margin: 0;
        display: flex;
        gap: 6px;
    }

    /* 🎯 THE FIX: Remove the black dots (list item markers) */
    .pagination .page-item {
        list-style: none;
    }
    /* 🎯 END FIX */

    .pagination .page-item .page-link {
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        color: #495057;
        background: #fff;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.2s;
        /* Ensure it's displayed correctly for the numbers/icons */
        display: inline-block; 
    }

    .pagination .page-item.active .page-link {
        background-color: #0f3e59;
        border-color: #0f3e59;
        color: #fff;
    }

    .pagination .page-link:hover:not(.page-item.disabled .page-link) {
        background-color: #f8f9fa;
    }

    .pagination .page-item.disabled .page-link {
        color: #adb5bd;
        pointer-events: none;
    }

    /* Buttons Hover */
    button[type="submit"]:hover {
        background: #1a5270;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(15, 62, 89, 0.2);
    }

    a[href*="transaction"]:hover,
    a[href*="previous"]:hover {
        background: #5a6268;
        transform: translateY(-1px);
    }
</style>
@endsection