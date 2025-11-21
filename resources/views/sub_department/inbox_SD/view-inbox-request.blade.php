@extends('layouts.subdept-layout')

@section('title', 'View Inbox Message')
@section('page_title', 'Inbox Message')
@section('breadcrumb', 'Home / Inbox / View')

@section('content')
<div class="detail-container">
    <!-- Header Card -->
    <div class="detail-card header-card">
        <div class="card-header modern-header">
            <div class="header-content">
                <div class="header-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                </div>
                <div>
                    <h3 class="header-title">Request Details</h3>
                    <p class="header-subtitle">Request ID: #{{ $requestData->request_id }}</p>
                </div>
            </div>
            <div class="status-badge status-{{ strtolower($requestData->rq_status) }}">
                {{ ucfirst($requestData->rq_status) }}
            </div>
        </div>
    </div>

    <!-- Request Information Card -->
    <div class="detail-card">
        <div class="card-section-header">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <h4>Request Information</h4>
        </div>
        
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Requested By
                </div>
                <div class="info-value">{{ $requestData->user->u_name ?? 'Unknown User' }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    Date Requested
                </div>
                <div class="info-value">{{ $requestData->rq_date_requested }}</div>
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
            <h4>Item Details</h4>
        </div>
        
        <div class="info-grid">
            <div class="info-item featured">
                <div class="info-label">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    </svg>
                    Item Name
                </div>
                <div class="info-value">{{ $requestData->item->i_name ?? 'Unknown Item' }}</div>
            </div>
        </div>
    </div>

    <!-- Quantity Information Card -->
    <div class="detail-card">
        <div class="card-section-header">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="20" x2="18" y2="10"></line>
                <line x1="12" y1="20" x2="12" y2="4"></line>
                <line x1="6" y1="20" x2="6" y2="14"></line>
            </svg>
            <h4>Quantity Information</h4>
        </div>
        
        <div class="info-grid quantity-grid">
            <div class="info-item quantity-card requested">
                <div class="quantity-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 11 12 14 22 4"></polyline>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    </svg>
                </div>
                <div class="quantity-info">
                    <div class="quantity-label">Requested</div>
                    <div class="quantity-value">{{ $requestData->rq_quantity_requested }}</div>
                </div>
            </div>

            <div class="info-item quantity-card approved">
                <div class="quantity-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <div class="quantity-info">
                    <div class="quantity-label">Approved</div>
                    <div class="quantity-value">{{ $requestData->rq_qty_approved }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approval Details Card -->
    <div class="detail-card">
        <div class="card-section-header">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <h4>Approval Details</h4>
        </div>
        
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Approved By
                </div>
                <div class="info-value">{{ $requestData->approvedByUser->u_name ?? 'Not Approved' }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    Date Approved
                </div>
                <div class="info-value">{{ $requestData->rq_date_approved }}</div>
            </div>
        </div>
    </div>

    <!-- Remarks Card -->
    @if($requestData->rq_remarks)
    <div class="detail-card">
        <div class="card-section-header">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <h4>Remarks</h4>
        </div>
        
        <div class="remarks-content">
            <p>{{ $requestData->rq_remarks }}</p>
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('subdept.inbox') }}" class="btn-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
            <span>Back to Inbox</span>
        </a>
    </div>
</div>

@push('styles')
<style>
    .detail-container {
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
        border-left: 4px solid #1e5f74;
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

    .status-approved {
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

    .quantity-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .info-item.featured {
        grid-column: 1 / -1;
        padding: 20px;
        background: linear-gradient(135deg, #e8f4f8 0%, #d4e9f0 100%);
        border-radius: 10px;
        border: 2px solid #b8dfe8;
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

    .info-item.featured .info-value {
        font-size: 18px;
        font-weight: 700;
    }

    /* Quantity Cards */
    .quantity-card {
        flex-direction: row;
        align-items: center;
        padding: 20px;
        border-radius: 10px;
        border: 2px solid;
        gap: 16px;
    }

    .quantity-card.requested {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border-color: #93c5fd;
    }

    .quantity-card.approved {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border-color: #6ee7b7;
    }

    .quantity-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .quantity-card.requested .quantity-icon {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .quantity-card.approved .quantity-icon {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .quantity-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .quantity-label {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .quantity-card.requested .quantity-label {
        color: #1e40af;
    }

    .quantity-card.approved .quantity-label {
        color: #065f46;
    }

    .quantity-value {
        font-size: 28px;
        font-weight: 700;
    }

    .quantity-card.requested .quantity-value {
        color: #1e3a8a;
    }

    .quantity-card.approved .quantity-value {
        color: #064e3b;
    }

    /* Remarks */
    .remarks-content {
        padding: 28px;
    }

    .remarks-content p {
        margin: 0;
        font-size: 15px;
        line-height: 1.6;
        color: #2c3e50;
        background: #f8fafb;
        padding: 16px 20px;
        border-radius: 8px;
        border-left: 4px solid #1e5f74;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        justify-content: flex-start;
        gap: 12px;
        padding-top: 8px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: linear-gradient(135deg, #1e5f74 0%, #0f3e59 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        box-shadow: 0 2px 8px rgba(15, 62, 89, 0.25);
    }

    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(15, 62, 89, 0.35);
        background: linear-gradient(135deg, #0f3e59 0%, #0a2e44 100%);
    }

    .btn-back svg {
        transition: transform 0.2s ease;
    }

    .btn-back:hover svg {
        transform: translateX(-2px);
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

        .quantity-grid {
            grid-template-columns: 1fr;
        }

        .card-section-header {
            padding: 16px 20px;
        }

        .remarks-content {
            padding: 20px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-back {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush
@endsection