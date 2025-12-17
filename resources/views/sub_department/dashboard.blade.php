@extends('layouts.subdept-layout')

@section('title', 'Sub Department Dashboard - INTELLIMEDS')
@section('page_title', 'Welcome Sub Department')
@section('breadcrumb', 'Home / Dashboard')

@section('content')

<style>
    /* Cards container */
    .cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    /* Card style */
    .card {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }

    .card h4 {
        margin-bottom: 10px;
        font-weight: 600;
        color: #333;
    }

    .card h2 {
        font-size: 40px;
        margin: 10px 0;
    }

    .card p {
        color: #999;
        margin: 0;
    }

    .card .low-stock {
        color: #e53935;
    }

    /* Alerts table */
    .alerts h3 {
        margin-bottom: 20px;
        color: #0f3e59;
    }

    .table-scroll {
        overflow-x: auto;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
    }

    thead {
        background-color: #f5f5f5;
        font-weight: 600;
        color: #333;
    }

    tbody tr {
        border-bottom: 1px solid #e0e0e0;
    }

    tbody tr:last-child {
        border-bottom: none;
    }

    tbody tr:hover {
        background-color: #f9f9f9;
    }

    td.low-stock {
        color: #e53935;
        font-weight: 600;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card h2 {
            font-size: 32px;
        }

        table {
            font-size: 14px;
        }
    }
</style>

<div class="cards">
    <!-- STOCK REQUESTED (Pending) -->
    <div class="card">
        <h4>STOCK REQUESTED</h4>
        <h2>{{ $pendingRequests }}</h2>
        <p>Pending</p>
    </div>

    <!-- IN-HAND STOCK -->
    <div class="card">
        <h4>IN-HAND STOCKS</h4>
        <h2>{{ $inhand }}</h2>
        <p>Total</p>
    </div>

    <!-- LOW STOCK ITEMS -->
    <div class="card">
        <h4>LOW STOCK ITEMS</h4>
        <h2 class="low-stock">{{ $lowStockCount }}</h2>
        <p>Below Minimum Level</p>
    </div>
</div>

<div class="alerts">
    <h3>Low Stock Items</h3>

    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Stock ID</th>
                    <th>In Stock</th>
                    <th>Min Level</th>
                    <th>Actions </th>
                </tr>
            </thead>
            <tbody>
            @forelse($lowStockItems ?? [] as $item)
                <tr>
                    <td>{{ $item->i_name }}</td>
                    <td>{{ $item->i_stockID }}</td>
                    <td class="low-stock">{{ abs($item->net_quantity) }}</td>
                    <td>{{ $item->i_minLevel }}</td>
                    <td>
                        <a href="{{ route('subdept.request', ['item_id' => $item->item_id]) }}" 
                           style="padding: 6px 12px; background: #0f3e59; color: #fff; border-radius: 4px; text-decoration: none; font-size: 14px;">
                           Request Stock
                        </a>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center; color:#7f8c9a; padding:30px;">
                        No low stock items.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
