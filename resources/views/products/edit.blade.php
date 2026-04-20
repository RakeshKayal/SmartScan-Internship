@extends('layouts.app')

@section('title', 'Edit Product | POS System')
@section('page-heading', 'Edit Product')

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
            <h2>Update Product</h2>
            <p>Edit product details and save changes.</p>
        </div>

        <form action="{{ route('products.update', $product->id) }}" method="POST" class="custom-form">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label for="product_name">Product Name</label>
                    <input type="text" name="product_name" id="product_name" value="{{ old('product_name', $product->product_name) }}" required>
                </div>

                <div class="form-group">
                    <label for="barcode">Barcode</label>
                    <input type="text" name="barcode" id="barcode" value="{{ old('barcode', $product->barcode) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label for="product_description">Product Description</label>
                <textarea name="product_description" id="product_description" rows="4">{{ old('product_description', $product->product_description) }}</textarea>
            </div>

            <div class="form-group">
                <label for="price">Price (₹)</label>
                <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $product->price) }}" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="primary-action-btn">Update Product</button>
                <a href="{{ route('products.index') }}" class="secondary-action-btn">Back to Products</a>
            </div>
        </form>
    </div>
@endsection