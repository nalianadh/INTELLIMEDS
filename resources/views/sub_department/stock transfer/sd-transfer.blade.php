@extends('layouts.subdept-layout')

@section('title', 'Stock Transfer')
@section('page_title', 'Stock Transfer')
@section('page_icon', 'fas fa-exchange-alt')
@section('breadcrumb', 'Home / Inventory / Stock Transfer')

@section('content')
<div class="form-container">
    <form method="POST" action="{{ route('stock.transfer.subdept.store') }}" class="stock-transfer-form">
        @csrf
        
        <!-- Transfer Details Card -->
        <div class="card modern-card">
            <div class="card-header modern-header">
                <div class="header-content">
                    <div class="header-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h3 class="card-title">Transfer Information</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group modern-form-group">
                        <label for="tr_from_unit" class="modern-label">
                            <i class="fas fa-hospital"></i>
                            From (Ward/Unit/Facilities)
                        </label>
                        <input type="text" name="tr_from_unit" id="tr_from_unit" class="modern-input" placeholder="Enter source location" required>
                    </div>
                    <div class="form-group modern-form-group">
                        <label for="tr_destination" class="modern-label">
                            <i class="fas fa-map-marker-alt"></i>
                            To (Ward/Unit/Facilities)
                        </label>
                        <input type="text" name="tr_destination" id="tr_destination" class="modern-input" placeholder="Enter destination location" required>
                    </div>
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
                    <h3 class="card-title">Items to Transfer</h3>
                </div>
                <button type="button" id="add-item" class="btn btn-success modern-btn-add">
                    <i class="fas fa-plus"></i>
                    <span>Add Item</span>
                </button>
            </div>

            <div class="card-body">
                <div id="items-section">
                    <div class="item-row modern-item-row">
                        <div class="form-group modern-form-group">
                            <label class="modern-label">Item</label>
                            <select name="items[0][item_id]" class="item-select modern-select" data-index="0" required>
                                <option value="">Select Item</option>
                                @foreach($inHandStock as $item)
                                    <option value="{{ $item->item_id }}">{{ $item->i_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group modern-form-group">
                            <label class="modern-label">Batch & Expiry</label>
                            <select name="items[0][batch_expiry]" class="batch-expiry-select modern-select" required>
                                <option value="">Select Batch & Expiry</option>
                            </select>
                        </div>
                        <div class="form-group modern-form-group">
                            <label class="modern-label">Quantity</label>
                            <input type="number" name="items[0][quantity]" class="modern-input" placeholder="Qty" min="1" required>
                        </div>
                        <div class="form-group modern-form-group remove-btn-wrapper">
                            <button type="button" class="btn btn-danger modern-btn-remove remove-item" onclick="removeItemRow(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Remarks Card -->
        <div class="card modern-card">
            <div class="card-body">
                <div class="form-group modern-form-group" style="margin-bottom: 0;">
                    <label for="tr_remarks" class="modern-label">
                        <i class="fas fa-comment"></i>
                        Remarks (Optional)
                    </label>
                    <textarea name="tr_remarks" id="tr_remarks" class="modern-textarea" rows="4" placeholder="Add any additional notes or comments..."></textarea>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary modern-btn-primary">
                <i class="fas fa-check"></i>
                <span>Transfer Stock</span>
            </button>
        </div>
    </form>
</div>

@push('styles')
<style>
    .form-container {
        width: 100%;
        margin: 0;
        padding: 0;
    }

    .stock-transfer-form {
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

    /* Form Row */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
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

    .modern-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e8ecef;
        border-radius: 8px;
        font-size: 15px;
        color: #2c3e50;
        background: #ffffff;
        transition: all 0.2s ease;
        outline: none;
        resize: vertical;
        font-family: inherit;
    }

    .modern-textarea:focus {
        border-color: #1e5f74;
        box-shadow: 0 0 0 4px rgba(30, 95, 116, 0.1);
    }

    .modern-textarea:hover {
        border-color: #c5d0d8;
    }

    /* Modern Item Row */
    .modern-item-row {
        display: grid;
        grid-template-columns: 2fr 2fr 1fr auto;
        gap: 16px;
        padding: 20px;
        background: linear-gradient(135deg, #f8fafb 0%, #f4f7fa 100%);
        border-radius: 10px;
        border: 2px solid #e8ecef;
        align-items: end;
        margin-bottom: 16px;
        transition: all 0.2s ease;
        animation: slideIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modern-item-row:hover {
        background: linear-gradient(135deg, #f0f4f7 0%, #e8f0f5 100%);
        border-color: #d1d8dd;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
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

    .modern-item-row .form-group {
        margin-bottom: 0;
    }

    .remove-btn-wrapper {
        display: flex;
        align-items: flex-end;
        justify-content: flex-end;
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
        padding: 10px 16px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 44px;
        height: 44px;
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

    .modern-btn-primary {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 28px;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        background: linear-gradient(135deg, #1e5f74 0%, #0f3e59 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(15, 62, 89, 0.25);
    }

    .modern-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(15, 62, 89, 0.35);
        background: linear-gradient(135deg, #0f3e59 0%, #0a2e44 100%);
    }

    .modern-btn-primary:active {
        transform: translateY(0);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .modern-item-row {
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .remove-btn-wrapper {
            grid-column: 1 / -1;
            justify-content: flex-start;
        }
    }

    @media (max-width: 768px) {
        .modern-header {
            flex-direction: column;
            gap: 16px;
            align-items: flex-start;
        }

        .modern-btn-add {
            width: 100%;
            justify-content: center;
        }

        .modern-item-row {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .modern-btn-primary {
            width: 100%;
            justify-content: center;
        }

        .card-body {
            padding: 20px 16px;
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
    newRow.className = 'item-row modern-item-row';
    newRow.innerHTML = `
        <div class="form-group modern-form-group">
            <label class="modern-label">Item</label>
            <select name="items[${itemIndex}][item_id]" class="item-select modern-select" data-index="${itemIndex}" required>
                <option value="">Select Item</option>
                @foreach($items as $item)
                    <option value="{{ $item->item_id }}">{{ $item->i_stockID }} ({{ $item->i_name }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group modern-form-group">
            <label class="modern-label">Batch & Expiry</label>
            <select name="items[${itemIndex}][batch_expiry]" class="batch-expiry-select modern-select" required>
                <option value="">Select Batch & Expiry</option>
            </select>
        </div>
        <div class="form-group modern-form-group">
            <label class="modern-label">Quantity</label>
            <input type="number" name="items[${itemIndex}][quantity]" class="modern-input" placeholder="Qty" min="1" required>
        </div>
        <div class="form-group modern-form-group remove-btn-wrapper">
            <button type="button" class="btn btn-danger modern-btn-remove remove-item" onclick="removeItemRow(this)">
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