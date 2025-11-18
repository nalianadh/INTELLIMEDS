@extends('layouts.main_store_layout')

@section('title', 'Register Item - Main Store')

@section('content')
    <div class="main">
        <div class="header">
            <h2>Item Register</h2>
            <p>Item Register / New Item</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('items.store') }}">
            @csrf

            <div class="form-group">
                <label for="i_name">Item Name</label>
                <input type="text" name="i_name" id="i_name" placeholder="Enter item name" required>
            </div>

            <div class="form-group">
                <label for="i_description">Description</label>
                <textarea name="i_description" id="i_description" rows="4" placeholder="Enter item description" required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="i_stockID">Stock ID</label>
                    <input type="text" name="i_stockID" id="i_stockID" placeholder="e.g., STK-001" required>
                </div>

                <div class="form-group">
                    <label for="i_unit">Unit</label>
                    <input type="text" name="i_unit" id="i_unit" placeholder="e.g., box, bottle, pack" required>
                </div>
            </div>

            <button type="submit">
                <i class="fas fa-plus"></i>
                Register Item
            </button>
        </form>
    </div>
@endsection