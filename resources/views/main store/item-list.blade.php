@extends('layouts.main_store_layout')

@section('title', 'Item List')

@section('content')
<div class="main">
    <div class="header">
        <h2>Item Register</h2>
        <p>Item Register / Registered Items</p>
    </div>
    <!--form method="GET" action="" style="margin-bottom:24px;">
        <div class="custom-search-bar">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search items by name, stock ID, batch..." class="custom-search-input">
            <button type="submit" class="custom-search-btn">Search</button>
        </div>
    </form-->
    <div style="padding:24px 0;">
        <table style="width:100%; background:#fff; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.06); border-collapse:collapse;">
            <thead>
                <tr style="background:#f6f9fc;">
                    <th style="padding:10px; border-bottom:1.5px solid #eaeaea;">Name</th>
                    <th style="padding:10px; border-bottom:1.5px solid #eaeaea;">Stock ID</th>
                    <th style="padding:10px; border-bottom:1.5px solid #eaeaea;">Qty</th>
                    <th style="padding:10px; border-bottom:1.5px solid #eaeaea;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td style="padding:8px; border-bottom:1px solid #f0f0f0;">{{ $item->i_name }}</td>
                        <td style="padding:8px; border-bottom:1px solid #f0f0f0; text-align:center;">{{ $item->i_stockID }}</td>
                        <td style="padding:8px; border-bottom:1px solid #f0f0f0; text-align:center;">{{ $item->i_quantity_in_stock }}</td>
                        <td style="padding:8px; border-bottom:1px solid #f0f0f0; text-align:center;">
                            <a href="{{ route('items.view', ['item' => $item->item_id]) }}" style="padding:6px 18px; border-radius:6px; background:#20425c; color:#fff; border:none; cursor:pointer; font-size:0.95em; text-decoration:none; display:inline-block;">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="padding:12px; text-align:center; color:#aaa;">No items registered yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
