@extends('layouts.main_store_layout')

@section('title', 'Stock Request History')

@section('content')
<div class="main">
    <div class="header">
        <h2>Stock Request History</h2>
        <p>Home / Report - Stock Request History</p>
    </div>

    <!-- Search Bar -->
    <form method="GET" action="{{ route('reports.stock-request.list') }}" class="search-bar-form mb-3">
        <input 
            type="date" 
            name="search" 
            value="{{ request('search') }}" 
            class="search-bar-input"
        >
        <button type="submit" class="search-bar-button">
            <i class="fas fa-search"></i> Search
        </button>
        @if(request('search'))
            <a href="{{ route('reports.stock-request.list') }}" class="btn-clear">
                <i class="fas fa-times"></i> Clear
            </a>
        @endif
    </form>

    <!-- Date List -->
    <div class="card p-3">
        @forelse ($groupedRequests as $date => $requests)
            <div class="date-list-item">
                <div class="date-info">
                    <div class="date-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="date-details">
                        <h5 class="date-title">{{ \Carbon\Carbon::parse($date)->format('d F Y') }}</h5>
                        <p class="date-subtitle">{{ $requests->count() }} item(s) supplied</p>
                    </div>
                </div>
                <div class="date-actions">
                    <a href="{{ route('reports.stock-request.view', $date) }}" class="btn-view-date">
                        <i class="fas fa-eye"></i>
                        View Details
                    </a>
                </div>
            </div>
            @if(!$loop->last)
                <hr class="divider">
            @endif
        @empty
            <div class="empty-state">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="mb-0">No stock requests found.</p>
                @if(request('search'))
                    <p class="text-muted mt-2">Try adjusting your search criteria.</p>
                @endif
            </div>
        @endforelse
    </div>
</div>

<style>
    .card {
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid #e0e0e0;
    }

    .date-list-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 15px;
        transition: background-color 0.2s ease;
    }

    .date-list-item:hover {
        background-color: #f8f9fa;
        border-radius: 6px;
    }

    .date-info {
        display: flex;
        align-items: center;
        gap: 15px;
        flex: 1;
    }

    .date-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #0f3e59 0%, #1a5a7d 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 22px;
    }

    .date-details {
        flex: 1;
    }

    .date-title {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
    }

    .date-subtitle {
        margin: 5px 0 0 0;
        font-size: 14px;
        color: #6c757d;
    }

    .date-actions {
        display: flex;
        gap: 10px;
    }

    .btn-view-date {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 24px;
        background: #0f3e59;
        color: #ffffff;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .btn-view-date:hover {
        background: #1a5a7d;
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(15, 62, 89, 0.3);
    }

    .divider {
        margin: 0;
        border-top: 1px solid #e9ecef;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .search-bar-form {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .search-bar-input {
        flex: 1;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.2s;
    }

    .search-bar-input:focus {
        outline: none;
        border-color: #0f3e59;
    }

    .search-bar-button,
    .btn-clear {
        padding: 12px 20px;
        background: #0f3e59;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        font-weight: 500;
    }

    .search-bar-button:hover {
        background: #1a5a7d;
        color: white;
    }

    .btn-clear {
        background: #6c757d;
    }

    .btn-clear:hover {
        background: #5a6268;
        color: white;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .date-list-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .date-actions {
            width: 100%;
        }

        .btn-view-date {
            width: 100%;
            justify-content: center;
        }

        .search-bar-form {
            flex-direction: column;
        }

        .search-bar-button,
        .btn-clear {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection