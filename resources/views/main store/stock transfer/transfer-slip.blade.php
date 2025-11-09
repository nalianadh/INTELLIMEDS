@extends('layouts.main_store_layout')

@section('title', 'Stock Transfer Slip')

@section('content')
<div class="main">
    <div class="header">
        <h2>Stock Transfer Slip</h2>
        <p>Home / Stock Transfer - {{ $transfer->tr_date_requested }}</p>
    </div>

    <div class="transfer-info" style="margin-bottom:24px;">
        <table style="width:auto;">
            <tr><th style="text-align:left;">Transfer Date:</th><td>{{ $transfer->tr_date_requested ?? '-' }}</td></tr>
            <tr><th style="text-align:left;">From Unit:</th><td>{{ $transfer->tr_from_unit ?? '-' }}</td></tr>
            <tr><th style="text-align:left;">Destination:</th><td>{{ $transfer->tr_destination ?? '-' }}</td></tr>
            <tr><th style="text-align:left;">Requested By:</th><td>{{ $transfer->tr_requested_by ?? '-' }}</td></tr>
        </table>
    </div>

    <div class="transfer-items">
        <h3>Transferred Items</h3>
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f5f5f5;">
                    <th style="border:1px solid #ddd;padding:8px;">#</th>
                    <th style="border:1px solid #ddd;padding:8px;">Item Name</th>
                    <th style="border:1px solid #ddd;padding:8px;">Quantity Transferred</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ $transfer->item->i_name ?? 'N/A' }}</td>
                    <td>{{ abs($transfer->tr_quantity) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- ✅ Back Button at the bottom --}}
    <div style="margin-top:30px; text-align:center;">
        <a href="{{ route('stock.transfer.list') }}" 
           style="display:inline-block; background:#6c757d; color:#fff; 
                  padding:8px 18px; border-radius:6px; text-decoration:none; 
                  transition: background 0.2s;">
            ← Back to Transfer List
        </a>
    </div>
</div>
@endsection
