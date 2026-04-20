@extends('layouts.app')

@section('title', 'All Products | POS System')
@section('page-heading', 'All Products')

@section('content')
    @if(session('success'))
        <div class="alert-box success-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-card">
        <div class="table-card-header">
            <div>
                <h2>Product List</h2>
                <p>Manage all available products for your POS system.</p>
            </div>
            <a href="{{ route('products.create') }}" class="primary-action-btn">+ Add Product</a>
        </div>

        <div class="table-wrapper">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Barcode</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->product_description ?? 'N/A' }}</td>
                            <td>₹{{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->barcode }}</td>
                            <td>{{ $product->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('products.edit', $product->id) }}" class="edit-btn">Update</a>

                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">No products found. Add your first product.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection