@extends('layouts.subdept-layout')

@section('title', 'In-Hand Stock')
@section('page_title', 'Sub Department In-Hand Stock')
@section('breadcrumb', 'Home / In-Hand Stock')

@section('content')
<style>
    .stock-container {
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .stock-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    
    .stock-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .stock-table thead th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        letter-spacing: 0.5px;
        font-size: 14px;
    }
    
    .stock-table thead th.text-center {
        text-align: center;
    }
    
    .stock-table tbody tr {
        border-bottom: 1px solid #e0e0e0;
        transition: background-color 0.2s ease;
    }
    
    .stock-table tbody tr:hover {
        background-color: #f8f9ff;
    }
    
    .stock-table tbody td {
        padding: 15px;
        color: #333;
    }
    
    .view-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        font-size: 13px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
    }
    
    .view-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(102, 126, 234, 0.4);
    }
    
    .view-btn:active {
        transform: translateY(0);
    }
    
    .batch-details {
        background: #f8f9ff;
        padding: 20px;
        margin: 5px 0;
        border-left: 4px solid #667eea;
    }
    
    .batch-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    
    .batch-table thead {
        background: #e9ecef;
        color: #495057;
    }
    
    .batch-table thead th {
        padding: 12px;
        font-weight: 600;
        font-size: 13px;
        text-align: left;
    }
    
    .batch-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
    }
    
    .batch-table tbody tr:last-child {
        border-bottom: none;
    }
    
    .batch-table tbody td {
        padding: 12px;
        color: #555;
        font-size: 14px;
    }
    
    .quantity-badge {
        display: inline-block;
        background: #667eea;
        color: white;
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
    }
    
    .no-stock {
        text-align: center;
        padding: 40px;
        color: #999;
        font-size: 15px;
    }
    
    .item-name {
        font-weight: 600;
        color: #2c3e50;
        font-size: 15px;
    }
</style>

<div class="stock-container">
    <table class="stock-table">
        <thead>
            <tr>
                <th>Item Name</th>
                <th class="text-center">Total Quantity</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($finalStock->groupBy('i_name') as $itemName => $batches)
                <tr>
                    <td class="item-name">{{ $itemName }}</td>
                    <td style="text-align:center;">
                        <span class="quantity-badge">{{ $batches->sum('sd_quantityInHand') }}</span>
                    </td>
                    <td style="text-align:center;">
                        <button 
                            onclick="toggleBatches('batches-{{ $loop->index }}')" 
                            class="view-btn">
                            View Batches
                        </button>
                    </td>
                </tr>
                <tr id="batches-{{ $loop->index }}" style="display: none;">
                    <td colspan="3" style="padding: 0;">
                        <div class="batch-details">
                            <h4 style="margin: 0 0 15px 0; color: #667eea; font-size: 16px;">Batch Details for {{ $itemName }}</h4>
                            <table class="batch-table">
                                <thead>
                                    <tr>
                                        <th>Batch Number</th>
                                        <th style="text-align: center;">Expiry Date</th>
                                        <th style="text-align: center;">Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($batches as $batch)
                                        <tr>
                                            <td>{{ $batch->sd_batchNumber ?: '-' }}</td>
                                            <td style="text-align: center;">
                                                {{ $batch->sd_expiryDate ? \Carbon\Carbon::parse($batch->sd_expiryDate)->format('d M Y') : '-' }}
                                            </td>
                                            <td style="text-align: center; font-weight: 600;">{{ $batch->sd_quantityInHand }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="no-stock">No in-hand stock available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
function toggleBatches(id) {
    const element = document.getElementById(id);
    if (element.style.display === 'none') {
        element.style.display = 'table-row';
    } else {
        element.style.display = 'none';
    }
}
</script>
@endsection