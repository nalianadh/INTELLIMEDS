@extends('layouts.main_store_layout')

@section('title', 'Item Details')

@section('content')
<div class="main">
    <div class="header">
        <h2>Item Details</h2>
        <p>Item Register / View Item</p>
    </div>
    <div style="background:#fff; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.06); padding:32px 28px; max-width:600px; margin:0 auto;">
        <h3 style="margin-top:0; color:#20425c;">{{ $item->i_name }}</h3>
        <div style="margin-bottom:18px;">
            <strong>Stock ID:</strong> {{ $item->i_stockID }}<br>
            <strong>Description:</strong> {{ $item->i_description }}
        </div>
        <h4 style="margin-bottom:10px; color:#20425c;">Batch & Expiry Records</h4>
        <table style="width:100%; border-collapse:collapse; background:#f8fafc; border-radius:8px;">
            <thead>
                <tr style="background:#f6f9fc;">
                    <th style="padding:10px; border-bottom:1.5px solid #eaeaea;">Batch Number</th>
                    <th style="padding:10px; border-bottom:1.5px solid #eaeaea;">Expiry Date</th>
                    <th style="padding:10px; border-bottom:1.5px solid #eaeaea;">Quantity</th>
                </tr>
            </thead>
            <tbody>
                @forelse($batches as $batch)
                    <tr>
                        <td style="padding:8px; border-bottom:1px solid #f0f0f0;">{{ $batch->grn_itemBatchNumber ?? '-' }}</td>
                        <td style="padding:8px; border-bottom:1px solid #f0f0f0;">{{ $batch->grn_itemExpiredDate ?? '-' }}</td>
                        <td style="padding:8px; border-bottom:1px solid #f0f0f0;">{{ $batch->grn_quantity_received ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" style="padding:12px; text-align:center; color:#aaa;">No batch/expiry records found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top:24px;">
            <a href="{{ route('items.list') }}" style="color:#20425c; text-decoration:underline; font-weight:500;">&larr; Back to Item List</a>
        </div>
    </div>
</div>
@endsection
