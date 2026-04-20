@extends('layouts.app')

@section('title', 'Order History | POS System')
@section('page-heading', 'Order History')

@section('content')
    @if(session('success'))
        <div class="alert-box success-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-card">
        <div class="table-card-header">
            <div>
                <h2>Order History</h2>
                <p>All generated bills before payment processing.</p>
            </div>
            <a href="{{ route('billing.index') }}" class="primary-action-btn">+ New Billing</a>
        </div>

        <div class="table-wrapper">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Order Number</th>
                        <th>Customer Type</th>
                        <th>Customer</th>
                        <th>Billed By</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $order->customer_type)) }}</td>
                            <td>
                                @if($order->customer)
                                    {{ $order->customer->customer_name }} ({{ $order->customer->customer_phone }})
                                @else
                                    Walk-in Customer
                                @endif
                            </td>
                            <td>{{ $order->user->name ?? 'N/A' }}</td>
                            <td>₹{{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span class="status-badge status-pending">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-state">No orders found yet. Create your first bill.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection