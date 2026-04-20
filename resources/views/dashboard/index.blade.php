@extends('layouts.app')

@section('title', 'Dashboard | POS System')
@section('page-heading', 'Dashboard')

@section('content')

@if(session('success'))
    <div class="alert-box success-alert">
        {{ session('success') }}
    </div>
@endif

<!-- HERO -->
<section class="dashboard-hero-modern">
    <div class="hero-content">
        <h1>Dashboard</h1>
        <p>Welcome back, <strong>{{ auth()->user()->name }}</strong></p>
        <span class="role-badge">{{ ucfirst(auth()->user()->role) }}</span>
    </div>

    <div class="hero-stats">
        <div class="mini-card">
            <h4>Quick Access</h4>
            <p>Manage your POS efficiently</p>
        </div>
    </div>
</section>

<!-- ACTION CARDS -->
<section class="dashboard-grid-modern">

    <a href="{{ route('products.index') }}" class="card modern-card">
        <div class="card-icon">📦</div>
        <h3>Products</h3>
        <p>View & manage inventory</p>
    </a>

    <a href="{{ route('customers.index') }}" class="card modern-card">
        <div class="card-icon">👥</div>
        <h3>Customers</h3>
        <p>Manage customer records</p>
    </a>

    <a href="{{ route('products.create') }}" class="card modern-card highlight">
        <div class="card-icon">➕</div>
        <h3>Add Product</h3>
        <p>Create new product</p>
    </a>

    <a href="{{ route('customers.create') }}" class="card modern-card highlight">
        <div class="card-icon">🧾</div>
        <h3>Add Customer</h3>
        <p>Add new customer</p>
    </a>

</section>

@endsection