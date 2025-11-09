@extends('layouts.main_store_layout')

@section('title', 'Stock Request - View Request')

@section('content')

{{-- SweetAlert2 Success Message --}}
@if(session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif

<div class="container mt-4" style="max-width:800px; margin:auto;">
    <h2 class="mb-4">Stock Request Details</h2>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Request ID:</strong> {{ $stockRequest->request_id }}</p>
            <p><strong>Requested By:</strong> {{ $stockRequest->requestedBy->u_name ?? 'Unknown' }}</p>
            <p><strong>Date Requested:</strong> {{ \Carbon\Carbon::parse($stockRequest->rq_date_requested)->format('d M Y') }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><strong>Requested Item</strong></div>
        <div class="card-body">
            <p><strong>Item Name:</strong> {{ $stockRequest->item->i_name}}</p>
            <p><strong>Quantity Requested:</strong> {{ $stockRequest->rq_quantity_requested }}</p>
            <p><strong>Quantity Available:</strong> {{ $stockRequest->item->i_quantity_in_stock }}</p>
        </div>
    </div>

    <form action="{{ route('stock-request.update', $stockRequest->request_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card mb-4">
            <div class="card-header"><strong>Approve Request</strong></div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="rq_qty_approved" class="form-label">Quantity Approved</label>
                    <input type="number" name="rq_qty_approved" id="rq_qty_approved"
                        class="form-control" value="{{ old('rq_qty_approved', $stockRequest->rq_qty_approved) }}" required>
                </div>

                <div class="mb-3">
                    <label for="rq_remarks" class="form-label">Remarks</label>
                    <textarea name="rq_remarks" id="rq_remarks" class="form-control" rows="3">{{ old('rq_remarks', $stockRequest->rq_remarks) }}</textarea>
                </div>

                <input type="hidden" name="rq_date_approved" value="{{ now() }}">
                <input type="hidden" name="rq_approved_by" value="{{ auth()->id() }}">

                <button type="submit" class="btn btn-success">Submit Approval</button>
            </div>
        </div>
    </form>

    {{-- Back Button at Bottom --}}
    <div class="mt-3">
        <a href="{{ route('stock.request.list') }}" class="btn btn-secondary">
            ← Back to Stock Request List
        </a>
    </div>
</div>
@endsection
