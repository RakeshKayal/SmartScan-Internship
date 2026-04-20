@php
    $currentRoute = request()->route() ? request()->route()->getName() : '';
@endphp

<aside class="sidebar">
    <div class="sidebar-top">
        <a href="{{ route('dashboard') }}" class="brand-block">
            <div class="brand-icon">P</div>
            <div class="brand-text">
                <h2>POS System</h2>
                <p>Smart Retail Panel</p>
            </div>
        </a>
    </div>

    <div class="sidebar-section-label">MAIN NAVIGATION</div>

    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}"
           class="sidebar-link {{ $currentRoute === 'dashboard' ? 'active' : '' }}">
            <span class="sidebar-link-icon">◉</span>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('products.create') }}"
           class="sidebar-link {{ $currentRoute === 'products.create' ? 'active' : '' }}">
            <span class="sidebar-link-icon">◉</span>
            <span>Add Product</span>
        </a>

        <a href="{{ route('products.index') }}"
           class="sidebar-link {{ in_array($currentRoute, ['products.index', 'products.edit']) ? 'active' : '' }}">
            <span class="sidebar-link-icon">◉</span>
            <span>All Products</span>
        </a>

        <a href="{{ route('customers.create') }}"
           class="sidebar-link {{ $currentRoute === 'customers.create' ? 'active' : '' }}">
            <span class="sidebar-link-icon">◉</span>
            <span>Add Customer</span>
        </a>

        <a href="{{ route('customers.index') }}"
           class="sidebar-link {{ in_array($currentRoute, ['customers.index', 'customers.edit']) ? 'active' : '' }}">
            <span class="sidebar-link-icon">◉</span>
            <span>All Customers</span>
        </a>

        <a href="{{ route('billing.index') }}"
           class="sidebar-link {{ $currentRoute === 'billing.index' ? 'active' : '' }}">
            <span class="sidebar-link-icon">◉</span>
            <span>Billing</span>
        </a>

        <a href="{{ route('orders.index') }}"
           class="sidebar-link {{ $currentRoute === 'orders.index' ? 'active' : '' }}">
            <span class="sidebar-link-icon">◉</span>
            <span>Order History</span>
        </a>
        @if(Auth::user()->role === 'admin')
<a href="{{ route('users.index') }}"
   class="sidebar-link {{ in_array($currentRoute, ['users.index', 'users.create']) ? 'active' : '' }}">
    <span class="sidebar-link-icon">◉</span>
    <span>Manage Sales Person</span>
</a>
@endif
    </nav>

    <div class="sidebar-bottom-card">
        <div class="sidebar-bottom-content">
            <span class="sidebar-bottom-label">POS STATUS</span>
            <h4>Workspace Active</h4>
            <p>Manage sales, products, and customer billing in one streamlined dashboard.</p>
        </div>
    </div>
</aside>