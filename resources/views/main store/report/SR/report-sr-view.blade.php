@extends('layouts.main_store_layout')

@section('title', 'Stock Request Details')

@section('content')
<div class="main">
    <div class="header d-flex justify-content-between align-items-center">
        <div>
            <h2>Stock Request Details</h2>
            <p>Home / Report - Stock Request Details - {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</p>
        </div>
    </div>

    <div class="card p-4">

        <!-- Buttons -->
        <div class="action-bar">
            <button onclick="window.print();" class="btn btn-primary">
                <span>🖨</span> Print Slip
            </button>
        </div>

        <!-- Date Header -->
        <div class="date-header-box mb-4">
            <h4 class="mb-2">
                <i class="fas fa-calendar-alt"></i> 
                Supply Date: {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
            </h4>
            <p class="text-muted mb-0">Total Items Supplied: {{ $stockRequests->count() }}</p>
        </div>

        <!-- Requested Items for this Date -->
        <h4 class="mt-4 mb-3">Supplied Items</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 60px;">No.</th>
                        <th style="width: 110px;">Request ID</th>
                        <th style="width: 280px; min-width: 220px;">Item Name</th>
                        <th style="width: 130px;">Stock ID</th>
                        <th style="width: 220px; min-width: 180px;">Description/Brand</th>
                        <th style="width: 120px;">Qty Requested</th>
                        <th style="width: 120px;">Qty Approved</th>
                        <th style="width: 80px;">Unit</th>
                        <th style="width: 110px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stockRequests as $index => $request)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $request->request_id }}</td>
                        <td class="text-wrap">
                            <div class="item-name-cell" title="{{ $request->item->i_name ?? 'N/A' }}">
                                {{ $request->item->i_name ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="text-center">{{ $request->item->i_stockID ?? 'N/A' }}</td>
                        <td class="text-wrap">
                            <div class="description-cell" title="{{ $request->item->i_description ?? '-' }}">
                                {{ $request->item->i_description ?? '-' }}
                            </div>
                        </td>
                        <td class="text-center">{{ $request->rq_quantity_requested }}</td>
                        <td class="text-center">{{ $request->rq_qty_approved ?? '-' }}</td>
                        <td class="text-center">{{ $request->item->i_unit ?? '-' }}</td>
                        <td class="text-center">
                            <span class="status-badge {{ strtolower($request->rq_status) }}">
                                {{ ucfirst($request->rq_status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary Section -->
        <div class="summary-box mt-4">
            <div class="summary-row">
                <strong>Total Items:</strong>
                <span>{{ $stockRequests->count() }}</span>
            </div>
            <div class="summary-row">
                <strong>Total Quantity Approved:</strong>
                <span>{{ $stockRequests->sum('rq_qty_approved') }}</span>
            </div>
            <div class="summary-row">
                <strong>Requested By:</strong>
                <span>{{ $stockRequests->first()->requestedBy->u_name ?? 'N/A' }}</span>
            </div>
            <div class="summary-row">
                <strong>Approved By:</strong>
                <span>{{ $stockRequests->first()->approvedByUser->u_name ?? 'N/A' }}</span>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-4">
            <a href="{{ route('reports.stock-request.list') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back to List
            </a>
        </div>

    </div>
</div>

<style>
    .date-header-box {
        background: linear-gradient(135deg, #0f3e59 0%, #1a5a7d 100%);
        color: white;
        padding: 20px;
        border-radius: 8px;
    }

    .date-header-box h4 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
    }

    .date-header-box i {
        margin-right: 8px;
    }

    .action-bar {
        display: flex;
        gap: 12px;
        flex-shrink: 0;
        margin-bottom: 20px;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: #007bff;
        color: white;
    }

    .btn-primary:hover {
        background: #0056b3;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
        color: white;
        text-decoration: none;
    }

    /* Table Improvements */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table {
        table-layout: fixed;
        width: 100%;
        margin-bottom: 0;
    }

    .table th {
        font-weight: 600;
        font-size: 13px;
        white-space: normal !important;  /* Changed from nowrap to normal */
        background-color: #343a40 !important;
        color: #ffffff !important;
        padding: 12px 8px;  /* Add more padding */
        line-height: 1.3;  /* Adjust line height for wrapped text */
        vertical-align: middle;
    }

    .table td {
        vertical-align: middle;
        font-size: 14px;
        overflow: hidden;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Text wrapping cells */
    .item-name-cell,
    .description-cell {
        word-wrap: break-word;
        word-break: break-word;
        overflow-wrap: break-word;
        hyphens: auto;
        max-height: 100px;
        overflow-y: auto;
        line-height: 1.4;
        padding: 2px 0;
    }

    /* Scrollbar styling for cells */
    .item-name-cell::-webkit-scrollbar,
    .description-cell::-webkit-scrollbar {
        width: 4px;
    }

    .item-name-cell::-webkit-scrollbar-track,
    .description-cell::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .item-name-cell::-webkit-scrollbar-thumb,
    .description-cell::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .item-name-cell::-webkit-scrollbar-thumb:hover,
    .description-cell::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .text-wrap {
        white-space: normal !important;
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
        white-space: nowrap;
    }

    .status-badge.approved {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .status-badge.pending {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .status-badge.rejected {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .summary-box {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #dee2e6;
    }

    .summary-row:last-child {
        border-bottom: none;
    }

    .summary-row strong {
        color: #495057;
    }

    .summary-row span {
        color: #212529;
        font-weight: 500;
    }

    /* Print Styles */
    @media print {
        .action-bar,
        .btn-secondary {
            display: none !important;
        }
        
        .card {
            box-shadow: none;
            border: none;
        }

        .table {
            font-size: 11px;
        }

        .item-name-cell,
        .description-cell {
            max-height: none;
            overflow: visible;
        }
    }

    /* Responsive adjustments */
    @media (max-width: 1200px) {
        .table th,
        .table td {
            font-size: 12px;
            padding: 8px 6px;
        }

        .status-badge {
            font-size: 10px;
            padding: 4px 10px;
        }
    }
</style>
@endsection