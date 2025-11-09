@extends('layouts.main_store_layout')

@section('title', 'Stock Transfer List')

@section('content')
<div class="main">
    <div class="header">
        <h2>Stock Transfer List</h2>
        <p>Home / Stock Transfer - List</p>
    </div>
    <div>
        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:16px; color: #155724; background: #d4edda; border: 1px solid #c3e6cb; padding: 10px 20px; border-radius: 4px;">
                {{ session('success') }}
            </div>
        @endif
        <table class="table" style="width:100%; background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
            <thead style="background:#f5f5f5;">
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Actions</th> <!-- ✅ Added column -->
                </tr>
            </thead>
            <tbody>
                @forelse($transfers as $transfer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $transfer->tr_date_requested }}</td>
                        <td>{{ $transfer->tr_from_unit }}</td>
                        <td>{{ $transfer->tr_destination }}</td>
                        <td>
                            @if($transfer->tr_quantity > 0)
                                Transfer In 
                            @else
                                Transfer Out
                            @endif
                        </td>
                        <td>{{ $transfer->tr_remarks }}</td>
                        <td>
                            <a href="{{ route('transferSlip.show', $transfer->transfer_id) }}" 
                               class="btn btn-sm btn-info" 
                               style="background:#226699; color:#fff; border:none; border-radius:6px; padding:4px 10px;">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="11" style="text-align:center;">No transfers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
