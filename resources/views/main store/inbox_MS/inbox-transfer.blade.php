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

<div style="max-width:970px; margin:auto; background:#fff; border-radius:16px; box-shadow:0 2px 8px rgba(0,0,0,0.04); padding:32px;">
        <div class="header">
            <h2>Inbox</h2>
            <p>Home / Inbox</p>
        </div>
    <div style="max-width:965px; margin:auto; background:#fff; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.1); padding:24px; text-align:left;">
        {{-- view-inbox-transfer.blade.php --}}
        <table class="table" style="width:100%;">
            <tr>
                <th style="width:30%;">Transfer ID</th>
                <td>: {{ $transfer->transfer_id }}</td>
            </tr>
            <tr>
                <th>From Unit</th>
                <td>: {{ $transfer->tr_from_unit }}</td>
            </tr>
            <tr>
                <th>Item</th>
                <td>: {{ $transfer->item->i_name ?? 'Unknown Item' }}</td>
            </tr>
            <tr>
                <th>Quantity</th>
                <td>: {{ abs($transfer->tr_quantity) }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>: {{ $transfer->tr_transfer_status }}</td>
            </tr>
            <tr>
                <th>Date Requested</th>
                <td>: {{ $transfer->tr_date_requested }}</td>
            </tr>
        </table>

        {{-- Buttons (only for Pending transfers) --}}
        <div style="margin-top:20px; display:flex; gap:10px;">
            <a href="{{ route('mainstore.inbox') }}" 
            style="padding:8px 16px; border-radius:6px; background:#ccc; color:#000; text-decoration:none; font-size:0.9rem;">
                ⬅ Back
            </a>

            @if($transfer->tr_transfer_status === 'Pending')
                <form action="{{ route('mainstore.inbox.accept', $transfer->transfer_id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit"
                        style="padding:8px 16px; border-radius:6px; background:#28a745; color:#fff; border:none; font-size:0.9rem; cursor:pointer;">
                        ✅ Accept
                    </button>
                </form>

                <form action="{{ route('mainstore.inbox.reject', $transfer->transfer_id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit"
                        style="padding:8px 16px; border-radius:6px; background:#dc3545; color:#fff; border:none; font-size:0.9rem; cursor:pointer;">
                        ❌ Reject
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
