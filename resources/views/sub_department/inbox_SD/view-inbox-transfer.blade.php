@extends('layouts.subdept-layout')

@section('title', 'View Inbox Message')
@section('page_title', 'Inbox Message')
@section('breadcrumb', 'Home / Inbox / View')

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

<div class="transfer-detail-container">
    <!-- Header Card -->
    <div class="detail-card header-card">
        <div class="card-header modern-header">
            <div class="header-content">
                <div class="header-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 4 23 10 17 10"></polyline>
                        <polyline points="1 20 1 14 7 14"></polyline>
                        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="header-title">Transfer Details</h3>
                    <p class="header-subtitle">Transfer ID: #{{ $transfer->transfer_id }}</p>
                </div>
            </div>
            <div class="status-badge status-{{ strtolower($transfer->tr_transfer_status) }}">
                {{ ucfirst($transfer->tr_transfer_status) }}
            </div>
        </div>
    </div>

    <!-- Transfer Information Card -->
    <div class="detail-card">
        <div class="card-section-header">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <h4>Transfer Information</h4>
        </div>
        
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    From Unit
                </div>
                <div class="info-value">{{ $transfer->tr_from_unit }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    Date Requested
                </div>
                <div class="info-value">{{ $transfer->tr_date_requested }}</div>
            </div>
        </div>
    </div>

    <!-- Item Details Card -->
    <div class="detail-card">
        <div class="card-section-header">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                <line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>
            <h4>Item & Quantity</h4>
        </div>
        
        <div class="item-quantity-section">
            <div class="item-card">
                <div class="item-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    </svg>
                </div>
                <div class="item-info">
                    <div class="item-label">Item Name</div>
                    <div class="item-name">{{ $transfer->item->i_name ?? 'Unknown Item' }}</div>
                </div>
            </div>

            <div class="quantity-card">
                <div class="quantity-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 4 23 10 17 10"></polyline>
                        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10"></path>
                    </svg>
                </div>
                <div class="quantity-info">
                    <div class="quantity-label">Quantity</div>
                    <div class="quantity-value">{{ abs($transfer->tr_quantity) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-section">
        <div class="action-buttons">
            <a href="{{ route('subdept.inbox') }}" class="btn-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                <span>Back to Inbox</span>
            </a>

            @if($transfer->tr_transfer_status === 'Pending')
                <form action="{{ route('subdept.inbox.accept', $transfer->transfer_id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-accept">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>Accept Transfer</span>
                    </button>
                </form>

                <form action="{{ route('subdept.inbox.reject', $transfer->transfer_id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-reject">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                        <span>Reject Transfer</span>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .transfer-detail-container {
        width: 100%;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .detail-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border: 1px solid #e8ecef;
        overflow: hidden;
    }

    .header-card {
        border-left: 4px solid #10b981;
    }

    .modern-header {
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
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
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
        font-weight: 500;
    }

    .status-badge {
        padding: 8px 20px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
    }

    .status-accepted {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    .status-rejected {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }

    .card-section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 20px 28px;
        background: #f8fafb;
        border-bottom: 1px solid #e8ecef;
    }

    .card-section-header svg {
        color: #1e5f74;
    }

    .card-section-header h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #0f3e59;
    }

    .info-grid {
        padding: 28px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .info-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #5a6c7d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-label svg {
        opacity: 0.7;
    }

    .info-value {
        font-size: 16px;
        font-weight: 600;
        color: #0f3e59;
    }

    /* Item & Quantity Section */
    .item-quantity-section {
        padding: 28px;
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }

    .item-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 24px;
        background: linear-gradient(135deg, #e8f4f8 0%, #d4e9f0 100%);
        border-radius: 12px;
        border: 2px solid #b8dfe8;
    }

    .item-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #1e5f74 0%, #0f3e59 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(15, 62, 89, 0.2);
    }

    .item-info {
        flex: 1;
    }

    .item-label {
        font-size: 12px;
        font-weight: 600;
        color: #5a6c7d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .item-name {
        font-size: 18px;
        font-weight: 700;
        color: #0f3e59;
        line-height: 1.3;
    }

    .quantity-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 24px;
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border-radius: 12px;
        border: 2px solid #6ee7b7;
        text-align: center;
    }

    .quantity-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .quantity-label {
        font-size: 12px;
        font-weight: 600;
        color: #065f46;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .quantity-value {
        font-size: 32px;
        font-weight: 700;
        color: #064e3b;
        line-height: 1;
    }

    /* Action Section */
    .action-section {
        padding-top: 8px;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-back,
    .btn-accept,
    .btn-reject {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-back {
        background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
        color: #2c3e50;
        border: 1px solid #ced4da;
    }

    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        background: linear-gradient(135deg, #dee2e6 0%, #ced4da 100%);
    }

    .btn-back svg {
        transition: transform 0.2s ease;
    }

    .btn-back:hover svg {
        transform: translateX(-2px);
    }

    .btn-accept {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25);
    }

    .btn-accept:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.35);
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }

    .btn-reject {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.25);
    }

    .btn-reject:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(239, 68, 68, 0.35);
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .modern-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }

        .status-badge {
            align-self: flex-start;
        }

        .info-grid {
            padding: 20px;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .item-quantity-section {
            padding: 20px;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .card-section-header {
            padding: 16px 20px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-back,
        .btn-accept,
        .btn-reject {
            width: 100%;
            justify-content: center;
        }

        .item-card {
            padding: 20px;
        }

        .quantity-card {
            padding: 20px;
        }
    }
</style>
@endpush
@endsection