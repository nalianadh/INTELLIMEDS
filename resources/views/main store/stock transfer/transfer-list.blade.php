@extends('layouts.main_store_layout')

@section('title', 'Stock Transfer List')

@section('content')
<div class="main">
    <div class="header">
        <h2>Stock Transfer List</h2>
        <p>Home / Stock Transfer - List</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search Bar -->
    <form method="GET" action="{{ route('stock.transfer.search') }}" class="search-bar-form" style="display: flex; gap: 10px; align-items: center; margin-bottom: 20px;">
        <input 
            type="text" 
            name="query" 
            value="{{ isset($query) ? $query : request('query') }}" 
            placeholder="Search by unit, destination, status, or transfer id..." 
            class="search-bar-input form-control" 
            style="flex: 1; padding: 8px;"
        >

        <button type="submit" class="search-bar-button btn btn-primary" style="display: flex; align-items: center; gap: 5px;">
            <i class="fas fa-search"></i> Search
        </button>

        @if(isset($query) && $query)
            <a href="{{ route('stock.transfer.list') }}" class="btn-clear">Clear</a>
        @endif
    </form>

        <p>To search for Transfer ID please enter the last 4 digit only</p>
    <!-- Stock Transfer Table Container -->
    <div class="table-container">
        <div class="table-header">
            <h3>Transfer Records</h3>
            <span class="item-count">{{ $transfers->count() }} transfers</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 60px;">#</th>
                    <th>Transfer ID</th>
                    <th>Date</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Status</th>
                    <th style="text-align: center; width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transfers as $transfer)
                    <tr>
                        <td style="text-align: center;">
                            <span style="font-weight: 600; color: #6c757d;">{{ $loop->iteration }}</span>
                        </td>
                        <td>
                            <span class="stock-id-badge">STR-{{ str_pad($transfer->transfer_id, 4, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-calendar" style="color: #6c757d; font-size: 14px;"></i>
                                <span>{{ $transfer->tr_date_requested ? \Carbon\Carbon::parse($transfer->tr_date_requested)->format('d M Y') : '-' }}</span>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-hospital" style="color: #6c757d; font-size: 14px;"></i>
                                <span style="font-weight: 500;">{{ $transfer->tr_from_unit }}</span>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-map-marker-alt" style="color: #6c757d; font-size: 14px;"></i>
                                <span style="font-weight: 500;">{{ $transfer->tr_destination }}</span>
                            </div>
                        </td>
                        <td>
                            @if($transfer->tr_quantity > 0)
                                <span class="status-badge" style="background-color: #d1fae5; color: #065f46;">
                                    <i class="fas fa-arrow-down" style="font-size: 11px;"></i>
                                    Transfer In
                                </span>
                            @else
                                <span class="status-badge" style="background-color: #fee2e2; color: #991b1b;">
                                    <i class="fas fa-arrow-up" style="font-size: 11px;"></i>
                                    Transfer Out
                                </span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('transferSlip.show', $transfer->transfer_id) }}" class="btn-view">
                                <i class="fas fa-eye"></i>
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state">
                            <div style="padding: 40px 20px;">
                                <i class="fas fa-exchange-alt" style="font-size: 48px; color: #adb5bd; display: block; margin-bottom: 16px;"></i>
                                <p style="margin: 0 0 16px 0; color: #6c757d; font-size: 14px;">No transfers found</p>
                                <div style="display: flex; gap: 12px; justify-content: center;">
                                    <a href="{{ route('stock.transfer.in') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 500; transition: all 0.2s;">
                                        <i class="fas fa-arrow-down"></i> New Transfer In
                                    </a>
                                    <a href="{{ route('stock.transfer.out') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; background: #dc3545; color: white; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 500; transition: all 0.2s;">
                                        <i class="fas fa-arrow-up"></i> New Transfer Out
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(method_exists($transfers, 'links'))
        <div style="margin-top: 24px;">
            {{ $transfers->links() }}
        </div>
    @endif
</div>
    <style>
            .btn-view {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 20px;
        background: #0f3e59;
        color: #ffffff;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
                .btn-clear {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 20px;
        background: #0f3e59;
        color: #ffffff;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    </style>
@endsection