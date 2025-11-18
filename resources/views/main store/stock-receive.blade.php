@extends('layouts.main_store_layout')

@section('title', 'Stock Receive')

@section('content')
<div class="main">
    <div class="header">
        <h2>Stock Receive</h2>
        <p>Home / Stock Receive - Add GRN</p>
    </div>
    <div>
        <form method="POST" action="{{ route('stock.receive.store') }}" enctype="multipart/form-data">
        <p>If receive new item, please register the item first before key in the receive notes to avoid any redundancy of data.</p>
        <p>Go to "Item Register > Add Item".</p>
            @csrf
            <div class="form-group">
                <label for="grn_purchase_order_id">GRN Purchase Order ID</label>
                <input type="text" id="grn_purchase_order_id" name="grn_purchase_order_id" placeholder="Enter Purchase Order  ID" required>
            </div>
            <div class="form-group">
                <label for="total_order_received">Total Order Received</label>
                <input type="text" id="total_order_received" name="total_order_received" placeholder="Enter Total Item Received" required>
            </div>
            <div class="form-group">
                <label for="grn_supplier_company">GRN Supplier Company</label>
                <input type="text" id="grn_supplier_company" name="grn_supplier_company" placeholder="Enter Supplier Company" required>
            </div>
            <div class="form-group" style="display:flex;align-items:center;gap:10px;">
                <input type="file" id="purchase_order_file" name="purchase_order_file" style="flex:1;">
                <label for="purchase_order_file" style="margin:0;cursor:pointer;background:none;border:none;padding:0;">
                    <span style="font-size:1.2em;vertical-align:middle;">&#128193;</span>
                </label>
            </div>
            <div id="items-container">
                <!-- Item rows will be inserted here -->
            </div>
            <button type="submit" id="add-item-row" style="margin-bottom:16px;">+ Add Item</button>
            <button type="submit">SAVE</button>
        </form>
    </div>
</div>
<script>
    // Ensure $items is passed as an array of objects with the required properties in your controller:
    //
    // public function create() {
    //     $items = Item::all()->map(function($item) {
    //         return [
    //             'item_id' => $item->item_id,
    //             'i_name' => $item->i_name,
    //             'i_quantity_in_stock' => $item->i_quantity_in_stock,
    //         ];
    //     })->toArray();
    //     return view('main store.stock-receive', compact('items'));
    // }

    const items = @json($items);
    function createItemRow(index) {
        return `
        <div class="item-row" data-index="${index}" style="border:1px solid #eee;padding:16px;margin-bottom:12px;position:relative;">
            <button type="button" class="remove-item-row" style="position:absolute;top:8px;right:8px;">&times;</button>
            <div class="form-group">
                <label>Item Received</label>
                <select name="item_received[]" class="item-select" required>
                    <option value="">Select Item Received</option>
                    ${items.map(item => `<option value="${item.item_id}">${item.i_stockID} (Name: ${item.i_name})</option>`).join('')}
                    
                </select>
            </div>
            <div class="existing-item-fields" style="display:none;">
                <div class="form-group">
                    <label>Quantity Received</label>
                    <input type="number" name="existing_qty_received[]" placeholder="Enter Quantity Received">
                </div>
                <div class="form-group">
                    <label>Batch Number</label>
                    <input type="text" name="existing_batch_number[]" placeholder="Enter Batch Number" required>
                </div>
                <div class="form-group">
                    <label>Expiry Date (Month/Year)</label>
                    <input type="month" name="existing_expired_date[]" placeholder="MM/YYYY" required>
                </div>
            </div>
            <div class="new-item-fields" style="display:none;">
                <div class="form-group">
                    <label>New Item Name</label>
                    <input type="text" name="new_item_name[]" placeholder="Enter New Item Name">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="new_item_description[]" placeholder="Enter Description"></textarea>
                </div>
                <div class="form-group">
                    <label>Quantity Received</label>
                    <input type="number" name="new_qty_received[]" placeholder="Enter Quantity">
                </div>
                <div class="form-group">
                    <label>Batch Number</label>
                    <input type="text" name="new_batch_number[]" placeholder="Enter Batch Number">
                </div>
                <div class="form-group">
                    <label>Expired Date</label>
                    <input type="date" name="new_expired_date[]" placeholder="Enter Expired Date">
                </div>
            </div>
        </div>
        `;
    }
    function updateRowFields(row) {
        const select = row.querySelector('.item-select');
        const newFields = row.querySelector('.new-item-fields');
        const existingFields = row.querySelector('.existing-item-fields');
        if (select.value === 'new') {
            newFields.style.display = 'block';
            existingFields.style.display = 'none';
        } else if (select.value) {
            newFields.style.display = 'none';
            existingFields.style.display = 'block';
        } else {
            newFields.style.display = 'none';
            existingFields.style.display = 'none';
        }
    }
    function addItemRow() {
        const container = document.getElementById('items-container');
        const index = container.children.length;
        const temp = document.createElement('div');
        temp.innerHTML = createItemRow(index);
        const row = temp.firstElementChild;
        container.appendChild(row);
        const select = row.querySelector('.item-select');
        select.addEventListener('change', function() { updateRowFields(row); });
        row.querySelector('.remove-item-row').addEventListener('click', function() {
            row.remove();
        });
        // Show fields for first row by default
        updateRowFields(row);
    }
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('add-item-row').addEventListener('click', addItemRow);
        addItemRow(); // Add first row by default
    });
</script>
@endsection
