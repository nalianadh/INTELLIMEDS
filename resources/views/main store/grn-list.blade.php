@extends('layouts.main_store_layout')

@section('title', 'GRN List')

@section('content')
<div class="main">
    <div class="header">
        <h2>Stock Receive</h2>
        <p>Home / Stock Receive - GRN List</p>
    </div>
    <div class="grn-list-table" style="margin-top:24px;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f5f5f5;">
                    <th style="border:1px solid #ddd;padding:8px;">#</th>
                    <th style="border:1px solid #ddd;padding:8px;">GRN Date</th>
                    <th style="border:1px solid #ddd;padding:8px;">Supplier</th>
                    <th style="border:1px solid #ddd;padding:8px;">PO Number</th>
                    <th style="border:1px solid #ddd;padding:8px;">Received By</th>
                    <th style="border:1px solid #ddd;padding:8px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($grnGroups as $group)
                    <tr>
                        <td style="border:1px solid #ddd;padding:8px;">{{ $loop->iteration }}</td>
                        <td style="border:1px solid #ddd;padding:8px;">{{ $group->first()->grn_date_received ?? '-' }}</td>
                        <td style="border:1px solid #ddd;padding:8px;">{{ $group->first()->grn_supplier ?? '-' }}</td>
                        <td style="border:1px solid #ddd;padding:8px;">{{ $group->first()->grn_po_number ?? '-' }}</td>
                        <td style="border:1px solid #ddd;padding:8px;">{{ $group->first()->grn_received_by ?? '-' }}</td>
                        <td style="border:1px solid #ddd;padding:8px;">
                            <a href="{{ route('stock.receive.grn', ['po' => $group->first()->grn_po_number, 'supplier' => $group->first()->grn_supplier]) }}" style="color:#20425c;text-decoration:underline;">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;">No GRNs found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
