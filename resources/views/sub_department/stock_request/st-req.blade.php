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

<div style="max-width: 900px; margin: 0 auto;">
    <form action="{{ route('stock-request.store') }}" method="POST">
        @csrf
        
        <!-- Department Selection Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-building"></i>
                    Request Details
                </h3>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label for="department">
                    <i class="fas fa-hospital-user"></i>
                    Select Department
                </label>
                <select class="form-control" name="department" id="department" required>
                    <option value="">Choose a department...</option>
                    <option value="Main Store">Main Store</option>
                    <option value="ICU">ICU (Intensive Care Unit)</option>
                    <option value="Ward">Ward</option>
                    <option value="ER">ER (Emergency Room)</option>
                </select>
            </div>
        </div>

        <!-- Items Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-boxes"></i>
                    Request Items
                </h3>
                <button type="button" id="add-row" class="btn btn-success">
                    <i class="fas fa-plus"></i>
                    Add Item
                </button>
            </div>

            <div class="table-responsive">
                <table class="request-table">
                    <thead>
                        <tr>
                            <th style="width: 50%;">
                                <i class="fas fa-box" style="margin-right: 6px;"></i>
                                Item Name
                            </th>
                            <th style="width: 25%;">
                                <i class="fas fa-hashtag" style="margin-right: 6px;"></i>
                                Quantity
                            </th>
                            <th style="width: 25%; text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="item-rows">
                        <tr class="item-row">
                            <td>
                                <select name="items[0][item_id]" class="form-control" required>
                                    <option value="">Select Item</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->item_id }}">{{ $item->i_name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="items[0][quantity]" min="1" class="form-control" placeholder="Qty" required>
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-danger btn-sm remove-row">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; justify-content: flex-end; gap: 12px;">
            <a href="{{ route('subdept.request') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i>
                Submit Request
            </button>
        </div>
    </form>
</div>

<!-- Template for dynamic rows -->
<script type="text/template" id="row-template">
<tr class="item-row">
    <td>
        <select name="items[__INDEX__][item_id]" class="form-control" required>
            <option value="">Select Item</option>
            @foreach($items as $item)
                <option value="{{ $item->item_id }}">{{ $item->i_name }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="number" name="items[__INDEX__][quantity]" min="1" class="form-control" placeholder="Qty" required>
    </td>
    <td style="text-align: center;">
        <button type="button" class="btn btn-danger btn-sm remove-row">
            <i class="fas fa-trash"></i>
        </button>
    </td>
</tr>
</script>

@push('styles')
<style>
    .table-responsive {
        overflow-x: auto;
        margin: 0;
    }

    .request-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .request-table thead {
        background: #f8f9fa;
    }

    .request-table thead th {
        padding: 14px 16px;
        font-size: 13px;
        font-weight: 600;
        color: #0f3e59;
        text-align: left;
        border-bottom: 2px solid #e9ecef;
    }

    .request-table tbody tr {
        transition: background-color 0.2s;
    }

    .request-table tbody tr:hover {
        background: #f8f9fa;
    }

    .request-table tbody td {
        padding: 12px 16px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .request-table tbody tr:last-child td {
        border-bottom: none;
    }

    .item-row {
        animation: slideIn 0.3s ease;
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

    .btn-sm {
        padding: 8px 12px;
        font-size: 13px;
    }

    /* Ensure form controls in table look good */
    .request-table .form-control {
        margin-bottom: 0;
    }

    @media (max-width: 768px) {
        .request-table {
            font-size: 13px;
        }

        .request-table thead th,
        .request-table tbody td {
            padding: 10px 12px;
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