@extends('layouts.main_store_layout')

@section('title', 'Inbox')

@section('content')
<div style="max-width:970px; margin:auto; background:#fff; border-radius:16px; box-shadow:0 2px 8px rgba(0,0,0,0.04); padding:32px;">
    <div style="max-width:965px; margin:auto; background:#fff; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.1); padding:24px; text-align:left;">
        {{-- view-inbox-transfer.blade.php --}}
        <table class="table" style="width:100%;">
            <tr>
                <th style="width:30%;">Request ID</th>
                <td>: {{ $requestData->request_id }}</td>
            </tr>
            <tr>
                <th>Requested By</th>
                <td>: {{ $requestData->user->u_name ?? 'Unknown User' }}</td>
            </tr>
            <tr>
                <th>Item</th>
                <td>: {{ $requestData->item->i_name ?? 'Unknown Item' }}</td>
            </tr>
            <tr>
                <th>Quantity Requested</th>
                <td>: {{ $requestData->rq_quantity_requested }}</td>
            </tr>
            <tr>
                <th>Quantity Approved</th>
                <td>: {{ $requestData->rq_qty_approved }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>: {{ $requestData->rq_status }}</td>
            </tr>
            <tr>
                <th>Date Requested</th>
                <td>: {{ $requestData->rq_date_requested }}</td>
            </tr>
            <tr>
                <th>Date Approved</th>
                <td>: {{ $requestData->rq_date_approved }}</td>
            </tr>
            <tr>
                <th>Approved By</th>
                <td>: {{ $requestData->approvedByUser->u_name ?? 'Not Approved' }}</td>
            </tr>
            <tr>
                <th>Remarks</th>
                <td>: {{ $requestData->rq_remarks}}</td>
            </tr>
        </table>
            <div style="margin-top:20px; display:flex; gap:10px;">
            <a href="{{ route('subdept.inbox') }}" 
            style="padding:8px 16px; border-radius:6px; background:#ccc; color:#000; text-decoration:none; font-size:0.9rem;">
                ⬅ Back
            </a>
            </div>
    </div>
</div>

@endsection
