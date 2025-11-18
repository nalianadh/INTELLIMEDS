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
                confirmButtonColor: '#0f3e59',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif

<div class="main">
    <div class="header">
        <h2>Stock Request Details</h2>
        <p>Home / Stock Request / View Request</p>
    </div>

    <!-- Request Information Section -->
    <div class="info-card">
        <div class="info-card-header">
            <h3><i class="fas fa-info-circle"></i> Request Information</h3>
        </div>
        <div class="info-card-body">
            <div class="info-grid">
                <div class="info-item">
                    <label class="info-label"><i class="fas fa-hashtag"></i> Request ID</label>
                    <span class="badge-primary">REQ-{{ str_pad($stockRequest->request_id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="info-item">
                    <label class="info-label"><i class="fas fa-user"></i> Requested By</label>
                    <p class="info-value">{{ $stockRequest->requestedBy->u_name ?? 'Unknown' }}</p>
                </div>
                <div class="info-item">
                    <label class="info-label"><i class="fas fa-calendar"></i> Date Requested</label>
                    <p class="info-value">{{ \Carbon\Carbon::parse($stockRequest->rq_date_requested)->format('d M Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Item Details Section -->
    <div class="info-card">
        <div class="info-card-header">
            <h3><i class="fas fa-box"></i> Requested Item</h3>
        </div>
        <div class="info-card-body">
            <div class="info-grid">
                <div class="info-item">
                    <label class="info-label"><i class="fas fa-tag"></i> Item Name</label>
                    <p class="info-value item-name">{{ $stockRequest->item->i_name }}</p>
                </div>
                <div class="info-item">
                    <label class="info-label"><i class="fas fa-sort-amount-up"></i> Quantity Requested</label>
                    <span class="quantity-display">{{ $stockRequest->rq_quantity_requested }}</span>
                </div>
                <div class="info-item">
                    <label class="info-label"><i class="fas fa-warehouse"></i> Quantity Available</label>
                    <span class="status-badge {{ $stockRequest->item->quantity_in_stock >= $stockRequest->rq_quantity_requested ? 'approved' : 'rejected' }}">
                        {{ $stockRequest->item->quantity_in_stock }} units
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Approval Form Section -->
    <form action="{{ route('stock-request.update', $stockRequest->request_id) }}" method="POST" enctype="multipart/form-data">

        @csrf
        @method('PUT')


        <div class="form-section-header">
            <h3><i class="fas fa-check-circle"></i> Approve Request</h3>
        </div>

        <!-- Quantity Approved -->
        <div class="form-group">
            <label for="rq_qty_approved"><i class="fas fa-check-double"></i> Quantity Approved</label>
            <input type="number" 
                   name="rq_qty_approved" 
                   id="rq_qty_approved"
                   value="{{ old('rq_qty_approved', $stockRequest->rq_qty_approved) }}" 
                   placeholder="Enter approved quantity" 
                   required>
        </div>

        <!-- Batch Selection Table -->
        <div class="form-group">
            <label><i class="fas fa-layer-group"></i> Select Batches to Supply</label>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Batch Number</th>
                            <th>Expiry Date</th>
                            <th>Available Quantity</th>
                            <th>Quantity to Supply</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stockRequest->item->receiveNotes->where('grn_available_qty', '>', 0) as $grn)
                            <tr>
                                <td class="batch-number">{{ $grn->grn_itemBatchNumber }}</td>
                                <td>{{ \Carbon\Carbon::parse($grn->grn_itemExpiredDate)->format('d M Y') }}</td>
                                <td><span class="available-qty-badge">{{ $grn->grn_available_qty }}</span></td>
                                <td>
                                    <input type="number" 
                                           name="batches[{{ $grn->grn_id }}]" 
                                           min="0" 
                                           max="{{ $grn->grn_available_qty }}" 
                                           class="batch-input"
                                           placeholder="Enter quantity">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Remarks -->
        <div class="form-group">
            <label for="rq_remarks"><i class="fas fa-comment"></i> Remarks (Optional)</label>
            <textarea name="rq_remarks" 
                      id="rq_remarks" 
                      rows="4" 
                      placeholder="Add any notes or special instructions...">{{ old('rq_remarks', $stockRequest->rq_remarks) }}</textarea>
        </div>

        <input type="hidden" name="rq_date_approved" value="{{ now() }}">
        <input type="hidden" name="rq_approved_by" value="{{ auth()->id() }}">

        <!-- Action Buttons -->
        <div class="form-actions">
            <a href="{{ route('stock.request.list') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            <button type="submit">
                <i class="fas fa-check"></i> Approve Request
            </button>
        </div>
    </form>
</div>

<style>
    /* Info Cards - Consistent with Layout */
    .info-card {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #e9ecef;
        margin-bottom: 24px;
        overflow: hidden;
    }

    .info-card-header {
        background-color: #f8f9fa;
        padding: 16px 24px;
        border-bottom: 1px solid #e9ecef;
    }

    .info-card-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        color: #0f3e59;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-card-body {
        padding: 24px;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 24px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .info-label {
        font-size: 13px;
        color: #6c757d;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 0;
    }

    .info-value {
        font-size: 15px;
        color: #212529;
        font-weight: 500;
        margin: 0;
    }

    .item-name {
        color: #0f3e59;
        font-weight: 600;
    }

    /* Badges - Consistent with Layout */
    .badge-primary {
        display: inline-block;
        padding: 6px 14px;
        background-color: #0f3e59;
        color: white;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }

    .quantity-display {
        display: inline-block;
        padding: 6px 14px;
        background-color: #f8f9fa;
        color: #212529;
        border-radius: 6px;
        font-size: 15px;
        font-weight: 600;
        border: 1px solid #e9ecef;
    }

    /* Form Section Header */
    .form-section-header {
        background: #ffffff;
        padding: 20px 32px;
        border-radius: 12px 12px 0 0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #e9ecef;
        border-bottom: none;
    }

    .form-section-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #0f3e59;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Form Styling - Inherits from Layout */
    form {
        border-radius: 0 0 12px 12px;
        margin-top: 0;
    }

    /* Table Wrapper */
    .table-wrapper {
        overflow-x: auto;
        border-radius: 8px;
        margin-top: 8px;
    }

    /* Batch Number Styling */
    .batch-number {
        font-weight: 600;
        color: #0f3e59;
    }

    /* Available Quantity Badge */
    .available-qty-badge {
        display: inline-block;
        padding: 4px 12px;
        background-color: #d1e7dd;
        color: #0f5132;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    /* Batch Input - Consistent with Layout */
    .batch-input {
        padding: 8px 12px;
        font-size: 14px;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 24px;
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 28px;
        background-color: #6c757d;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(108, 117, 125, 0.2);
    }

    /* Override Submit Button Color to Match Layout */
    button[type="submit"] {
        background: #0f3e59;
    }

    button[type="submit"]:hover {
        background: #1a5270;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(15, 62, 89, 0.2);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-secondary,
        button[type="submit"] {
            width: 100%;
            justify-content: center;
        }

        .table-wrapper {
            font-size: 13px;
        }

        table th,
        table td {
            padding: 10px 12px;
        }
    }
</style>
@endsection