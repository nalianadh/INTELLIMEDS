@extends('layouts.subdept-layout')

@section('title', 'Stock Transfer')
@section('page_title', 'Stock Transfer')
@section('page_icon', 'fas fa-exchange-alt')
@section('breadcrumb', 'Home / Inventory / Stock Transfer')

@section('content')
<form method="POST" action="{{ route('stock.transfer.subdept.store') }}">
    @csrf
    
    <!-- Transfer Details Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-info-circle"></i>
                Transfer Information
            </h3>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="tr_from_unit">
                    <i class="fas fa-hospital"></i>
                    From (Ward/Unit/Facilities)
                </label>
                <input type="text" name="tr_from_unit" id="tr_from_unit" placeholder="Enter source location" required>
            </div>
            <div class="form-group">
                <label for="tr_destination">
                    <i class="fas fa-map-marker-alt"></i>
                    To (Ward/Unit/Facilities)
                </label>
                <input type="text" name="tr_destination" id="tr_destination" placeholder="Enter destination location" required>
            </div>
        </div>
    </div>

    <!-- Items Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-boxes"></i>
                Items to Transfer
            </h3>
            <button type="button" id="add-item" class="btn btn-success">
                <i class="fas fa-plus"></i>
                Add Item
            </button>
        </div>

        <div id="items-section">
            <div class="item-row">
                <div class="form-group">
                    <label>Item</label>
                    <select name="items[0][item_id]" class="item-select" data-index="0" required>
                        <option value="">Select Item</option>
                        @foreach($items as $item)
                            <option value="{{ $item->item_id }}">{{ $item->i_stockID }} ({{ $item->i_name }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Batch & Expiry</label>
                    <select name="items[0][batch_expiry]" class="batch-expiry-select" required>
                        <option value="">Select Batch & Expiry</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="items[0][quantity]" placeholder="Qty" min="1" required>
                </div>
                <div class="form-group" style="justify-content: flex-end;">
                    <button type="button" class="btn btn-danger remove-item" onclick="removeItemRow(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Remarks Card -->
    <div class="card">
        <div class="form-group" style="margin-bottom: 0;">
            <label for="tr_remarks">
                <i class="fas fa-comment"></i>
                Remarks (Optional)
            </label>
            <textarea name="tr_remarks" id="tr_remarks" rows="3" placeholder="Add any additional notes or comments..."></textarea>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div style="display: flex; justify-content: flex-end; gap: 12px;">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-check"></i>
            Transfer Stock
        </button>
    </div>
</form>

@push('styles')
<style>
    .item-row {
        display: grid;
        grid-template-columns: 2fr 2fr 1fr auto;
        gap: 16px;
        margin-bottom: 16px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        align-items: end;
    }

    .item-row:hover {
        background: #f1f3f5;
        border-color: #dee2e6;
    }

    .item-row .form-group {
        margin-bottom: 0;
    }

    .item-row .remove-item {
        padding: 11px 16px;
        height: fit-content;
    }

    @media (max-width: 768px) {
        .item-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
// Prepare batch/expiry data for JS
const batchExpiryData = {
    @foreach($items as $item)
        {{ $item->item_id }}: [
            @foreach($item->receiveNotes as $note)
                { batch: "{{ $note->grn_itemBatchNumber }}", expiry: "{{ $note->grn_itemExpiredDate }}" },
            @endforeach
        ],
    @endforeach
};

function updateBatchExpirySelect(itemSelect, batchExpirySelect) {
    const itemId = itemSelect.value;
    batchExpirySelect.innerHTML = '<option value="">Select Batch & Expiry</option>';
    if (batchExpiryData[itemId]) {
        batchExpiryData[itemId].forEach(function(be) {
            if (be.batch && be.expiry) {
                batchExpirySelect.innerHTML += `<option value="${be.batch}|${be.expiry}">${be.batch} (Exp: ${be.expiry})</option>`;
            }
        });
    }
}

document.querySelectorAll('.item-select').forEach(function(select, idx) {
    select.addEventListener('change', function() {
        const batchExpirySelect = document.querySelectorAll('.batch-expiry-select')[idx];
        updateBatchExpirySelect(select, batchExpirySelect);
    });
});

let itemIndex = 1;
document.getElementById('add-item').addEventListener('click', function() {
    const section = document.getElementById('items-section');
    const newRow = document.createElement('div');
    newRow.className = 'item-row';
    newRow.innerHTML = `
        <div class="form-group">
            <label>Item</label>
            <select name="items[${itemIndex}][item_id]" class="item-select" data-index="${itemIndex}" required>
                <option value="">Select Item</option>
                @foreach($items as $item)
                    <option value="{{ $item->item_id }}">{{ $item->i_stockID }} ({{ $item->i_name }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Batch & Expiry</label>
            <select name="items[${itemIndex}][batch_expiry]" class="batch-expiry-select" required>
                <option value="">Select Batch & Expiry</option>
            </select>
        </div>
        <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="items[${itemIndex}][quantity]" placeholder="Qty" min="1" required>
        </div>
        <div class="form-group" style="justify-content: flex-end;">
            <button type="button" class="btn btn-danger remove-item" onclick="removeItemRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    section.appendChild(newRow);
    
    // Add event listener for new item select
    const itemSelect = newRow.querySelector('.item-select');
    const batchExpirySelect = newRow.querySelector('.batch-expiry-select');
    itemSelect.addEventListener('change', function() {
        updateBatchExpirySelect(itemSelect, batchExpirySelect);
    });
    itemIndex++;
});

function removeItemRow(btn) {
    const row = btn.closest('.item-row');
    if (document.querySelectorAll('.item-row').length > 1) {
        row.remove();
    } else {
        alert('At least one item is required');
    }
}
</script>
@endpush
@endsection