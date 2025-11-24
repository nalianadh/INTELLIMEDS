@extends('layouts.main_store_layout')

@section('title', 'Stock Transfer Slip')

@section('content')

<style>
@media print {
    .no-print {
        display: none !important;
    }
    .main {
        padding: 0;
    }
    .transfer-slip {
        box-shadow: none;
        border: none;
    }
}

.main {
    padding: 24px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 32px;
}

.header-content h2 {
    font-size: 28px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0 0 8px 0;
}

.breadcrumb {
    color: #6c757d;
    font-size: 14px;
}

.action-bar {
    display: flex;
    gap: 12px;
    flex-shrink: 0;
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
    background: #545b62;
}

.transfer-slip {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    padding: 32px;
}

.slip-header {
    text-align: center;
    padding-bottom: 24px;
    border-bottom: 2px solid #e9ecef;
    margin-bottom: 32px;
}

.slip-title {
    font-size: 24px;
    font-weight: 700;
    color: #212529;
    margin: 0 0 8px 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.slip-subtitle {
    font-size: 14px;
    color: #6c757d;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.info-item {
    background: #f8f9fa;
    padding: 16px;
    border-radius: 8px;
    border-left: 4px solid #007bff;
}

.info-label {
    font-size: 12px;
    text-transform: uppercase;
    color: #6c757d;
    font-weight: 600;
    margin-bottom: 4px;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 16px;
    color: #212529;
    font-weight: 500;
}

.section-title {
    font-size: 18px;
    font-weight: 600;
    color: #212529;
    margin: 0 0 16px 0;
    padding-bottom: 8px;
    border-bottom: 2px solid #e9ecef;
}

.items-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 32px;
}

.items-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.items-table th {
    padding: 14px;
    text-align: left;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.items-table tbody tr {
    border-bottom: 1px solid #e9ecef;
    transition: background 0.2s ease;
}

.items-table tbody tr:hover {
    background: #f8f9fa;
}

.items-table td {
    padding: 16px 14px;
    color: #495057;
    font-size: 14px;
}

.items-table td:first-child {
    font-weight: 600;
    color: #6c757d;
}

.items-table td:last-child {
    font-weight: 600;
    color: #007bff;
}

.footer-actions {
    display: flex;
    justify-content: center;
    margin-top: 32px;
}
</style>

<div class="main">
    <div class="header no-print">
        <div class="header-content">
            <h2>Stock Transfer Slip</h2>
            <p class="breadcrumb">Home / Stock Transfer - {{ $transfer->tr_date_requested }}</p>
        </div>
        <div class="action-bar">
            <button onclick="window.print();" class="btn btn-primary">
                <span>🖨</span> Print Slip
            </button>
        </div>
    </div>

    <div class="transfer-slip">
        <div class="slip-header">
            <h1 class="slip-title">Stock Transfer Slip</h1>
            <p class="slip-subtitle">Transfer Documentation & Record</p>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Transfer Date</div>
                <div class="info-value">{{ $transfer->tr_date_requested ?? '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">From Unit</div>
                <div class="info-value">{{ $transfer->tr_from_unit ?? '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Destination</div>
                <div class="info-value">{{ $transfer->tr_destination ?? '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Requested By</div>
                <div class="info-value">{{ $transfer->user->u_name ?? '-' }}</div>
            </div>
        </div>

        <h3 class="section-title">Transferred Items</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 80px;">#</th>
                    <th>Item Name</th>
                    <th style="width: 180px;">Quantity Transferred</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ $transfer->item->i_name ?? 'N/A' }}</td>
                    <td>{{ abs($transfer->tr_quantity) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer-actions no-print">
        <a href="{{ route('stock.transfer.list') }}" class="btn btn-secondary">
            <span>←</span> Back to Transfer List
        </a>
    </div>
</div>
@endsection