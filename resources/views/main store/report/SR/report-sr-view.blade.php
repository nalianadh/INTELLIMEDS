@extends('layouts.main_store_layout')

@section('title', 'Stock Request Details')

@section('content')
<div class="main">
    <div class="header d-flex justify-content-between align-items-center">
        <div>
            <h2>Stock Request Details</h2>
            <p>Home / Report - Stock Request Details</p>
        </div>

        <!-- Buttons -->
        <div>
            <!-- Print Button -->
            <button onclick="window.print()" class="btn btn-outline-primary me-2">
                <i class="fas fa-print"></i> Print
            </button>

            <!-- Export to PDF -->
            <a href="#" 
               class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>

    <div class="card p-4">

        <!-- Basic Request Information -->
        <h4 class="mb-3">Request Information</h4>
        <table class="table table-bordered">
            <tr>
                <th style="width: 25%">Request ID</th>
                <td>{{ $stockRequest->request_id }}</td>
            </tr>
            <tr>
                <th>Requested By</th>
                <td>{{ $stockRequest->requestedBy->u_name ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <th>Date Requested</th>
                <td>{{ \Carbon\Carbon::parse($stockRequest->rq_date_requested)->format('d M Y') }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $stockRequest->rq_status ?? '-' }}</td>
            </tr>
            <tr>
                <th>Remarks</th>
                <td>{{ $stockRequest->rq_remarks ?? 'No remarks' }}</td>
            </tr>
        </table>

        <!-- Requested Item -->
        <h4 class="mt-4 mb-3">Requested Item</h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Item Name</th>
                        <th>Description/Brand</th>
                        <th>Quantity Requested</th>
                        <th>Quantity Approved</th>
                        <th>Unit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $stockRequest->item->i_name }}</td>
                        <td>{{ $stockRequest->item->i_description }}</td>
                        <td>{{ $stockRequest->rq_quantity_requested }}</td>
                        <td>{{ $stockRequest->rq_qty_approved ?? '-' }}</td>
                        <td>{{ $stockRequest->item->i_unit }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Back Button -->
        <div class="mt-4">
            <a href="{{ route('reports.stock-request.list') }}"style="display:inline-block; background:#0f3e59; color:#fff; 
                  padding:8px 18px; border-radius:6px; text-decoration:none; 
                  transition: background 0.2s;"class="btn btn-secondary">
                Back to List
            </a>
        </div>

    </div>
</div>
@endsection
