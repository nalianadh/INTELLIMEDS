@extends('layouts.main_store_layout')

@section('title', 'Item Details')

@section('content')
<div class="main">
    <div class="header">
        <h2>Item Details</h2>
        <p>Item Register / View Item</p>
    </div>

    <!-- Details Container -->
    <div class="details-container">
        <!-- Header Section -->
        <div class="details-header">
            <div class="item-title-section">
                <i class="fas fa-box" style="color: #0f3e59; font-size: 24px;"></i>
                <h3>{{ $item->i_name }}</h3>
            </div>
            <a href="{{ route('items.list') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        <!-- Item Information -->
        <div class="info-section">
            <h4 class="section-title">
                <i class="fas fa-info-circle"></i> Item Information
            </h4>
            <div class="info-grid">
                <div class="info-item">
                    <label class="info-label">
                        <i class="fas fa-barcode"></i> Stock ID
                    </label>
                    <div class="info-value stock-id-badge">{{ $item->i_stockID }}</div>
                </div>
                <div class="info-item full-width">
                    <label class="info-label">
                        <i class="fas fa-align-left"></i> Description
                    </label>
                    <div class="info-value">{{ $item->i_description }}</div>
                </div>
            </div>
        </div>

        <!-- Batch & Expiry Records -->
        <div class="batch-section">
            <h4 class="section-title">
                <i class="fas fa-layer-group"></i> Batch & Expiry Records
            </h4>
            
            <div class="table-wrapper">
                <table class="batch-table">
                    <thead>
                        <tr>
                            <th>
                                <i class="fas fa-hashtag"></i> Batch Number
                            </th>
                            <th>
                                <i class="fas fa-calendar-alt"></i> Expiry Date
                            </th>
                            <th>
                                <i class="fas fa-cubes"></i> Quantity Available
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($batches as $batch)
                            <tr>
                                <td>
                                    <span class="batch-number">{{ $batch->grn_itemBatchNumber ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="expiry-date">{{ $batch->grn_itemExpiredDate ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="quantity-badge">{{ $batch->grn_available_qty ?? '-' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="empty-state">
                                    <i class="fas fa-inbox" style="font-size: 36px; display: block; margin-bottom: 12px; color: #adb5bd;"></i>
                                    No batch/expiry records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Action Footer -->
        <div class="action-footer">
            <a href="{{ route('items.list') }}" class="btn-back-bottom">
                <i class="fas fa-arrow-left"></i> Back to Item List
            </a>
        </div>
    </div>
</div>

<style>
    /* Details Container */
    .details-container {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #e9ecef;
        overflow: hidden;
        margin-top: 20px;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Header Section */
    .details-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px 28px;
        border-bottom: 2px solid #e9ecef;
        background: linear-gradient(to bottom, #f8f9fa, #ffffff);
    }

    .item-title-section {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .item-title-section h3 {
        margin: 0;
        font-size: 22px;
        font-weight: 600;
        color: #0f3e59;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: #6c757d;
        color: #ffffff;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-back:hover {
        background: #5a6268;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(108, 117, 125, 0.2);
    }

    .btn-back i {
        font-size: 12px;
    }

    /* Info Section */
    .info-section {
        padding: 28px;
        border-bottom: 1px solid #e9ecef;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
        font-weight: 600;
        color: #0f3e59;
        margin: 0 0 20px 0;
    }

    .section-title i {
        color: #0f3e59;
        font-size: 16px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .info-item.full-width {
        grid-column: 1 / -1;
    }

    .info-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-label i {
        font-size: 12px;
        color: #adb5bd;
    }

    .info-value {
        font-size: 15px;
        color: #212529;
        padding: 12px 16px;
        background: #f8f9fa;
        border-radius: 6px;
        border: 1px solid #e9ecef;
        line-height: 1.6;
    }

    .stock-id-badge {
        display: inline-block;
        padding: 8px 16px;
        background: #e7f3ff;
        color: #0066cc;
        border: 1px solid #b3d9ff;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        font-family: 'Courier New', monospace;
        width: fit-content;
    }

    /* Batch Section */
    .batch-section {
        padding: 28px;
    }

    .table-wrapper {
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        overflow: hidden;
    }

    .batch-table {
        width: 100%;
        border-collapse: collapse;
    }

    .batch-table thead {
        background: #0f3e59;
        color: #ffffff;
    }

    .batch-table thead tr th {
        padding: 14px 16px;
        font-weight: 600;
        font-size: 13px;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .batch-table thead tr th i {
        margin-right: 6px;
        opacity: 0.9;
    }

    .batch-table tbody tr {
        transition: background-color 0.2s ease;
    }

    .batch-table tbody tr:hover {
        background-color: #ffffff;
    }

    .batch-table tbody tr td {
        padding: 14px 16px;
        border-bottom: 1px solid #e9ecef;
        font-size: 14px;
        color: #495057;
    }

    .batch-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Batch Table Cell Styles */
    .batch-number {
        display: inline-block;
        padding: 4px 12px;
        background: #ffffff;
        color: #495057;
        border-radius: 4px;
        font-weight: 500;
        border: 1px solid #dee2e6;
    }

    .expiry-date {
        display: inline-flex;
        align-items: center;
        color: #495057;
        font-weight: 500;
    }

    .quantity-badge {
        display: inline-block;
        padding: 4px 12px;
        background: #d1ecf1;
        color: #0c5460;
        border-radius: 20px;
        font-weight: 600;
        font-size: 13px;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px !important;
        color: #6c757d;
        font-style: italic;
    }

    /* Action Footer */
    .action-footer {
        padding: 24px 28px;
        border-top: 2px solid #e9ecef;
        background: #f8f9fa;
    }

    .btn-back-bottom {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 24px;
        background: #0f3e59;
        color: #ffffff;
        text-decoration: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-back-bottom:hover {
        background: #1a5270;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 62, 89, 0.3);
    }

    .btn-back-bottom i {
        font-size: 12px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .details-container {
            margin: 20px 0;
            border-radius: 8px;
        }

        .details-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
            padding: 20px;
        }

        .item-title-section {
            width: 100%;
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .btn-back {
            width: 100%;
            justify-content: center;
        }

        .info-section,
        .batch-section,
        .action-footer {
            padding: 20px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .batch-table {
            min-width: 500px;
        }

        .batch-table thead tr th,
        .batch-table tbody tr td {
            padding: 10px 12px;
            font-size: 13px;
        }

        .btn-back-bottom {
            width: 100%;
            justify-content: center;
        }
    }

    /* Focus visible for accessibility */
    .btn-back:focus-visible,
    .btn-back-bottom:focus-visible {
        outline: 2px solid #0f3e59;
        outline-offset: 2px;
    }
</style>
@endsection