@extends('layouts.main_store_layout')

@section('title', 'Item List')

@section('content')
<div class="main">
    <div class="header">
        <h2>Item Register</h2>
        <p>Item Register / Registered Items</p>
    </div>

    <!-- Search Bar -->
    <form method="GET" action="{{ route('items.search') }}" class="search-bar-form" style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search items by name, stock ID, or batch..." class="search-bar-input" style="padding: 6px; width: 250px;">
        <button type="submit" class="search-bar-button">
            <i class="fas fa-search"></i> Search
        </button>
    </form>
    <!--a href="{{ route('items.create') }}" class="btn btn-primary" style="padding: 6px 12px; text-decoration: none; border-radius: 6px;">+ Register New Item</a-->

    <!-- Items Table Container -->
    <div class="table-container">
        <div class="table-header" style="display: flex; justify-content: space-between; align-items: center; padding: 16px; background: #f8f9fa; border-bottom: 1px solid #e9ecef;">
            <h3>Registered Items</h3>
            <span class="item-count">{{ $items->total() }} items</span>
        </div>
        
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th>Item Name</th>
                    <th>Stock ID</th>
                    <th>Total Quantity</th>
                    <th>Unit</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>
                            <div class="item-name-cell" style="display: flex; align-items: center; font-weight: 500; color: #212529;">
                                <i class="fas fa-box" style="color: #6c757d; margin-right: 10px;"></i>
                                <span>{{ $item->i_name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="stock-id-badge" style="display: inline-block; padding: 4px 12px; background: #e7f3ff; color: #0066cc; border-radius: 20px; font-size: 12px; font-weight: 500;">{{ $item->i_stockID }}</span>
                        </td>
                        <td>
                            <span class="quantity-cell" style="display: inline-block; padding: 4px 12px; background: #f8f9fa; color: #495057; border-radius: 20px; font-weight: 600;">{{ $item->quantity_in_stock }}</span>
                        </td>
                        <td>
                            <span class="unit-text" style="color: #6c757d;">{{ $item->i_unit ?? 'N/A' }}</span>
                        </td>
                        <td style="text-align: center;">
                            <div class="action-buttons">
                                <a href="{{ route('items.view', ['item' => $item->item_id]) }}" class="btn-action btn-view">
                                    <i class="fas fa-eye"></i> 
                                </a>
                                <a href="{{ route('items.transaction-log', ['item' => $item->item_id]) }}" class="btn-action btn-log">
                                    <i class="fas fa-history"></i>
                                </a>
                                <a href="{{ route('items.edit', ['item' => $item->item_id]) }}" class="btn-action btn-edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" 
                                class="btn-action btn-delete"
                                onclick="event.preventDefault();
                                            if(confirm('Are you sure you want to delete this item?')) {
                                                document.getElementById('delete-item-{{ $item->item_id }}').submit();
                                            }">
                                    <i class="fas fa-trash"></i>
                                </a>

                                <form id="delete-item-{{ $item->item_id }}"
                                    action="{{ route('items.delete', ['item' => $item->item_id]) }}"
                                    method="POST"
                                    style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px 0; color: #6c757d;">
                            <i class="fas fa-inbox" style="font-size: 48px; display: block; margin-bottom: 16px;"></i>
                            No items registered yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <a href="{{ route('items.sync') }}" class="btn-action btn-view" style="margin-bottom: 20px; margin-left: 10px;">
            Sync Items from Dataset
        </a>
    </div>

    <!-- Pagination -->
    @if($items->hasPages())
        <div class="pagination-container">
            {{ $items->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>

<style>
    /* Table Container */
    .table-container {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #e9ecef;
        overflow: hidden;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        border-bottom: 1px solid #e9ecef;
        background: #f8f9fa;
    }

    .table-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #0f3e59;
    }

    .item-count {
        font-size: 13px;
        color: #6c757d;
        font-weight: 500;
        background: #ffffff;
        padding: 6px 12px;
        border-radius: 20px;
        border: 1px solid #e9ecef;
    }

    /* Override table styles to match main layout */
    .table-container table {
        margin-top: 0;
        border-radius: 0;
        box-shadow: none;
        border: none;
    }

    .table-container table th {
        background-color: #f8f9fa;
        padding: 12px 16px;
        font-weight: 600;
        font-size: 13px;
        color: #495057;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e9ecef;
    }

    .table-container table td {
        padding: 12px 16px;
        font-size: 14px;
        color: #212529;
        border-bottom: 1px solid #e9ecef;
    }

    .table-container table tbody tr:last-child td {
        border-bottom: none;
    }

    .table-container table tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Item Name Cell */
    .item-name-cell {
        display: flex;
        align-items: center;
        font-weight: 500;
        color: #212529;
    }

    /* Stock ID Badge */
    .stock-id-badge {
        display: inline-block;
        padding: 4px 12px;
        background: #e7f3ff;
        color: #0066cc;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        font-family: 'Courier New', monospace;
    }

    /* Quantity Cell */
    .quantity-cell {
        display: inline-block;
        padding: 4px 12px;
        background: #f8f9fa;
        color: #495057;
        border-radius: 20px;
        font-weight: 600;
        font-size: 13px;
    }

    /* Unit Text */
    .unit-text {
        color: #6c757d;
        font-size: 14px;
    }

    /* Action Buttons Container */
    .action-buttons {
        display: inline-flex;
        gap: 8px;
        align-items: center;
        justify-content: center;
    }

    /* Base Action Button Style */
    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 16px;
        color: #ffffff;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        min-width: 80px;
    }

    .btn-action i {
        font-size: 14px;
    }

    /* View Button */
    .btn-view {
        background: #0f3e59;
        min-width: 36px;
    }

    .btn-view:hover {
        background: #1a5270;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(15, 62, 89, 0.2);
    }

    /* Log Button - Icon Only */
    .btn-log {
        background: #17a2b8;
        min-width: 40px;
        padding: 8px 12px;
    }

    .btn-log:hover {
        background: #138496;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(23, 162, 184, 0.2);
    }

    /* Edit Button - Icon Only */
    .btn-edit {
        background: #28a745;
        min-width: 40px;
        padding: 8px 12px;
    }

    .btn-edit:hover {
        background: #218838;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.2);
    }

    /* Delete Button - Icon Only */
    .btn-delete {
        background: #ea0101ff;
        min-width: 40px;
        padding: 8px 12px;
    }

    .btn-delete:hover {
        background: #c82333;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.2);
    }

    .btn-action:active {
        transform: translateY(0);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 0 !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .table-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .table-container table th,
        .table-container table td {
            padding: 10px 12px;
            font-size: 13px;
        }

        .action-buttons {
            gap: 6px;
        }

        .btn-action {
            padding: 6px 12px;
            font-size: 13px;
            min-width: 70px;
        }

        .btn-log,
        .btn-edit,
        .btn-delete {
            min-width: 36px;
            padding: 6px 10px;
        }
    }
   /* Pagination Wrapper */
.pagination-container {
    margin-top: 24px;
    padding: 20px 0;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Remove defaults */
.pagination {
    display: flex;
    gap: 8px;
    padding: 0;
    margin: 0;
    list-style: none;
}

/* Page Item */
.pagination li {
    margin: 0;
    padding: 0;
}

/* Page Links */
.pagination li a,
.pagination li span {
    display: flex;
    align-items: center;
    justify-content: center;

    min-width: 40px;
    height: 40px;

    padding: 8px 12px;
    border-radius: 10px;

    background: #ffffff;
    border: 1px solid #dee2e6;

    color: #0f3e59;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;

    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    transition: all 0.2s ease;
}

/* Hover */
.pagination li a:hover {
    background: #f0f4f7;
    border-color: #0f3e59;
    color: #0f3e59;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(15, 62, 89, 0.15);
}

/* Active */
.pagination li.active span {
    background: #0f3e59;
    color: #ffffff;
    border-color: #0f3e59;
    box-shadow: 0 3px 8px rgba(15, 62, 89, 0.25);
    cursor: default;
}

/* Disabled */
.pagination li.disabled span,
.pagination li.disabled a {
    background: #f8f9fa;
    color: #adb5bd;
    border-color: #e0e0e0;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Previous & Next */
.pagination li:first-child a,
.pagination li:last-child a,
.pagination li:first-child span,
.pagination li:last-child span {
    padding: 8px 18px;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 768px) {
    .pagination li a,
    .pagination li span {
        min-width: 34px;
        height: 34px;
        font-size: 13px;
    }
}

@media (max-width: 480px) {
    .pagination li a,
    .pagination li span {
        min-width: 30px;
        height: 30px;
        padding: 6px;
        font-size: 12px;
    }
}
</style>
@endsection