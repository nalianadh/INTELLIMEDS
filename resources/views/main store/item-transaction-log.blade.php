@extends('layouts.main_store_layout')

@section('title', 'Item Transaction Log')

@section('content')
<div class="main">
    <div class="header">
        <h2>Item Transaction Log</h2>
        <p>Item Register / Transaction Log / {{ $item->i_name }}</p>
    </div>

    <!-- Transaction Logs Table -->
    <div class="table-container">
        <div class="table-header" style="display: flex; justify-content: space-between; align-items: center; padding: 16px; background: #f8f9fa; border-bottom: 1px solid #e9ecef;">
            <h3>Transaction Logs</h3>
            <span class="item-count">{{ $transactions->count() }} transactions</span>
        </div>
        
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th>TYPE</th>
                    <th>QUANTITY</th>
                    <th>FROM </th>
                    <th>APPROVED BY</th>
                    <th>STATUS</th>
                    <th>REMARKS</th>
                    <th>DATE</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                    <tr>
                        <td>
                            <span class="type-badge type-{{ strtolower($t->type) }}">
                                @if($t->type === 'Request')
                                    <i class="fas fa-hand-paper"></i>
                                @elseif($t->type === 'Transfer')
                                    <i class="fas fa-exchange-alt"></i>
                                @else
                                    <i class="fas fa-download"></i>
                                @endif
                                {{ ucfirst($t->type) }}
                            </span>
                        </td>
                        <td>
                            @if($t->type === 'Transfer')
                                <div class="quantity-transfer">
                                    <span class="qty-in">
                                        <i class="fas fa-arrow-down"></i>
                                        {{ $t->quantity_in ?? 0 }}
                                    </span>
                                    <span class="qty-divider">|</span>
                                    <span class="qty-out">
                                        <i class="fas fa-arrow-up"></i>
                                        {{ $t->quantity_out ?? 0 }}
                                    </span>
                                </div>
                            @else
                                <span class="quantity-normal">{{ $t->quantity }}</span>
                            @endif
                        </td>
                        <td>{{ $t->requested_by ?: '-' }}</td>
                        <td>{{ $t->approved_by ?: '-' }}</td>
                        <td>
                            <span class="status-badge status-{{ strtolower($t->status) }}">
                                @if(strtolower($t->status) === 'approved')
                                    <i class="fas fa-check-circle"></i>
                                @elseif(strtolower($t->status) === 'pending')
                                    <i class="fas fa-clock"></i>
                                @elseif(strtolower($t->status) === 'received')
                                    <i class="fas fa-check-double"></i>
                                @else
                                    <i class="fas fa-info-circle"></i>
                                @endif
                                {{ ucfirst($t->status) }}
                            </span>
                        </td>
                        <td>{{ $t->remarks ?: '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($t->date)->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px 0; color: #6c757d;">
                            <i class="fas fa-inbox" style="font-size: 48px; display: block; margin-bottom: 16px;"></i>
                            No transactions found for this item.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
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

/* Table Header */
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

/* Table Styles */
.table-container table {
    width: 100%;
    border-collapse: collapse;
}

.table-container table th,
.table-container table td {
    padding: 12px 16px;
    border-bottom: 1px solid #e9ecef;
}

.table-container table th {
    font-weight: 600;
    font-size: 13px;
    color: #495057;
    text-transform: uppercase;
}

.table-container table td {
    font-size: 14px;
    color: #212529;
}

.table-container table tbody tr:hover {
    background-color: #f8f9fa;
}

/* Type Badge */
.type-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
}

.type-request {
    background: #e3f2fd;
    color: #1565c0;
}

.type-transfer {
    background: #f3e5f5;
    color: #6a1b9a;
}

.type-receive {
    background: #e8f5e9;
    color: #2e7d32;
}

/* Quantity Styles */
.quantity-transfer {
    display: flex;
    align-items: center;
    gap: 8px;
}

.qty-in {
    color: #2e7d32;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.qty-out {
    color: #c62828;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.qty-divider {
    color: #adb5bd;
    font-weight: 300;
}

.quantity-normal {
    font-weight: 600;
    color: #495057;
}

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-approved {
    background: #d4edda;
    color: #155724;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-received {
    background: #d1ecf1;
    color: #0c5460;
}

.status-rejected {
    background: #f8d7da;
    color: #721c24;
}

/* Empty State */
.table-container table tbody tr td {
    text-align: left;
}

/* Responsive */
@media (max-width: 768px) {
    .table-container table th,
    .table-container table td {
        padding: 10px 12px;
        font-size: 13px;
    }
    
    .type-badge,
    .status-badge {
        font-size: 11px;
        padding: 4px 8px;
    }
    
    .quantity-transfer {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }
    
    .qty-divider {
        display: none;
    }
}
</style>
@endsection