@extends('layouts.app')

@section('title', 'Add Product | POS System')
@section('page-heading', 'Add Product')

@section('content')
    @if($errors->any())
        <div class="alert-box error-alert">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">
        <div class="form-card-header">
            <h2>Add New Product</h2>
            <p>Enter product details for inventory and billing use.</p>
        </div>

        <form action="{{ route('products.store') }}" method="POST" class="custom-form">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="product_name">Product Name</label>
                    <input type="text" name="product_name" id="product_name" value="{{ old('product_name') }}" placeholder="Enter product name" required>
                </div>

                <div class="form-group">
                    <label for="price">Price (₹)</label>
                    <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}" placeholder="Enter product price" required>
                </div>

                
            </div>

            <div class="form-group">
                <label for="product_description">Product Description</label>
                <textarea name="product_description" id="product_description" rows="4" placeholder="Enter product description (optional)">{{ old('product_description') }}</textarea>
            </div>

            
            <div class="form-group">
                    <label for="barcode">Barcode</label>
                    <input type="text" name="barcode" id="barcode" value="{{ old('barcode') }}" placeholder="Enter barcode" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="primary-action-btn">Save Product</button>
                <a href="{{ route('products.index') }}" class="secondary-action-btn">View All Products</a>
            </div>
        </form>
    </div>
@endsection