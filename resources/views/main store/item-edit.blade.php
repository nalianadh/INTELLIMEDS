@extends('layouts.main_store_layout')

@section('title', 'Edit Item')

@section('content')
<div class="main">
    <div class="header">
        <h2>Edit Item</h2>
        <p>Item Register / Edit Item</p>
    </div>

    <!-- Form Container -->
    <div class="form-container">
        <div class="form-header">
            <div class="form-header-content">
                <i class="fas fa-edit" style="color: #0f3e59; font-size: 20px;"></i>
                <h3>Edit: {{ $item->i_name }}</h3>
            </div>
            <a href="{{ route('items.list') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        <!-- Form Start -->
        <form action="{{ route('items.update', $item->item_id) }}" method="POST" class="item-form">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <!-- Stock ID -->
                <div class="form-group">
                    <label for="i_stockID" class="form-label">
                        <i class="fas fa-barcode"></i> Stock ID <span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="i_stockID"
                        name="i_stockID" 
                        value="{{ old('i_stockID', $item->i_stockID) }}"
                        required
                        class="form-control"
                        placeholder="Enter stock ID"
                    >
                </div>

                <!-- Min qty -->
                <div class="form-group">
                    <label for="i_minLevel" class="form-label">
                        <i class="fas fa-barcode"></i> Minimum Level <span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="i_minLevel"
                        name="i_minLevel" 
                        value="{{ old('i_minLevel', $item->i_minLevel) }}"
                        required
                        class="form-control"
                        placeholder="Enter stock ID"
                    >
                </div>

                <!-- Item Name -->
                <div class="form-group">
                    <label for="i_name" class="form-label">
                        <i class="fas fa-box"></i> Item Name <span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="i_name"
                        name="i_name" 
                        value="{{ old('i_name', $item->i_name) }}"
                        required
                        class="form-control"
                        placeholder="Enter item name"
                    >
                </div>

                <!-- Quantity In Stock (Read-Only) -->
                <div class="form-group">
                    <label for="i_quantity_in_stock" class="form-label">
                        <i class="fas fa-cubes"></i> Quantity In Stock
                    </label>
                    <input 
                        type="number" 
                        id="i_quantity_in_stock"
                        name="i_quantity_in_stock" 
                        value="{{ $item->i_quantity_in_stock }}"
                        readonly 
                        class="form-control form-control-readonly"
                    >
                    <small class="form-text">This field is read-only</small>
                </div>
            </div>

            <!-- Description -->
            <div class="form-section">
                <h4 class="section-title">
                    <i class="fas fa-align-left"></i> Description
                </h4>
                <div class="form-group">
                    <label for="i_description" class="form-label">
                        Item Description <span class="required">*</span>
                    </label>
                    <textarea 
                        id="i_description"
                        name="i_description"
                        required
                        class="form-control"
                        rows="5"
                        placeholder="Enter detailed description of the item"
                    >{{ old('i_description', $item->i_description) }}</textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('items.list') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Update Item
                </button>
            </div>

        </form>
        <!-- Form End -->

    </div>
</div>

<style>
    /* Form Container */
    .form-container {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #e9ecef;
        overflow: hidden;
        margin-top: 20px;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }

    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 28px;
        border-bottom: 2px solid #e9ecef;
        background: linear-gradient(to bottom, #f8f9fa, #ffffff);
    }

    .form-header-content {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .form-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        color: #0f3e59;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: #6c757d;
        color: #ffffff;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-back:hover {
        background: #5a6268;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(108, 117, 125, 0.2);
    }

    .btn-back i {
        font-size: 12px;
    }

    /* Form Styles */
    .item-form {
        padding: 32px 28px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 24px;
        margin-bottom: 28px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
    }

    .form-label i {
        color: #6c757d;
        font-size: 14px;
    }

    .required {
        color: #dc3545;
        font-weight: bold;
        margin-left: 2px;
    }

    .form-control {
        padding: 12px 14px;
        font-size: 14px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        transition: all 0.2s ease;
        font-family: inherit;
        background: #ffffff;
    }

    .form-control:focus {
        outline: none;
        border-color: #0f3e59;
        box-shadow: 0 0 0 3px rgba(15, 62, 89, 0.1);
    }

    .form-control-readonly {
        background: #f8f9fa;
        cursor: not-allowed;
        color: #6c757d;
    }

    .form-control-readonly:focus {
        border-color: #ced4da;
        box-shadow: none;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 120px;
        line-height: 1.6;
    }

    .form-text {
        display: block;
        margin-top: 6px;
        font-size: 12px;
        color: #6c757d;
        font-style: italic;
    }

    /* Section Styles */
    .form-section {
        margin-bottom: 28px;
        padding: 24px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
        font-weight: 600;
        color: #0f3e59;
        margin: 0 0 20px 0;
    }

    .section-title i {
        color: #0f3e59;
        font-size: 16px;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding-top: 24px;
        border-top: 2px solid #e9ecef;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 32px;
        background: #0f3e59;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        order: 2;
    }

    .btn-submit:hover {
        background: #1a5270;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 62, 89, 0.3);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    .btn-submit i {
        font-size: 14px;
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: #ffffff;
        color: #6c757d;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        order: 1;
    }

    .btn-cancel:hover {
        background: #f8f9fa;
        border-color: #6c757d;
        color: #495057;
        transform: translateY(-1px);
    }

    .btn-cancel i {
        font-size: 12px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-container {
            margin: 20px 0;
            border-radius: 8px;
        }

        .form-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
            padding: 16px 20px;
        }

        .form-header-content {
            width: 100%;
        }

        .btn-back {
            width: 100%;
            justify-content: center;
        }

        .item-form {
            padding: 24px 20px;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .form-section {
            padding: 20px;
        }

        .form-actions {
            flex-direction: column-reverse;
            gap: 12px;
        }

        .btn-submit,
        .btn-cancel {
            width: 100%;
            justify-content: center;
            order: 0;
        }
    }

    /* Focus visible for accessibility */
    .btn-submit:focus-visible,
    .btn-cancel:focus-visible,
    .btn-back:focus-visible {
        outline: 2px solid #0f3e59;
        outline-offset: 2px;
    }
</style>
@endsection