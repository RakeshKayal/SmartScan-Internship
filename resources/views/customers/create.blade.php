@extends('layouts.app')

@section('title', 'Add Customer | POS System')
@section('page-heading', 'Add Customer')

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
            <h2>Add New Customer</h2>
            <p>Save customer details for quick billing and record keeping.</p>
        </div>

        <form action="{{ route('customers.store') }}" method="POST" class="custom-form">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="customer_name">Customer Name</label>
                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" placeholder="Enter customer name" required>
                </div>

                <div class="form-group">
                    <label for="customer_phone">Phone Number</label>
                    <input type="text" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}" placeholder="Enter customer phone number" required>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="primary-action-btn">Save Customer</button>
                <a href="{{ route('customers.index') }}" class="secondary-action-btn">View All Customers</a>
            </div>
        </form>
    </div>
@endsection