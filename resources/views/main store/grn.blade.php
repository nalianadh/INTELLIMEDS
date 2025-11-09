@extends('layouts.main_store_layout')

@section('title', 'Goods Receive Note')

@section('content')
<div class="main">
    <div class="header">
        <h2>Stock Receive</h2>
        <p>Home / Stock Receive - GRN</p>
    </div>
    <div class="grn-info" style="margin-bottom:24px;">
        <table style="width:auto;">
            <tr><th style="text-align:left;">GRN Date:</th><td>{{ $receiveNotes->first()->grn_date_received ?? '-' }}</td></tr>
            <tr><th style="text-align:left;">Supplier:</th><td>{{ $receiveNotes->first()->grn_supplier ?? '-' }}</td></tr>
            <tr><th style="text-align:left;">PO Number:</th><td>{{ $receiveNotes->first()->grn_po_number ?? '-' }}</td></tr>
            <tr><th style="text-align:left;">Received By:</th><td>{{ $receiveNotes->first()->grn_received_by ?? '-' }}</td></tr>
        </table>
    </div>
    <div class="grn-items">
        <h3>Received Items</h3>
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f5f5f5;">
                    <th style="border:1px solid #ddd;padding:8px;">#</th>
                    <th style="border:1px solid #ddd;padding:8px;">Item Name</th>
                    <th style="border:1px solid #ddd;padding:8px;">Quantity Received</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receiveNotes as $i => $note)
                    <tr>
                        <td style="border:1px solid #ddd;padding:8px;">{{ $i + 1 }}</td>
                        <td style="border:1px solid #ddd;padding:8px;">{{ $note->item->i_name ?? '-' }}</td>
                        <td style="border:1px solid #ddd;padding:8px;">{{ $note->grn_quantity_received }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="grn-remarks" style="margin-top:24px;">
        <strong>Remarks:</strong><br>
        {{ $receiveNotes->first()->grn_remarks ?? 'None' }}
    </div>
    <div style="margin-top:32px;">
        <a href="{{ route('stock.receive.grnlist') }}" style="display:inline-block;padding:8px 20px;background:#20425c;color:#fff;text-decoration:none;border-radius:4px;">&larr; Back to GRN List</a>
    </div>
</div>
@endsection
