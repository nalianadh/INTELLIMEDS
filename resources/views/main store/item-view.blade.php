@extends('layouts.main_store_layout')

@section('title', 'Item Details')

@section('content')
<div class="main">
    <div class="header">
        <h2>Item Details</h2>
        <p>Item Register / View Item</p>
    </div>

    <!-- Main Content Layout -->
    <div class="content-layout">
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

            <!-- Tab Navigation -->
            <div class="tab-navigation">
                <button class="tab-button active" data-tab="info">
                    <i class="fas fa-info-circle"></i> Information
                </button>
                <button class="tab-button" data-tab="batches">
                    <i class="fas fa-layer-group"></i> Batches
                </button>
            </div>

            <!-- Tab Contents -->
            <div class="tab-content-wrapper">
                <!-- Information Tab -->
                <div class="tab-content active" id="info-tab">
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
                            <div class="info-item full-width">
                                <label class="info-label">
                                    <i class="fas fa-align-left"></i> Minimum Quantity
                                </label>
                                <div class="info-value">{{ $item->i_minLevel }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Section in Info Tab -->
                    <div class="actions-section">
                        <h4 class="section-title">
                            <i class="fas fa-bolt"></i> Quick Actions
                        </h4>
                        <div class="actions-list">
                            <a href="{{ route('items.edit', ['item' => $item->item_id]) }}" class="action-card">
                                <div class="action-icon" style="background: #e7f3ff;">
                                    <i class="fas fa-edit" style="color: #0066cc;"></i>
                                </div>
                                <div class="action-content">
                                    <span class="action-title">Edit Item</span>
                                    <span class="action-description">Update item details</span>
                                </div>
                            </a>
                            
                            <a href="{{ route('items.transaction-log', ['item' => $item->item_id]) }}" class="action-card">
                                <div class="action-icon" style="background: #fff3cd;">
                                    <i class="fas fa-history" style="color: #856404;"></i>
                                </div>
                                <div class="action-content">
                                    <span class="action-title">View History</span>
                                    <span class="action-description">Check transaction logs</span>
                                </div>
                            </a>
                            
                            <a href="#" class="action-card"
                               onclick="event.preventDefault();
                                        if(confirm('Are you sure you want to delete this item?')) {
                                            document.getElementById('delete-item-{{ $item->item_id }}').submit();
                                        }">
                                <div class="action-icon" style="background: #f8d7da;">
                                    <i class="fas fa-trash-alt" style="color: #dc3545;"></i>
                                </div>
                                <div class="action-content">
                                    <span class="action-title">Delete Item</span>
                                    <span class="action-description">Remove from inventory</span>
                                </div>
                            </a>
                            
                            <form id="delete-item-{{ $item->item_id }}"
                                action="{{ route('items.delete', ['item' => $item->item_id]) }}"
                                method="POST"
                                style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Batches Tab -->
                <div class="tab-content" id="batches-tab">
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
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Main Content Layout */
    .content-layout {
        margin-top: 20px;
    }

    /* Details Container */
    .details-container {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #e9ecef;
        overflow: hidden;
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

    /* Tab Navigation */
    .tab-navigation {
        display: flex;
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
    }

    .tab-button {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 16px 20px;
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        color: #6c757d;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .tab-button i {
        font-size: 16px;
    }

    .tab-button:hover {
        background: #e9ecef;
        color: #495057;
    }

    .tab-button.active {
        background: #ffffff;
        color: #0f3e59;
        border-bottom-color: #0f3e59;
    }

    /* Tab Content */
    .tab-content-wrapper {
        position: relative;
    }

    .tab-content {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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

    /* Actions Section */
    .actions-section {
        padding: 28px;
    }

    .actions-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 16px;
        margin-top: 16px;
    }

    .action-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .action-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-color: #0f3e59;
        background: #ffffff;
    }

    .action-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .action-icon i {
        font-size: 18px;
    }

    .action-content {
        display: flex;
        flex-direction: column;
        gap: 2px;
        flex: 1;
    }

    .action-title {
        font-size: 14px;
        font-weight: 600;
        color: #212529;
    }

    .action-description {
        font-size: 12px;
        color: #6c757d;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .content-layout {
            margin: 20px 0;
        }

        .details-container {
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

        .tab-navigation {
            flex-direction: column;
        }

        .tab-button {
            border-bottom: none;
            border-left: 3px solid transparent;
        }

        .tab-button.active {
            border-left-color: #0f3e59;
            border-bottom-color: transparent;
        }

        .info-section,
        .batch-section,
        .actions-section {
            padding: 20px;
        }

        .actions-list {
            grid-template-columns: 1fr;
        }

        .action-card {
            padding: 14px;
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
    }

    /* Focus visible for accessibility */
    .btn-back:focus-visible,
    .tab-button:focus-visible,
    .action-card:focus-visible {
        outline: 2px solid #0f3e59;
        outline-offset: 2px;
    }
</style>

<script>
    // Tab switching functionality
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tabName = this.getAttribute('data-tab');

                // Remove active class from all buttons and contents
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));

                // Add active class to clicked button and corresponding content
                this.classList.add('active');
                document.getElementById(tabName + '-tab').classList.add('active');
            });
        });
    });
</script>
@endsection