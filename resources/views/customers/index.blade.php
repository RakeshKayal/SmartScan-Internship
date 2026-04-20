@extends('layouts.app')

@section('title', 'All Customers | POS System')
@section('page-heading', 'All Customers')

@section('content')
    @if(session('success'))
        <div class="alert-box success-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-card">
        <div class="table-card-header">
            <div>
                <h2>Customer List</h2>
                <p>Manage saved customers for faster billing support.</p>
            </div>
            <a href="{{ route('customers.create') }}" class="primary-action-btn">+ Add Customer</a>
        </div>

        <div class="table-wrapper">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer Name</th>
                        <th>Phone Number</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->customer_name }}</td>
                            <td>{{ $customer->customer_phone }}</td>
                            <td>{{ $customer->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('customers.edit', $customer->id) }}" class="edit-btn">Update</a>

                                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">No customers found. Add your first customer.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection