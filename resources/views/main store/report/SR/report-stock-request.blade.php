@extends('layouts.main_store_layout')

@section('title', 'Stock Request History')

@section('content')
<div class="main">
    <div class="header">
        <h2>Stock Request History</h2>
        <p>Home / Report - Stock Request History</p>
    </div>

    <!-- Search Bar (optional) -->
    <form method="GET" action="{{ route('reports.stock-request.list') }}" class="search-bar-form mb-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Request ID" class="search-bar-input">
        <button type="submit" class="search-bar-button">
            <i class="fas fa-search"></i> Search
        </button>
    </form>

    <!-- Table -->
    <div class="table-responsive card p-3">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Request ID</th>
                    <th>Requested By</th>
                    <th>Date Requested</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($requests as $request)
                    <tr>
                        <td>{{ $request->request_id }}</td>
                        <td>{{ $request->user->u_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($request->rq_date_requested)->format('d M Y') }}</td>
                        <td>
                            <span class="status-badge {{ strtolower($request->rq_status) }}">
                                {{ ucfirst($request->rq_status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('reports.stock-request.view', $request->request_id) }}" class="btn-view">
                                <i class="fas fa-eye"></i>
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No stock request found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $requests->links() }}
        </div>
    </div>
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
