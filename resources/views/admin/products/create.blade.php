@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Add_Product') }}</h3>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('messages.Back') }}
                    </a>
                </div>

                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>{{ __('messages.Basic_Information') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('messages.Name_English') }} <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('name_en') is-invalid @enderror" 
                                                           name="name_en" value="{{ old('name_en') }}" required>
                                                    @error('name_en')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('messages.Name_Arabic') }} <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('name_ar') is-invalid @enderror" 
                                                           name="name_ar" value="{{ old('name_ar') }}" required>
                                                    @error('name_ar')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- Add this after the name_ar field -->
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('messages.Gender') }} <span class="text-danger">*</span></label>
                                                    <select class="form-control @error('gender') is-invalid @enderror" name="gender" required>
                                                        <option value="">{{ __('messages.Select_Gender') }}</option>
                                                        <option value="man" {{ old('gender') == 'man' ? 'selected' : '' }}>{{ __('messages.Man') }}</option>
                                                        <option value="woman" {{ old('gender') == 'woman' ? 'selected' : '' }}>{{ __('messages.Woman') }}</option>
                                                        <option value="both" {{ old('gender', 'both') == 'both' ? 'selected' : '' }}>{{ __('messages.Both') }}</option>
                                                    </select>
                                                    @error('gender')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('messages.Description_English') }} <span class="text-danger">*</span></label>
                                                    <textarea class="form-control @error('description_en') is-invalid @enderror" 
                                                              name="description_en" rows="4" required>{{ old('description_en') }}</textarea>
                                                    @error('description_en')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('messages.Description_Arabic') }} <span class="text-danger">*</span></label>
                                                    <textarea class="form-control @error('description_ar') is-invalid @enderror" 
                                                              name="description_ar" rows="4" required>{{ old('description_ar') }}</textarea>
                                                    @error('description_ar')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('messages.Price') }} <span class="text-danger">*</span></label>
                                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                                           name="price" value="{{ old('price') }}" required>
                                                    @error('price')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('messages.Tax_Percentage') }}</label>
                                                    <input type="number" step="0.01" class="form-control @error('tax') is-invalid @enderror" 
                                                           name="tax" value="{{ old('tax', 16) }}">
                                                    @error('tax')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('messages.Discount_Percentage') }}</label>
                                                    <input type="number" step="0.01" class="form-control @error('discount_percentage') is-invalid @enderror" 
                                                           name="discount_percentage" value="{{ old('discount_percentage') }}">
                                                    @error('discount_percentage')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Images -->
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5>{{ __('messages.Product_Images') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('messages.Images') }}</label>
                                            <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                                                   name="images[]" multiple accept="image/*">
                                            <small class="form-text text-muted">
                                                {{ __('messages.You_Can_Select_Multiple_Images') }}
                                            </small>
                                            @error('images.*')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Variations -->
                                <div class="card mt-3">
                                    <div class="card-header d-flex justify-content-between">
                                        <h5>{{ __('messages.Product_Variations') }}</h5>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="addVariation()">
                                            <i class="fas fa-plus"></i> {{ __('messages.Add_Variation') }}
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div id="variations-container">
                                            <!-- Variations will be added here dynamically -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Relations -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>{{ __('messages.Relations') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('messages.Category') }}</label>
                                            <select class="form-control @error('category_id') is-invalid @enderror" name="category_id">
                                                <option value="">{{ __('messages.Select_Category') }}</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ app()->getLocale() == 'ar' ? $category->name_ar : $category->name_en }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">{{ __('messages.Celebrity') }}</label>
                                            <select class="form-control @error('celebrity_id') is-invalid @enderror" name="celebrity_id">
                                                <option value="">{{ __('messages.Select_Celebrity') }}</option>
                                                @foreach($celebrities as $celebrity)
                                                    <option value="{{ $celebrity->id }}" {{ old('celebrity_id') == $celebrity->id ? 'selected' : '' }}>
                                                        {{ app()->getLocale() == 'ar' ? $celebrity->name_ar : $celebrity->name_en }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('celebrity_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">{{ __('messages.Brand') }}</label>
                                            <select class="form-control @error('brand_id') is-invalid @enderror" name="brand_id">
                                                <option value="">{{ __('messages.Select_Brand') }}</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                        {{ app()->getLocale() == 'ar' ? $brand->name_ar : $brand->name_en }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('brand_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">{{ __('messages.Shop') }}</label>
                                            <select class="form-control @error('shop_id') is-invalid @enderror" name="shop_id">
                                                <option value="">{{ __('messages.Select_Shop') }}</option>
                                                @foreach($shops as $shop)
                                                    <option value="{{ $shop->id }}" {{ old('shop_id') == $shop->id ? 'selected' : '' }}>
                                                        {{ app()->getLocale() == 'ar' ? $shop->name_ar : $shop->name_en }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('shop_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ __('messages.Save_Product') }}
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            {{ __('messages.Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Variation Template -->
<template id="variation-template">
    <div class="variation-item border rounded p-3 mb-3">
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('messages.Color') }} <span class="text-danger">*</span></label>
                    <select class="form-control" name="variations[INDEX][color_id]" required>
                        <option value="">{{ __('messages.Select_Color') }}</option>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('messages.Size') }} <span class="text-danger">*</span></label>
                    <select class="form-control" name="variations[INDEX][size_id]" required>
                        <option value="">{{ __('messages.Select_Size') }}</option>
                        @foreach($sizes as $size)
                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">{{ __('messages.Price_Adjustment') }}</label>
                    <input type="number" step="0.01" class="form-control" name="variations[INDEX][price_adjustment]" value="0">
                </div>
            </div>
            <div class="col-md-1">
                <div class="mb-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-danger w-100" onclick="removeVariation(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('messages.Status') }}</label>
                    <select class="form-control" name="variations[INDEX][status]">
                        <option value="1">{{ __('messages.Active') }}</option>
                        <option value="2">{{ __('messages.Inactive') }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</template>
@endsection

@push('scripts')
<script>
let variationIndex = 0;

function addVariation() {
    const container = document.getElementById('variations-container');
    const template = document.getElementById('variation-template');
    const clone = template.content.cloneNode(true);
    
    // Replace INDEX placeholder with actual index
    const html = clone.querySelector('.variation-item').outerHTML.replace(/INDEX/g, variationIndex);
    container.insertAdjacentHTML('beforeend', html);
    
    variationIndex++;
}

function removeVariation(button) {
    button.closest('.variation-item').remove();
}

// Add one variation by default
document.addEventListener('DOMContentLoaded', function() {
    addVariation();
});
</script>
@endpush