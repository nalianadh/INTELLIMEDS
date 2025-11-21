@extends('layouts.main_store_layout')

@section('title', 'Stock Demand Prediction')

@section('content')
<style>
    .demand-header {
        margin-bottom: 32px;
    }

    .demand-header h2 {
        margin: 0 0 4px 0;
        font-size: 28px;
        color: #0f3e59;
        font-weight: 600;
    }

    .demand-header p {
        margin: 0;
        color: #6c757d;
        font-size: 14px;
    }

    .demand-section {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 32px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #e9ecef;
    }

    .section-title {
        font-size: 20px;
        font-weight: 600;
        margin: 0 0 20px 0;
        padding-bottom: 12px;
        border-bottom: 2px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #0f3e59;
    }

    .section-title.high::before {
        content: "📈";
        font-size: 24px;
    }

    .section-title.low::before {
        content: "📉";
        font-size: 24px;
    }

    .demand-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 20px;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }

    .demand-table thead th {
        background-color: #0f3e59;
        color: white;
        padding: 14px 16px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }

    .demand-table tbody td {
        padding: 14px 16px;
        text-align: left;
        border-bottom: 1px solid #e9ecef;
        color: #212529;
        font-size: 14px;
    }

    .demand-table tbody tr:last-child td {
        border-bottom: none;
    }

    .demand-table tbody tr {
        transition: all 0.2s ease;
    }

    .demand-table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.001);
    }

    .demand-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .demand-badge.high {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.25);
    }

    .demand-badge.low {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.25);
    }

    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 16px;
    }

    /* Override Bootstrap pagination to match theme */
    .pagination {
        display: flex;
        gap: 6px;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .page-item .page-link {
        padding: 8px 14px;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        color: #0f3e59;
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 14px;
    }

    .page-item .page-link:hover {
        background: #0f3e59;
        color: white;
        border-color: #0f3e59;
    }

    .page-item.active .page-link {
        background: #0f3e59;
        border-color: #0f3e59;
        color: white;
    }

    .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    @media (max-width: 768px) {
        .demand-section {
            padding: 16px;
        }

        .demand-table thead th,
        .demand-table tbody td {
            padding: 10px 12px;
            font-size: 13px;
        }

        .section-title {
            font-size: 18px;
        }
    }
</style>

<div class="main">
    <div class="demand-header">
        <h2>Stock Activities</h2>
        <p>Home / Stock Activities</p>
    </div>

    <!-- HIGH DEMAND SECTION -->
    <div class="demand-section">
        <h3 class="section-title high">High Demand Items</h3>
        <table class="demand-table">
            <thead>
                <tr>
                    <th>Stock</th>
                    <th>Entries</th>
                    <th>Total Quantity (Yearly)</th>
                    <th>Average Quantity (Monthly)</th>
                    <th>Demand</th>
                </tr>
            </thead>
            <tbody>
                @foreach($high as $item)
                <tr>
                    <td>{{ $item['stock'] }}</td>
                    <td>{{ $item['num_entries'] }}</td>
                    <td>{{ $item['total_quantity'] }}</td>
                    <td>{{ number_format($item['avg_quantity'], 2) }}</td>
                    <td><span class="demand-badge high">{{ $item['demand'] }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <!-- High Demand Pagination -->
        <div class="pagination-wrapper">
            {{ $high->appends(['low_page' => request()->get('low_page', 1)])->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <!-- LOW DEMAND SECTION -->
    <div class="demand-section">
        <h3 class="section-title low">Low Demand Items</h3>
        <table class="demand-table">
            <thead>
                <tr>
                    <th>Stock</th>
                    <th>Entries</th>
                    <th>Total Quantity (Yearly)</th>
                    <th>Average Quantity (Monthly)</th>
                    <th>Demand</th>
                </tr>
            </thead>
            <tbody>
                @foreach($low as $item)
                <tr>
                    <td>{{ $item['stock'] }}</td>
                    <td>{{ $item['num_entries'] }}</td>
                    <td>{{ $item['total_quantity'] }}</td>
                    <td>{{ number_format($item['avg_quantity'], 2) }}</td>
                    <td><span class="demand-badge low">{{ $item['demand'] }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Low Demand Pagination -->
        <div class="pagination-wrapper">
            {{ $low->appends(['high_page' => request()->get('high_page', 1)])->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection