@extends('layouts.main_store_layout')

@section('title', 'GRN List')

@section('content')
<div class="main">
    <div class="header">
        <h2>Stock Receive</h2>
        <p>Home / Stock Receive - GRN List</p>
    </div>

    <!-- Search Bar -->
    <form method="GET" action="{{ route('grn.search') }}" class="search-bar-form" style="display: flex; gap: 10px; align-items: center;">
        <input 
            type="text" 
            name="query" 
            value="{{ isset($query) ? $query : request('query') }}" 
            placeholder="Search by supplier, PO number, or received by..." 
            class="search-bar-input form-control" 
            style="flex: 1; padding: 8px;"
        >

        <button type="submit" class="search-bar-button btn btn-primary" style="display: flex; align-items: center; gap: 5px;">
            <i class="fas fa-search"></i> Search
        </button>

        @if(isset($query) && $query)
            <a href="{{ route('stock.receive.grnlist') }}" class="btn-clear">Clear</a>
        @endif
    </form>

    <!-- GRN Table Container -->
    <div class="table-container">
        <div class="table-header">
            <h3>Goods Received Notes (GRN)</h3>
            <span class="item-count">{{ $grnGroups->count() }} records</span>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 60px;">#</th>
                    <th>GRN Date</th>
                    <th>Supplier</th>
                    <th>PO Number</th>
                    <th>Received By</th>
                    <th style="text-align: center; width: 120px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($grnGroups as $group)
                    <tr>
                        <td style="text-align: center;">
                            <span style="font-weight: 600; color: #6c757d;">{{ $loop->iteration }}</span>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-calendar" style="color: #6c757d; font-size: 14px;"></i>
                                <span>{{ $group->first()->grn_date_received ? \Carbon\Carbon::parse($group->first()->grn_date_received)->format('d M Y') : '-' }}</span>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-truck" style="color: #6c757d; font-size: 14px;"></i>
                                <span style="font-weight: 500;">{{ $group->first()->grn_supplier ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="stock-id-badge">{{ $group->first()->grn_po_number ?? '-' }}</span>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-user" style="color: #6c757d; font-size: 14px;"></i>
                                <span>{{ $group->first()->grn_received_by ?? '-' }}</span>
                            </div>
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('stock.receive.grn', ['po' => $group->first()->grn_po_number, 'supplier' => $group->first()->grn_supplier]) }}" class="btn-view">
                                <i class="fas fa-eye"></i>
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">
                            <div style="padding: 40px 20px;">
                                <i class="fas fa-clipboard-list" style="font-size: 48px; color: #adb5bd; display: block; margin-bottom: 16px;"></i>
                                <p style="margin: 0 0 16px 0; color: #6c757d; font-size: 14px;">No GRN records found</p>
                                <a href="{{ route('stock.receive.form') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; background: #0f3e59; color: white; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 500; transition: all 0.2s;">
                                    <i class="fas fa-plus"></i> Create New GRN
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination if needed -->
    @if(method_exists($grnGroups, 'links'))
        <div style="margin-top: 24px;">
            {{ $grnGroups->links() }}
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