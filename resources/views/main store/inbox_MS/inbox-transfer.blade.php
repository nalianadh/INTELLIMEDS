@extends('layouts.main_store_layout')

@section('title', 'Inbox')

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

<div class="main">
    <div class="header">
        <h2>Inbox</h2>
        <p>Home / Inbox - Transfer Details</p>
    </div>

    <!-- Transfer Details Container -->
    <div style="max-width: 800px; background: #ffffff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); border: 1px solid #e9ecef; overflow: hidden;">
        
        <!-- Header Section -->
        <div style="background: #f8f9fa; padding: 20px 24px; border-bottom: 1px solid #e9ecef;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: #0f3e59; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-exchange-alt"></i>
                Transfer Details
            </h3>
        </div>

        <!-- Details Section -->
        <div style="padding: 24px;">
            <table style="width: 100%; border-collapse: collapse;">
                <tbody>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 14px 0; width: 35%; color: #6c757d; font-size: 14px; font-weight: 500;">
                            <i class="fas fa-hashtag" style="margin-right: 8px; color: #adb5bd; width: 16px;"></i>
                            Transfer ID
                        </td>
                        <td style="padding: 14px 0; color: #212529; font-size: 14px;">
                            <span class="stock-id-badge">STR-{{ str_pad($transfer->transfer_id, 4, '0', STR_PAD_LEFT) }}</span>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 14px 0; color: #6c757d; font-size: 14px; font-weight: 500;">
                            <i class="fas fa-hospital" style="margin-right: 8px; color: #adb5bd; width: 16px;"></i>
                            From Unit
                        </td>
                        <td style="padding: 14px 0; color: #212529; font-size: 14px; font-weight: 500;">
                            {{ $transfer->tr_from_unit }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 14px 0; color: #6c757d; font-size: 14px; font-weight: 500;">
                            <i class="fas fa-box" style="margin-right: 8px; color: #adb5bd; width: 16px;"></i>
                            Item
                        </td>
                        <td style="padding: 14px 0; color: #212529; font-size: 14px; font-weight: 500;">
                            {{ $transfer->item->i_name ?? 'Unknown Item' }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 14px 0; color: #6c757d; font-size: 14px; font-weight: 500;">
                            <i class="fas fa-sort-amount-up" style="margin-right: 8px; color: #adb5bd; width: 16px;"></i>
                            Quantity
                        </td>
                        <td style="padding: 14px 0; color: #212529; font-size: 14px;">
                            <span class="quantity-cell">{{ $transfer->tr_in_quantity ?: $transfer->tr_out_quantity }}</span>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 14px 0; color: #6c757d; font-size: 14px; font-weight: 500;">
                            <i class="fas fa-info-circle" style="margin-right: 8px; color: #adb5bd; width: 16px;"></i>
                            Status
                        </td>
                        <td style="padding: 14px 0; color: #212529; font-size: 14px;">
                            @if($transfer->tr_transfer_status === 'Pending')
                                <span class="status-badge pending">
                                    <i class="fas fa-clock" style="font-size: 11px;"></i>
                                    Pending
                                </span>
                            @elseif($transfer->tr_transfer_status === 'Approved' || $transfer->tr_transfer_status === 'Received')
                                <span class="status-badge approved">
                                    <i class="fas fa-check" style="font-size: 11px;"></i>
                                    {{ $transfer->tr_transfer_status }}
                                </span>
                            @elseif($transfer->tr_transfer_status === 'Rejected')
                                <span class="status-badge rejected">
                                    <i class="fas fa-times" style="font-size: 11px;"></i>
                                    Rejected
                                </span>
                            @else
                                <span class="status-badge" style="background-color: #f3f4f6; color: #374151;">
                                    {{ $transfer->tr_transfer_status }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 14px 0; color: #6c757d; font-size: 14px; font-weight: 500;">
                            <i class="fas fa-calendar" style="margin-right: 8px; color: #adb5bd; width: 16px;"></i>
                            Date Requested
                        </td>
                        <td style="padding: 14px 0; color: #212529; font-size: 14px;">
                            {{ $transfer->tr_date_requested ? \Carbon\Carbon::parse($transfer->tr_date_requested)->format('d M Y') : '-' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Action Buttons Section -->
        <div style="background: #f8f9fa; padding: 20px 24px; border-top: 1px solid #e9ecef; display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('mainstore.inbox') }}" 
               style="padding: 10px 24px; border-radius: 6px; background: #6c757d; color: white; text-decoration: none; font-size: 14px; font-weight: 500; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>

            @if($transfer->tr_transfer_status === 'Pending')
                <form action="{{ route('mainstore.inbox.accept', $transfer->transfer_id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit"
                        style="padding: 10px 24px; border-radius: 6px; background: #28a745; color: white; border: none; font-size: 14px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
                        <i class="fas fa-check"></i>
                        Accept
                    </button>
                </form>

                <form action="{{ route('mainstore.inbox.reject', $transfer->transfer_id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit"
                        style="padding: 10px 24px; border-radius: 6px; background: #dc3545; color: white; border: none; font-size: 14px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
                        <i class="fas fa-times"></i>
                        Reject
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<style>
    /* Button Hover Effects */
    a[href*="inbox"]:hover {
        background: #5a6268;
        transform: translateY(-1px);
    }

    button[type="submit"]:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    form button[style*="#28a745"]:hover {
        background: #218838 !important;
    }

    form button[style*="#dc3545"]:hover {
        background: #c82333 !important;
    }
</style>
@endsection