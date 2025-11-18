@extends('layouts.subdept-layout')

@section('title', 'Stock Transfer')
@section('page_title', 'Stock Transfer')
@section('breadcrumb', 'Home / Stock Transfer')

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

{{-- Error Message --}}
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="max-width:700px; margin:auto; background:#fff; border-radius:16px; box-shadow:0 2px 8px rgba(0,0,0,0.05); padding:32px;">
    <h3 style="font-weight:700; color:#20425c; margin-bottom:24px;">Transfer Stock</h3>

    <form action="{{ route('stock.transfer.subdept.store') }}" method="POST">
        @csrf
        {{-- Transfer To --}}
        <div style="margin-bottom:18px;">
            <label style="font-weight:600; color:#20425c;" for="transfer_to">Transfer To Department: </label>
            <select name="transfer_to" id="transfer_to" class="form-control" style="background:#e3f0fc; border:none; border-radius:8px; padding:10px 16px;" required>
                <option value="">Select Department</option>
                <option value="Main Store">Main Store</option>
                <option value="Ward">Ward</option>
                <option value="ER">ER</option>
                <option value="ICU">ICU</option>
            </select>
        </div>
        {{-- Select Item --}}
        <!--div style="margin-bottom:18px;">
            <label style="font-weight:600; color:#20425c;" for="item_id">Select Item: </label>
            <select name="item_id" id="item_id" class="form-control" style="background:#e3f0fc; border:none; border-radius:8px; padding:10px 16px;" required>
                <option value="">-- Choose Item --</option>
                @forelse($inHandStock as $item)
                    <option value="{{ $item->item_id }}">
                        {{ $item->i_name }} (In Stock: {{ $item->in_hand_stock ?? 0 }})
                    </option>
                @empty
                    <option value="">No items available</option>
                @endforelse
            </select>
        </div-->
        {{-- Items Section --}}
        <table style="width:100%; margin-bottom:18px;">
            <button type="button" id="add-row" class="btn" style="background:#226699; color:#fff; font-weight:700; border-radius:8px; padding:8px 18px; font-size:1rem; border:none; margin-bottom:18px;">Add Item</button>
            <thead>
                <tr>
                    <th style="color:#20425c; font-weight:600;">Item</th>
                    <th style="color:#20425c; font-weight:600;">Quantity</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="item-rows">
                <tr>
                    <td>
                        <select name="items[0][item_id]" class="form-control" style="background:#e3f0fc; border:none; border-radius:8px; padding:10px 16px;" required>
                            <option value="">Select Item</option>
                            @foreach($inHandStock as $item)
                                <option value="{{ $item->item_id }}">{{ $item->i_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="items[0][quantity]" id= "transfer_qty" min="1" class="form-control" placeholder="Enter quantity" style="background:#e3f0fc; border:none; border-radius:8px; padding:10px 16px;" required>
                    </td>
                    <td>
                        <button type="button" class="btn remove-row" style="background:#e63946; color:#fff; border-radius:8px; padding:6px 12px; font-size:0.9rem; border:none;">Remove</button>
                    </td>
                </tr>
            </tbody>
        </table>
        {{-- Quantity --}}
        <!--div style="margin-bottom:18px;">
            <label style="font-weight:600; color:#20425c;" for="transfer_qty">Quantity to Transfer: </label>
            <input type="number" name="transfer_qty" id="transfer_qty" class="form-control" min="1" style="background:#e3f0fc; border:none; border-radius:8px; padding:10px 16px;" required>
        </div-->


        {{-- Remarks --}}
        <div style="margin-bottom:18px;">
            <label style="font-weight:600; color:#20425c;" for="tr_remarks">Remarks</label>
            <textarea name="tr_remarks" id="tr_remarks" rows="2" class="form-control" style="background:#e3f0fc; border:none; border-radius:8px; padding:10px 16px;"></textarea>
        </div>

        {{-- Submit --}}
        <div style="text-align:center; margin-top:24px;">
        <button type="submit" class="btn btn-primary" style="background:#226699; color:#fff; font-weight:700; border-radius:8px; padding:8px 18px; font-size:1rem; border:none; margin-bottom:18px;">Submit Transfer</button>
        </div>
    </form>
</div>

<!-- ✅ Template for dynamic rows -->
<script type="text/template" id="row-template">
<tr>
    <td>
        <select name="items[__INDEX__][item_id]" class="form-control" style="background:#e3f0fc; border:none; border-radius:8px; padding:10px 16px;" required>
            <option value="">Select Item</option>
            @foreach($inHandStock as $item)
                <option value="{{ $item->item_id }}">{{ $item->i_name }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="number" name="items[__INDEX__][quantity]" min="1" class="form-control" placeholder="Enter quantity" style="background:#e3f0fc; border:none; border-radius:8px; padding:10px 16px;" required>
    </td>
    <td>
        <button type="button" class="btn remove-row" style="background:#e63946; color:#fff; border-radius:8px; padding:6px 12px; font-size:0.9rem; border:none;">Remove</button>
    </td>
</tr>
</script>

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
            e.target.closest('tr').remove();
        }
    });

</script>
@endsection
