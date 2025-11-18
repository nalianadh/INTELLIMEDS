@extends('layouts.main_store_layout')

@section('title', 'Inbox')

@section('content')
<div class="main">
    <div class="header">
        <h2>Inbox</h2>
        <p>Home / Inbox - Request Details</p>
    </div>

    <!-- Request Details Container -->
    <div style="max-width: 800px; background: #ffffff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); border: 1px solid #e9ecef; overflow: hidden;">
        
        <!-- Header Section -->
        <div style="background: #f8f9fa; padding: 20px 24px; border-bottom: 1px solid #e9ecef;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: #0f3e59; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-clipboard-list"></i>
                Request Details
            </h3>
        </div>

        <!-- Details Section -->
        <div style="padding: 24px;">
            <table style="width: 100%; border-collapse: collapse;">
                <tbody>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 14px 0; width: 35%; color: #6c757d; font-size: 14px; font-weight: 500;">
                            <i class="fas fa-hashtag" style="margin-right: 8px; color: #adb5bd; width: 16px;"></i>
                            Request ID
                        </td>
                        <td style="padding: 14px 0; color: #212529; font-size: 14px;">
                            <span class="stock-id-badge">REQ-{{ str_pad($requestData->request_id, 4, '0', STR_PAD_LEFT) }}</span>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 14px 0; color: #6c757d; font-size: 14px; font-weight: 500;">
                            <i class="fas fa-user" style="margin-right: 8px; color: #adb5bd; width: 16px;"></i>
                            Requested By
                        </td>
                        <td style="padding: 14px 0; color: #212529; font-size: 14px; font-weight: 500;">
                            {{ $requestData->user->u_name ?? 'Unknown User' }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 14px 0; color: #6c757d; font-size: 14px; font-weight: 500;">
                            <i class="fas fa-box" style="margin-right: 8px; color: #adb5bd; width: 16px;"></i>
                            Item
                        </td>
                        <td style="padding: 14px 0; color: #212529; font-size: 14px; font-weight: 500;">
                            {{ $requestData->item->i_name ?? 'Unknown Item' }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 14px 0; color: #6c757d; font-size: 14px; font-weight: 500;">
                            <i class="fas fa-sort-amount-up" style="margin-right: 8px; color: #adb5bd; width: 16px;"></i>
                            Quantity Requested
                        </td>
                        <td style="padding: 14px 0; color: #212529; font-size: 14px;">
                            <span class="quantity-cell">{{ $requestData->rq_quantity_requested }}</span>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 14px 0; color: #6c757d; font-size: 14px; font-weight: 500;">
                            <i class="fas fa-check-circle" style="margin-right: 8px; color: #adb5bd; width: 16px;"></i>
                            Quantity Approved
                        </td>
                        <td style="padding: 14px 0; color: #212529; font-size: 14px;">
                            @if($requestData->rq_qty_approved)
                                <span class="quantity-cell">{{ $requestData->rq_qty_approved }}</span>
                            @else
                                <span style="color: #adb5bd; font-size: 13px;">-</span>
                            @endif
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 14px 0; color: #6c757d; font-size: 14px; font-weight: 500;">
                            <i class="fas fa-info-circle" style="margin-right: 8px; color: #adb5bd; width: 16px;"></i>
                            Status
                        </td>
                        <td style="padding: 14px 0; color: #212529; font-size: 14px;">
                            @if($requestData->rq_status === 'Pending')
                                <span class="status-badge pending">
                                    <i class="fas fa-clock" style="font-size: 11px;"></i>
                                    Pending
                                </span>
                            @elseif($requestData->rq_status === 'Approved')
                                <span class="status-badge approved">
                                    <i class="fas fa-check" style="font-size: 11px;"></i>
                                    Approved
                                </span>
                            @elseif($requestData->rq_status === 'Rejected')
                                <span class="status-badge rejected">
                                    <i class="fas fa-times" style="font-size: 11px;"></i>
                                    Rejected
                                </span>
                            @else
                                <span class="status-badge" style="background-color: #f3f4f6; color: #374151;">
                                    {{ $requestData->rq_status }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 14px 0; color: #6c757d; font-size: 14px; font-weight: 500;">
                            <i class="fas fa-calendar" style="margin-right: 8px; color: #adb5bd; width: 16px;"></i>
                            Date Requested
                        </td>
                        <td style="padding: 14px 0; color: #212529; font-size: 14px;">
                            {{ $requestData->rq_date_requested ? \Carbon\Carbon::parse($requestData->rq_date_requested)->format('d M Y') : '-' }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 14px 0; color: #6c757d; font-size: 14px; font-weight: 500;">
                            <i class="fas fa-calendar-check" style="margin-right: 8px; color: #adb5bd; width: 16px;"></i>
                            Date Approved
                        </td>
                        <td style="padding: 14px 0; color: #212529; font-size: 14px;">
                            {{ $requestData->rq_date_approved ? \Carbon\Carbon::parse($requestData->rq_date_approved)->format('d M Y') : '-' }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 14px 0; color: #6c757d; font-size: 14px; font-weight: 500;">
                            <i class="fas fa-user-check" style="margin-right: 8px; color: #adb5bd; width: 16px;"></i>
                            Approved By
                        </td>
                        <td style="padding: 14px 0; color: #212529; font-size: 14px;">
                            {{ $requestData->approvedByUser->u_name ?? 'Not Approved' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 14px 0; color: #6c757d; font-size: 14px; font-weight: 500; vertical-align: top;">
                            <i class="fas fa-comment" style="margin-right: 8px; color: #adb5bd; width: 16px;"></i>
                            Remarks
                        </td>
                        <td style="padding: 14px 0; color: #212529; font-size: 14px;">
                            {{ $requestData->rq_remarks ?: '-' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Action Buttons Section -->
        <div style="background: #f8f9fa; padding: 20px 24px; border-top: 1px solid #e9ecef; display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('subdept.inbox') }}" 
               style="padding: 10px 24px; border-radius: 6px; background: #6c757d; color: white; text-decoration: none; font-size: 14px; font-weight: 500; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>
        </div>
    </div>
</div>

<style>
    /* Button Hover Effects */
    a[href*="inbox"]:hover {
        background: #5a6268;
        transform: translateY(-1px);
    }
</style>
@endsection