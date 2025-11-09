@extends('layouts.main_store_layout')

@section('title', 'Stock Transfer')

@section('content')
<div class="main">
    <div class="header">
        <h2>Stock Transfer</h2>
        <p>Home / Stock Transfer - New Transfer Out</p>
    </div>
    <div>
        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:16px; color: #155724; background: #d4edda; border: 1px solid #c3e6cb; padding: 10px 20px; border-radius: 4px;">
                {{ session('success') }}
            </div>
        @endif
        <form method="POST" action="{{ route('stock.transfer.out.store') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label for="tr_from_unit">From Unit</label>
                    <input type="text" name="tr_from_unit" id="tr_from_unit" required>
                </div>
                <div class="form-group">
                    <label for="tr_destination">To Unit</label>
                    <input type="text" name="tr_destination" id="tr_destination" required>
                </div>
            </div>
            <div id="items-section">
                <label>Items</label>
                <div class="item-row">
                    <div class="form-group">
                        <select name="items[0][item_id]" class="item-select" data-index="0" required>
                            <option value="">Select Item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->item_id }}">{{ $item->i_stockID }} ({{ $item->i_name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="items[0][batch_expiry]" class="batch-expiry-select" required>
                            <option value="">Select Batch & Expiry</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="number" name="items[0][quantity]" placeholder="Quantity" min="1" required>
                    </div>
                    <button type="button" class="remove-item" onclick="removeItemRow(this)">Remove</button>
                </div>
            </div>
            <button type="button" id="add-item">Add Item</button>
            <div class="form-group">
                <label for="tr_remarks">Remarks</label>
                <textarea name="tr_remarks" id="tr_remarks" rows="2"></textarea>
            </div>
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
                        <select name="items[${itemIndex}][item_id]" class="item-select" data-index="${itemIndex}" required>
                            <option value="">Select Item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->item_id }}">{{ $item->i_stockID }} ({{ $item->i_name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="items[${itemIndex}][batch_expiry]" class="batch-expiry-select" required>
                            <option value="">Select Batch & Expiry</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="number" name="items[${itemIndex}][quantity]" placeholder="Quantity" min="1" required>
                    </div>
                    <button type="button" class="remove-item" onclick="removeItemRow(this)">Remove</button>
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
                btn.parentElement.remove();
            }
            </script>
            <button type="submit">Transfer Stock</button>
        </form>
    </div>
</div>
@endsection
