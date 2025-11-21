@extends('layouts.subdept-layout') 

@section('title', 'Inbox')
@section('page_title', 'Inbox')
@section('breadcrumb', 'Home / Inbox')

@section('content')
    {{-- SweetAlert2 Messages --}}
    @if(session('success') || session('error'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: '{{ session('success') ? 'success' : 'error' }}',
                    title: '{{ session('success') ? 'Success' : 'Error' }}',
                    text: '{{ session('success') ?? session('error') }}',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif

<div class="inbox-container">
    <div class="inbox-card">
        <div class="inbox-header">
            <div class="header-content">
                <div class="header-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 12h-6l-2 3h-4l-2-3H2"></path>
                        <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="header-title">Messages</h3>
                    <p class="header-subtitle">Your notifications and updates</p>
                </div>
            </div>
            <div class="inbox-stats">
                <span class="message-count">{{ count($messages) }} {{ count($messages) === 1 ? 'message' : 'messages' }}</span>
            </div>
        </div>

        <div class="inbox-body">
            @forelse($messages as $msg)
                <div class="message-item {{ $msg['type'] }}">
                    <div class="message-icon">
                        @if($msg['type'] === 'transfer')
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="23 4 23 10 17 10"></polyline>
                                <polyline points="1 20 1 14 7 14"></polyline>
                                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                            </svg>
                        @else
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                            </svg>
                        @endif
                    </div>

                    <div class="message-content">
                        <div class="message-text">
                            {{-- Handle stock transfers --}}
                            @if($msg['type'] === 'transfer')
                                <span class="message-label">New Transfer</span>
                                You received a new stock transfer from 
                                <strong class="highlight">{{ $msg['from'] }}</strong> 
                                <span class="message-meta">Transfer ID: <strong>{{ $msg['id'] }}</strong></span>
                            
                            {{-- Handle stock requests --}}
                            @elseif($msg['type'] === 'request')
                                <span class="message-label status-{{ strtolower($msg['status']) }}">{{ ucfirst($msg['status']) }}</span>
                                Your request <strong class="highlight">ID: {{ $msg['id'] }}</strong> 
                                for <strong>{{ $msg['qty_requested'] }}</strong> unit(s) of 
                                <strong class="highlight">{{ \App\Models\Item::find($msg['item_id'])->i_name ?? 'Unknown Item' }}</strong>
                                @if($msg['qty_approved'])
                                    <span class="message-meta">Approved: {{ $msg['qty_approved'] }} units</span>
                                @endif
                            @endif
                        </div>

                        <div class="message-footer">
                            <span class="message-date">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                {{ $msg['date'] }}
                            </span>
                            @if($msg['type'] === 'transfer')
                                <a href="{{ route('subdept.inbox.show', ['id' => $msg['id'], 'type' => 'transfer']) }}" 
                                   class="message-action">
                                    View Details
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </a>
                            @else
                                <a href="{{ route('subdept.inbox.show', ['id' => $msg['id'], 'type' => 'request']) }}" 
                                   class="message-action">
                                    View Details
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 12h-6l-2 3h-4l-2-3H2"></path>
                            <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>
                        </svg>
                    </div>
                    <h3>No messages yet</h3>
                    <p>You're all caught up! Check back later for new notifications.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('styles')
<style>
    .inbox-container {
        width: 100%;
        margin: 0;
        padding: 0;
    }

    .inbox-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border: 1px solid #e8ecef;
        overflow: hidden;
    }

    .inbox-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-bottom: 2px solid #e8ecef;
        padding: 24px 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-content {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .header-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #1e5f74 0%, #0f3e59 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 4px 12px rgba(15, 62, 89, 0.2);
    }

    .header-title {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #0f3e59;
        letter-spacing: -0.3px;
    }

    .header-subtitle {
        margin: 4px 0 0 0;
        font-size: 14px;
        color: #5a6c7d;
        font-weight: 400;
    }

    .inbox-stats {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .message-count {
        background: linear-gradient(135deg, #e8f4f8 0%, #d4e9f0 100%);
        color: #0f3e59;
        font-size: 13px;
        font-weight: 700;
        padding: 8px 16px;
        border-radius: 20px;
        letter-spacing: 0.3px;
    }

    .inbox-body {
        padding: 0;
    }

    .message-item {
        display: flex;
        gap: 16px;
        padding: 20px 28px;
        border-bottom: 1px solid #f0f3f5;
        transition: all 0.2s ease;
        position: relative;
    }

    .message-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: transparent;
        transition: background 0.2s ease;
    }

    .message-item:hover {
        background: #f8fafb;
    }

    .message-item.transfer::before {
        background: #10b981;
    }

    .message-item.request::before {
        background: #3b82f6;
    }

    .message-item:last-child {
        border-bottom: none;
    }

    .message-icon {
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 2px;
    }

    .message-item.transfer .message-icon {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #059669;
    }

    .message-item.request .message-icon {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #2563eb;
    }

    .message-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .message-text {
        font-size: 15px;
        color: #2c3e50;
        line-height: 1.6;
    }

    .message-label {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 12px;
        margin-right: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        vertical-align: middle;
    }

    .message-item.transfer .message-label {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .message-label.status-pending {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .message-label.status-approved {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .message-label.status-rejected {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .highlight {
        color: #0f3e59;
        font-weight: 600;
    }

    .message-meta {
        display: inline-block;
        margin-left: 6px;
        color: #5a6c7d;
        font-size: 14px;
    }

    .message-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .message-date {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #5a6c7d;
    }

    .message-date svg {
        opacity: 0.7;
    }

    .message-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: linear-gradient(135deg, #1e5f74 0%, #0f3e59 100%);
        color: white;
        font-size: 13px;
        font-weight: 600;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(15, 62, 89, 0.2);
    }

    .message-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(15, 62, 89, 0.3);
        background: linear-gradient(135deg, #0f3e59 0%, #0a2e44 100%);
    }

    .message-action svg {
        transition: transform 0.2s ease;
    }

    .message-action:hover svg {
        transform: translateX(2px);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 40px;
    }

    .empty-icon {
        display: inline-flex;
        width: 96px;
        height: 96px;
        background: linear-gradient(135deg, #f0f3f5 0%, #e8ecef 100%);
        border-radius: 50%;
        align-items: center;
        justify-content: center;
        margin-bottom: 24px;
        color: #b8c1c8;
    }

    .empty-state h3 {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0 0 8px 0;
    }

    .empty-state p {
        font-size: 15px;
        color: #5a6c7d;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .inbox-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }

        .header-content {
            width: 100%;
        }

        .inbox-stats {
            width: 100%;
        }

        .message-count {
            width: 100%;
            text-align: center;
        }

        .message-item {
            padding: 16px 20px;
        }

        .message-footer {
            flex-direction: column;
            align-items: flex-start;
        }

        .message-action {
            width: 100%;
            justify-content: center;
        }

        .empty-state {
            padding: 60px 24px;
        }
    }
</style>
@endpush
@endsection