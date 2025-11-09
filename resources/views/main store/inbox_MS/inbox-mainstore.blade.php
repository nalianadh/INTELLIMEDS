@extends('layouts.main_store_layout')

@section('title', 'Inbox')

@section('content')
{{-- ✅ SweetAlert2 Messages --}}
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

<link rel="stylesheet" href="{{ asset('resources/css/inbox.css') }}">
<style>
.gmail-inbox-table { border-collapse: separate; border-spacing: 0; width: 100%; font-size: 1rem; }
.gmail-inbox-table th { background: #f5f5f5; color: #20425c; font-weight: 600; border-bottom: 2px solid #e3f0fc; padding: 12px 16px; }
.gmail-inbox-table td { padding: 16px 18px; border-bottom: 1px solid #f5f5f5; vertical-align: middle; }
.gmail-inbox-table tr:hover { background: #f0f8ff; }
.gmail-avatar { width: 36px; height: 36px; border-radius: 50%; background: #e3f0fc; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; color: #228be6; font-size: 1.1rem; margin-right: 12px; }
.gmail-subject { font-weight: 600; color: #20425c; }
.gmail-date { color: #888; font-size: 0.95rem; }
.gmail-status-unread { color: #d6336c; font-weight: 600; background: #fff0f6; border-radius: 6px; padding: 4px 10px; }
.gmail-status-read { color: #228be6; font-weight: 600; background: #e3f0fc; border-radius: 6px; padding: 4px 10px; }
</style>

<div class="main">
<div class="header">
<h2>Inbox</h2>
<p>Home / Inbox</p>
</div>

<div>
    <div class="inbox-list" style="background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.04); padding:24px;">
        <h4 style="margin-bottom:20px;">Messages</h4>

        <table class="gmail-inbox-table">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                    <tr>
                        <td class="gmail-subject">
                            {{-- Handle stock transfers --}}
                            @if($msg['type'] === 'transfer')
                                You received a new stock transfer from 
                                <strong>{{ $msg['from'] }}</strong> - 
                                Transfer ID: <strong>{{ $msg['id'] }}</strong>, 
                                <a href="{{ route('mainstore.inbox.show', ['id' => $msg['id'], 'type' => 'transfer']) }}" 
                                    style="color:#226699; font-weight:600; text-decoration:none;">
                                    click here for more action
                                </a>

                            {{-- Handle stock requests --}}
                            @elseif($msg['type'] === 'request')
                                Your request (ID: <strong>{{ $msg['id'] }}</strong>) 
                                for <strong>{{ $msg['qty_requested'] }}</strong> unit(s) of 
                                {{-- ✅ CHANGE: Use the pre-fetched item_name from the controller (N+1 fix) --}}
                                <strong>{{ $msg['item_name'] ?? 'Unknown Item' }}</strong> 
                                is currently <strong>{{ ucfirst($msg['status']) }}</strong>
                                @if($msg['qty_approved'])
                                    (Approved: {{ $msg['qty_approved'] }}).
                                @endif
                                <a href="{{ route('mainstore.inbox.show', ['id' => $msg['id'], 'type' => 'request']) }}" 
                                    style="color:#226699; font-weight:600; text-decoration:none;">
                                    click here for more detail
                                </a>
                            @else
                                {{ $msg->subject ?? '-' }}
                            @endif
                        </td>
                        <td class="gmail-date">{{ $msg['date'] ?? $msg->created_at ?? '-' }}</td>
                        <td>
                            {{-- Status check remains the same, using 'read_status' populated by the controller --}}
                            @if(($msg['read_status'] ?? 'Unread') === 'Unread')
                                <span class="gmail-status-unread">Unread</span>
                            @else
                                <span class="gmail-status-read">Read</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center; padding:20px; color:#666;">No messages found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</div>
@endsection