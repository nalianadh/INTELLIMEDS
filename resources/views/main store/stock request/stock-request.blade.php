@extends('layouts.main_store_layout')

@section('title', 'Stock Request - Request List')

@section('content')
<div class="main">
    <div class="header">
        <h2>Stock Request</h2>
        <p>Home / Stock Request - Request List</p>
    </div>

    <!-- Search Bar -->
    <form method="GET" action="{{ route('stock-request.pending') }}" class="search-bar-form">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by request ID, department, or status..." class="search-bar-input">
        <button type="submit" class="search-bar-button">
            <i class="fas fa-search"></i>Search
        </button>
    </form>

    <!-- Stock Request Table Container -->
    <div class="table-container">
        <div class="table-header">
            <h3>Pending Stock Requests</h3>
            <span class="item-count">{{ $pendingRequests->count() }} requests</span>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 60px;">#</th>
                    <th>Request ID</th>
                    <th>Date Requested</th>
                    <th>Requested By</th>
                    <th>Status</th>
                    <th style="text-align: center; width: 120px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingRequests as $index => $request)
                    <tr>
                        <td style="text-align: center;">
                            <span style="font-weight: 600; color: #6c757d;">{{ $index + 1 }}</span>
                        </td>
                        <td>
                            <span class="stock-id-badge">REQ-{{ str_pad($request->request_id, 4, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-calendar" style="color: #6c757d; font-size: 14px;"></i>
                                <span>{{ $request->rq_date_requested ? \Carbon\Carbon::parse($request->rq_date_requested)->format('d M Y') : '-' }}</span>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-user" style="color: #6c757d; font-size: 14px;"></i>
                                <span>{{ $request->requestedBy->u_name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge {{ strtolower($request->rq_status) }}">
                                {{ ucfirst($request->rq_status) }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('stock-request.view', ['id' => $request->request_id]) }}" class="btn-view">
                                <i class="fas fa-eye"></i>
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state">
                            <div style="padding: 40px 20px;">
                                <i class="fas fa-clipboard-list" style="font-size: 48px; color: #adb5bd; display: block; margin-bottom: 16px;"></i>
                                <p style="margin: 0 0 16px 0; color: #6c757d; font-size: 14px;">No pending stock requests found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(method_exists($pendingRequests, 'links'))
        <div style="margin-top: 24px;">
            {{ $pendingRequests->links() }}
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
</style>
@endsection