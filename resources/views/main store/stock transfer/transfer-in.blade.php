@extends('layouts.main_store_layout')

@section('title', 'Stock Transfer')

@section('content')
<div class="main">
    <div class="header">
        <h2>Stock Transfer</h2>
        <p>Home / Stock Transfer - New Transfer In</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('stock.transfer.in.store') }}">
        @csrf
        
        <!-- Transfer Details Section -->
        <div style="background: #f8f9fa; padding: 16px 24px; border-radius: 12px; margin-bottom: 24px; border: 1px solid #e9ecef;">
            <h3 style="margin: 0 0 16px 0; font-size: 16px; font-weight: 600; color: #0f3e59; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-exchange-alt"></i>
                Transfer Information
            </h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="tr_from_unit">
                        <i class="fas fa-hospital" style="margin-right: 6px; color: #6c757d;"></i>
                        From (Ward/Unit/Facilities)
                    </label>
                    <input type="text" name="tr_from_unit" id="tr_from_unit" placeholder="Enter source location" required>
                </div>
                <div class="form-group">
                    <label for="tr_destination">
                        <i class="fas fa-map-marker-alt" style="margin-right: 6px; color: #6c757d;"></i>
                        To (Ward/Unit/Facilities)
                    </label>
                    <input type="text" name="tr_destination" id="tr_destination" placeholder="Enter destination location" required>
                </div>
            </div>
        </div>

        <!-- Items Section -->
        <div style="background: #ffffff; padding: 24px; border-radius: 12px; margin-bottom: 24px; border: 1px solid #e9ecef;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #0f3e59; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-boxes"></i>
                    Items to Transfer
                </h3>
                <button type="button" id="add-item" style="background: #28a745; color: white; border: none; border-radius: 6px; padding: 8px 16px; font-size: 13px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;">
                    <i class="fas fa-plus"></i>
                    Add Item
                </button>
            </div>

            <div id="items-section">
                <div class="item-row" style="display: grid; grid-template-columns: 2fr 2fr 1fr auto; gap: 12px; margin-bottom: 12px; padding: 16px; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 12px; margin-bottom: 6px;">Item</label>
                        <select name="items[0][item_id]" class="item-select" data-index="0" required>
                            <option value="">Select Item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->item_id }}">{{ $item->i_stockID }} ({{ $item->i_name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 12px; margin-bottom: 6px;">Batch & Expiry</label>
                        <select name="items[0][batch_expiry]" class="batch-expiry-select" required>
                            <option value="">Select Batch & Expiry</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 12px; margin-bottom: 6px;">Quantity</label>
                        <input type="number" name="items[0][quantity]" placeholder="Qty" min="1" required>
                    </div>
                    <div style="display: flex; align-items: flex-end;">
                        <button type="button" class="remove-item" onclick="removeItemRow(this)" style="background: #dc3545; color: white; border: none; border-radius: 6px; padding: 10px 14px; font-size: 13px; cursor: pointer; height: 42px; display: flex; align-items: center; gap: 6px; transition: all 0.2s;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Remarks Section -->
        <div style="background: #ffffff; padding: 24px; border-radius: 12px; margin-bottom: 24px; border: 1px solid #e9ecef;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="tr_remarks">
                    <i class="fas fa-comment" style="margin-right: 6px; color: #6c757d;"></i>
                    Remarks (Optional)
                </label>
                <textarea name="tr_remarks" id="tr_remarks" rows="3" placeholder="Add any additional notes or comments..."></textarea>
            </div>
        </div>

        <!-- Submit Button -->
        <div style="display: flex; justify-content: flex-end; gap: 12px;">
            <a href="{{ route('stock.transfer.list') }}" style="background: #6c757d; color: white; border: none; border-radius: 6px; padding: 12px 28px; font-size: 14px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
                <i class="fas fa-times"></i>
                Cancel
            </a>
            <button type="submit" style="background: #0f3e59; color: white; border: none; border-radius: 6px; padding: 12px 28px; font-size: 14px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
                <i class="fas fa-check"></i>
                Transfer Stock
            </button>
        </div>
    </form>
</div>

<style>
    #add-item:hover {
        background: #218838;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.2);
    }

    .remove-item:hover {
        background: #c82333;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.2);
    }

    button[type="submit"]:hover {
        background: #1a5270;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(15, 62, 89, 0.2);
    }

    a[href*="cancel"]:hover {
        background: #5a6268;
        transform: translateY(-1px);
    }
</style>

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
    newRow.style.cssText = 'display: grid; grid-template-columns: 2fr 2fr 1fr auto; gap: 12px; margin-bottom: 12px; padding: 16px; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef;';
    newRow.innerHTML = `
        <div class="form-group" style="margin-bottom: 0;">
            <label style="font-size: 12px; margin-bottom: 6px;">Item</label>
            <select name="items[${itemIndex}][item_id]" class="item-select" data-index="${itemIndex}" required>
                <option value="">Select Item</option>
                @foreach($items as $item)
                    <option value="{{ $item->item_id }}">{{ $item->i_stockID }} ({{ $item->i_name }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label style="font-size: 12px; margin-bottom: 6px;">Batch & Expiry</label>
            <select name="items[${itemIndex}][batch_expiry]" class="batch-expiry-select" required>
                <option value="">Select Batch & Expiry</option>
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label style="font-size: 12px; margin-bottom: 6px;">Quantity</label>
            <input type="number" name="items[${itemIndex}][quantity]" placeholder="Qty" min="1" required>
        </div>
        <div style="display: flex; align-items: flex-end;">
            <button type="button" class="remove-item" onclick="removeItemRow(this)" style="background: #dc3545; color: white; border: none; border-radius: 6px; padding: 10px 14px; font-size: 13px; cursor: pointer; height: 42px; display: flex; align-items: center; gap: 6px; transition: all 0.2s;">
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
    btn.parentElement.parentElement.remove();
}
</script>
@endsection