@extends('layouts.admin')

@section('title', __('messages.Add_New_Note_Voucher'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ __('messages.Add_New_Note_Voucher') }}</h4>
                    <a href="{{ route('note-vouchers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>{{ __('messages.Back') }}
                    </a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('note-vouchers.store') }}" method="POST" id="voucherForm">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="number" class="form-label">{{ __('messages.Number') }} *</label>
                                <input type="number" 
                                       class="form-control @error('number') is-invalid @enderror" 
                                       id="number" 
                                       name="number" 
                                       value="{{ old('number', $nextNumber) }}" 
                                       required>
                                @error('number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="type" class="form-label">{{ __('messages.Type') }} *</label>
                                <select class="form-control @error('type') is-invalid @enderror" 
                                        id="type" 
                                        name="type" 
                                        required>
                                    <option value="">{{ __('messages.Select_Type') }}</option>
                                    <option value="1" {{ old('type') == 1 ? 'selected' : '' }}>{{ __('messages.In') }}</option>
                                    <option value="2" {{ old('type') == 2 ? 'selected' : '' }}>{{ __('messages.Out') }}</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="date_note_voucher" class="form-label">{{ __('messages.Date') }} *</label>
                                <input type="date" 
                                       class="form-control @error('date_note_voucher') is-invalid @enderror" 
                                       id="date_note_voucher" 
                                       name="date_note_voucher" 
                                       value="{{ old('date_note_voucher', date('Y-m-d')) }}" 
                                       required>
                                @error('date_note_voucher')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="warehouse_id" class="form-label">{{ __('messages.Warehouse') }}</label>
                                <select class="form-control @error('warehouse_id') is-invalid @enderror" 
                                        id="warehouse_id" 
                                        name="warehouse_id">
                                    <option value="">{{ __('messages.Select_Warehouse') }}</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                            {{ $warehouse->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('warehouse_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="order_id" class="form-label">{{ __('messages.Order') }}</label>
                                <select class="form-control @error('order_id') is-invalid @enderror" 
                                        id="order_id" 
                                        name="order_id">
                                    <option value="">{{ __('messages.Select_Order') }}</option>
                                    @foreach($orders as $order)
                                        <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                            {{ $order->number }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('order_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="note" class="form-label">{{ __('messages.Note') }}</label>
                                <textarea class="form-control @error('note') is-invalid @enderror" 
                                          id="note" 
                                          name="note" 
                                          rows="3">{{ old('note') }}</textarea>
                                @error('note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ __('messages.Products') }}</h5>
                                <button type="button" class="btn btn-sm btn-success" onclick="addProductRow()">
                                    <i class="fas fa-plus me-2"></i>{{ __('messages.Add_Product') }}
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="products-container">
                                    <!-- Product rows will be added here -->
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-save me-2"></i>{{ __('messages.Save') }}
                                </button>
                                <a href="{{ route('note-vouchers.index') }}" class="btn btn-secondary">
                                    {{ __('messages.Cancel') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Row Template -->
<template id="product-row-template">
    <div class="product-row mb-3 p-3 border rounded">
        <div class="row align-items-end">
            <div class="col-md-3">
                <label class="form-label">{{ __('messages.Product') }} *</label>
                <select class="form-control product-select" name="products[INDEX][product_id]" required>
                    <option value="">{{ __('messages.Select_Product') }}</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">{{ __('messages.Variation') }}</label>
                <select class="form-control variation-select" name="products[INDEX][variation_id]">
                    <option value="">{{ __('messages.Select_Variation') }}</option>
                    @foreach($variations as $variation)
                        <option value="{{ $variation->id }}">{{ $variation->color->name }} - {{ $variation->size->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">{{ __('messages.Quantity') }} *</label>
                <input type="number" class="form-control" name="products[INDEX][quantity]" min="1" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">{{ __('messages.Note') }}</label>
                <input type="text" class="form-control" name="products[INDEX][note]">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeProductRow(this)">
                    <i class="fas fa-trash"></i> {{ __('messages.Remove') }}
                </button>
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
let productRowIndex = 0;

function addProductRow() {
    const container = document.getElementById('products-container');
    const template = document.getElementById('product-row-template');
    const clone = template.content.cloneNode(true);
    
    // Replace INDEX with actual index
    const html = clone.querySelector('.product-row').outerHTML.replace(/INDEX/g, productRowIndex);
    container.insertAdjacentHTML('beforeend', html);
    
    productRowIndex++;
}

function removeProductRow(button) {
    button.closest('.product-row').remove();
}

// Add initial product row
document.addEventListener('DOMContentLoaded', function() {
    addProductRow();
});

// Form validation
document.getElementById('voucherForm').addEventListener('submit', function(e) {
    const productRows = document.querySelectorAll('.product-row');
    if (productRows.length === 0) {
        e.preventDefault();
        alert('{{ __("messages.Please_Add_At_Least_One_Product") }}');
        return false;
    }
});
</script>
@endpush
@endsection