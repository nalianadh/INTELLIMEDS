@extends('layouts.main_store_layout')

@section('title', 'Register Item - Main Store')

@section('content')
    <div class="main" style="display: flex; gap: 40px; align-items: flex-start;">
        <div style="flex: 1;">
            <div class="header">
                <h2>Item Register</h2>
                <p>Item Register / New Item</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom:16px; color: #155724; background: #d4edda; border: 1px solid #c3e6cb; padding: 10px 20px; border-radius: 4px;">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('items.store') }}" class="register-form">
                @csrf

                <div class="form-group">
                    <label for="i_name">Item Name</label>
                    <input type="text" name="i_name" id="i_name" required>
                </div>

                <div class="form-group">
                    <label for="i_description">Description</label>
                    <textarea name="i_description" id="i_description" rows="3" required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="i_stockID">Stock ID</label>
                        <input type="text" name="i_stockID" id="i_stockID" required>
                    </div>

                    <div class="form-group">
                        <label for="i_unit">Unit</label>
                        <input type="text" name="i_unit" id="i_unit" placeholder="e.g. box, bottle, pack" required>
                    </div>
                </div>

                <button type="submit">Register Item</button>
            </form>
        </div>
    </div>
@endsection
