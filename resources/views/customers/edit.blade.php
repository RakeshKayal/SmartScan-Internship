@extends('layouts.app')

@section('title', 'Edit Customer | POS System')
@section('page-heading', 'Edit Customer')

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
            <h2>Update Customer</h2>
            <p>Edit customer details and save changes.</p>
        </div>

        <form action="{{ route('customers.update', $customer->id) }}" method="POST" class="custom-form">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label for="customer_name">Customer Name</label>
                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', $customer->customer_name) }}" required>
                </div>

                <div class="form-group">
                    <label for="customer_phone">Phone Number</label>
                    <input type="text" name="customer_phone" id="customer_phone" value="{{ old('customer_phone', $customer->customer_phone) }}" required>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="primary-action-btn">Update Customer</button>
                <a href="{{ route('customers.index') }}" class="secondary-action-btn">Back to Customers</a>
            </div>
        </form>
    </div>
@endsection