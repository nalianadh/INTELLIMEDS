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
<div style="max-width:970px; margin:auto; background:#fff; border-radius:16px; box-shadow:0 2px 8px rgba(0,0,0,0.04); padding:32px;">
    <div>
        <table class="table" style="width:100%; background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.05); overflow:hidden;">
            <thead style="background:#f5f5f5;">
                <tr>
                    <th>#</th>
                    <th>Message</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            {{-- Handle stock transfers --}}
                            @if($msg['type'] === 'transfer')
                                You received a new stock transfer from 
                                <strong>{{ $msg['from'] }}</strong> - 
                                Transfer ID: <strong>{{ $msg['id'] }}</strong>, 
                                <a href="{{ route('subdept.inbox.show', ['id' => $msg['id'], 'type' => 'transfer']) }}" 
                                   style="color:#226699; font-weight:600; text-decoration:none;">
                                    click here for more action
                                </a>
                            
                            {{-- Handle stock requests --}}
                            @elseif($msg['type'] === 'request')
                                Your request (ID: <strong>{{ $msg['id'] }}</strong>) 
                                for <strong>{{ $msg['qty_requested'] }}</strong> unit(s) of 
                                <strong>{{ \App\Models\Item::find($msg['item_id'])->i_name ?? 'Unknown Item' }}</strong> 
                                is currently <strong>{{ ucfirst($msg['status']) }}</strong>
                                @if($msg['qty_approved'])
                                    (Approved: {{ $msg['qty_approved'] }}).
                                @endif
                                <a href="{{ route('subdept.inbox.show', ['id' => $msg['id'], 'type' => 'request']) }}" 
                                   style="color:#226699; font-weight:600; text-decoration:none;">
                                    click here for more detail
                                </a>
                            @endif
                        </td>
                        <td>{{ $msg['date'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align:center; padding:20px; color:#666;">No messages found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
