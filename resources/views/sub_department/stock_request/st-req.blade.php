@extends('layouts.subdept-layout')

@section('title', 'Stock Request')
@section('page_title', 'Stock Request')
@section('page_icon', 'fas fa-clipboard-list')
@section('breadcrumb', 'Home / Inventory / Stock Request')

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

<div class="form-container">
    <form action="{{ route('stock-request.store') }}" method="POST" class="stock-request-form">
        @csrf
        
        <!-- Department Selection Card -->
        <div class="card modern-card">
            <div class="card-header modern-header">
                <div class="header-content">
                    <div class="header-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <h3 class="card-title">Request Details</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group modern-form-group">
                    <label for="department" class="modern-label">
                        <i class="fas fa-hospital-user"></i>
                        Select Department
                    </label>
                    <select class="form-control modern-select" name="department" id="department" required>
                        <option value="">Choose a department...</option>
                        <option value="Main Store" selected>Main Store</option>
                        <option value="ICU">ICU (Intensive Care Unit)</option>
                        <option value="Ward">Ward</option>
                        <option value="ER">ER (Emergency Room)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Items Card -->
        <div class="card modern-card">
            <div class="card-header modern-header">
                <div class="header-content">
                    <div class="header-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h3 class="card-title">Request Items</h3>
                </div>
                <button type="button" id="add-row" class="btn btn-success modern-btn-add">
                    <i class="fas fa-plus"></i>
                    <span>Add Item</span>
                </button>
            </div>

            <div class="table-responsive modern-table-wrapper">
                <table class="request-table modern-table">
                    <thead>
                        <tr>
                            <th style="width: 50%;">
                                <i class="fas fa-box"></i>
                                Item Name
                            </th>
                            <th style="width: 25%;">
                                <i class="fas fa-hashtag"></i>
                                Quantity
                            </th>
                            <th style="width: 25%; text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="item-rows">
                        <tr class="item-row">
                            <td>
                                <select name="items[0][item_id]" class="form-control modern-select table-select" required>
                                    <option value="">Select Item</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->item_id }}" {{ isset($prefilledItem) && $prefilledItem->item_id == $item->item_id ? 'selected' : '' }}>{{ $item->i_name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="items[0][quantity]" min="1" class="form-control modern-input" placeholder="Qty" required>
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-danger btn-sm modern-btn-remove remove-row">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="form-actions">
            <a href="{{ route('subdept.request') }}" class="btn btn-secondary modern-btn-secondary">
                <i class="fas fa-times"></i>
                <span>Cancel</span>
            </a>
            <button type="submit" class="btn btn-primary modern-btn-primary">
                <i class="fas fa-paper-plane"></i>
                <span>Submit Request</span>
            </button>
        </div>
    </form>
</div>

<!-- Template for dynamic rows -->
<script type="text/template" id="row-template">
    <tr class="item-row">
        <td>
            <select name="items[__INDEX__][item_id]" class="form-control modern-select table-select" required>
                <option value="">Select Item</option>
                @foreach($items as $item)
                    <option value="{{ $item->item_id }}">{{ $item->i_name }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" name="items[__INDEX__][quantity]" min="1" class="form-control modern-input" placeholder="Qty" required>
        </td>
        <td style="text-align: center;">
            <button type="button" class="btn btn-danger btn-sm modern-btn-remove remove-row">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
</script>

@push('styles')
<style>
    .form-container {
        width: 100%;
        margin: 0;
        padding: 0;
    }

    .stock-request-form {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* Modern Card Styling */
    .modern-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border: 1px solid #e8ecef;
        overflow: hidden;
        transition: box-shadow 0.3s ease;
    }

    .modern-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    }

    .modern-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-bottom: 2px solid #e8ecef;
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-content {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .header-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #1e5f74 0%, #0f3e59 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        box-shadow: 0 4px 12px rgba(15, 62, 89, 0.2);
    }

    .card-title {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #0f3e59;
        letter-spacing: -0.3px;
    }

    .card-body {
        padding: 28px 24px;
    }

    /* Modern Form Group */
    .modern-form-group {
        margin-bottom: 0;
    }

    .modern-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
        letter-spacing: 0.2px;
    }

    .modern-label i {
        color: #1e5f74;
        font-size: 15px;
    }

    /* Modern Select & Input */
    .modern-select,
    .modern-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e8ecef;
        border-radius: 8px;
        font-size: 15px;
        color: #2c3e50;
        background: #ffffff;
        transition: all 0.2s ease;
        outline: none;
    }

    .modern-select:focus,
    .modern-input:focus {
        border-color: #1e5f74;
        box-shadow: 0 0 0 4px rgba(30, 95, 116, 0.1);
    }

    .modern-select:hover,
    .modern-input:hover {
        border-color: #c5d0d8;
    }

    .table-select {
        padding: 10px 14px;
        font-size: 14px;
    }

    /* Modern Table */
    .modern-table-wrapper {
        overflow-x: auto;
        margin: 0;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .modern-table thead {
        background: linear-gradient(135deg, #f8f9fa 0%, #f0f3f5 100%);
    }

    .modern-table thead th {
        padding: 16px 20px;
        font-size: 14px;
        font-weight: 700;
        color: #0f3e59;
        text-align: left;
        border-bottom: 2px solid #e8ecef;
        letter-spacing: 0.3px;
    }

    .modern-table thead th i {
        margin-right: 8px;
        color: #1e5f74;
        font-size: 13px;
    }

    .modern-table tbody tr {
        transition: all 0.2s ease;
    }

    .modern-table tbody tr:hover {
        background: #f8fafb;
    }

    .modern-table tbody td {
        padding: 16px 20px;
        border-bottom: 1px solid #f0f3f5;
        vertical-align: middle;
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    .item-row {
        animation: slideIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Modern Buttons */
    .modern-btn-add {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25);
    }

    .modern-btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }

    .modern-btn-add:active {
        transform: translateY(0);
    }

    .modern-btn-remove {
        padding: 8px 14px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: none;
        border-radius: 6px;
        color: white;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.25);
    }

    .modern-btn-remove:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.35);
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    }

    .modern-btn-remove:active {
        transform: translateY(0);
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 8px;
    }

    .modern-btn-secondary,
    .modern-btn-primary {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .modern-btn-secondary {
        background: #f1f3f5;
        color: #5a6c7d;
        border: 1px solid #e8ecef;
    }

    .modern-btn-secondary:hover {
        background: #e9ecef;
        color: #2c3e50;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .modern-btn-primary {
        background: linear-gradient(135deg, #1e5f74 0%, #0f3e59 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(15, 62, 89, 0.25);
    }

    .modern-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(15, 62, 89, 0.35);
        background: linear-gradient(135deg, #0f3e59 0%, #0a2e44 100%);
    }

    .modern-btn-secondary:active,
    .modern-btn-primary:active {
        transform: translateY(0);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-container {
            padding: 0 12px;
        }

        .modern-header {
            flex-direction: column;
            gap: 16px;
            align-items: flex-start;
        }

        .modern-btn-add {
            width: 100%;
            justify-content: center;
        }

        .modern-table {
            font-size: 13px;
        }

        .modern-table thead th,
        .modern-table tbody td {
            padding: 12px 14px;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .modern-btn-secondary,
        .modern-btn-primary {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    let rowIdx = 1;
    
    document.getElementById('add-row').onclick = function() {
        const tbody = document.getElementById('item-rows');
        const template = document.getElementById('row-template').innerHTML;
        const newRow = template.replace(/__INDEX__/g, rowIdx);
        tbody.insertAdjacentHTML('beforeend', newRow);
        rowIdx++;
    };

    document.getElementById('item-rows').addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-row')) {
            const row = e.target.closest('tr');
            const tbody = document.getElementById('item-rows');
            
            // Prevent removing the last row
            if (tbody.querySelectorAll('tr').length > 1) {
                row.remove();
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cannot Remove',
                    text: 'At least one item is required for the request.',
                    confirmButtonColor: '#0f3e59'
                });
            }
        }
    });

    // Also handle clicks on the icon inside the button
    document.getElementById('item-rows').addEventListener('click', function(e) {
        if (e.target && e.target.closest('.remove-row')) {
            const button = e.target.closest('.remove-row');
            button.click();
        }
    });
</script>
@endpush
@endsection