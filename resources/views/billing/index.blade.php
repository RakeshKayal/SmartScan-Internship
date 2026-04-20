@extends('layouts.app')

@section('title', 'Billing | POS System')
@section('page-heading', 'Billing')

@section('content')
    @if(session('success'))
        <div class="alert-box success-alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-box error-alert">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-box error-alert">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $cartTotal = collect($cart)->sum('line_total');
        $totalItems = collect($cart)->sum('quantity');
    @endphp

    <div class="billing-page-layout">
        {{-- LEFT PANEL --}}
        <div class="billing-panel">
            <div class="form-card">
                <div class="form-card-header">
                    <h2>Customer & Scan</h2>
                    <p>Select saved customer or capture walk-in customer details.</p>
                </div>

                {{-- Generate Bill Form --}}
                <form action="{{ route('billing.generateBill') }}" method="POST" id="generateBillForm" class="custom-form">
                    @csrf

                    <div class="form-group">
                        <label for="customer_type">Customer Type</label>
                        <select name="customer_type" id="customer_type" required>
                            <option value="walk_in" {{ ($billingCustomer['customer_type'] ?? 'walk_in') === 'walk_in' ? 'selected' : '' }}>
                                Walk-in Customer
                            </option>
                            <option value="registered" {{ ($billingCustomer['customer_type'] ?? '') === 'registered' ? 'selected' : '' }}>
                                Registered Customer
                            </option>
                        </select>
                    </div>

                    {{-- REGISTERED CUSTOMER --}}
                    <div id="registeredCustomerWrapper" style="display: none;">
                        <div class="form-group">
                            <label for="customer_id">Select Customer</label>
                            <select name="customer_id" id="customer_id">
                                <option value="">-- Select Customer --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ (string)($billingCustomer['customer_id'] ?? '') === (string)$customer->id ? 'selected' : '' }}>
                                        {{ $customer->customer_name }} ({{ $customer->customer_phone }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- WALK-IN CUSTOMER DETAILS --}}
                    <div id="walkInCustomerWrapper">
                        <div class="form-group">
                            <label for="walk_in_name">Customer Name</label>
                            <input type="text"
                                   name="walk_in_name"
                                   id="walk_in_name"
                                   placeholder="Enter customer name"
                                   value="{{ $billingCustomer['walk_in_name'] ?? '' }}">
                        </div>

                        <div class="form-group">
                            <label for="walk_in_phone">Phone Number</label>
                            <input type="text"
                                   name="walk_in_phone"
                                   id="walk_in_phone"
                                   placeholder="Enter customer phone number"
                                   value="{{ $billingCustomer['walk_in_phone'] ?? '' }}">
                        </div>
                    </div>
                </form>

                <div class="billing-divider"></div>

                {{-- Barcode Form --}}
                <form action="{{ route('billing.addToCart') }}" method="POST" class="custom-form">
                    @csrf

                    <div class="form-group">
                        <label for="barcode">Barcode</label>
                        <input type="text" name="barcode" id="barcode" placeholder="Scan or enter barcode..." autofocus required>
                    </div>

                    <div class="form-actions billing-form-actions">
                        <button type="submit" class="primary-action-btn full-width-btn">Add Product</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- RIGHT PANEL --}}
        <div class="billing-cart-section">
            <div class="table-card">
                <div class="table-card-header">
                    <div>
                        <h2>Billing Cart</h2>
                        <p>Review scanned products before generating the bill.</p>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="custom-table billing-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Barcode</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cart as $item)
                                <tr>
                                    <td>{{ $item['product_name'] }}</td>
                                    <td>{{ $item['barcode'] }}</td>
                                    <td>₹{{ number_format($item['price'], 2) }}</td>
                                    <td>
                                        <div class="qty-controls">
                                            <form action="{{ route('billing.decreaseQty', $item['product_id']) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="qty-btn">-</button>
                                            </form>

                                            <span class="qty-value">{{ $item['quantity'] }}</span>

                                            <form action="{{ route('billing.increaseQty', $item['product_id']) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="qty-btn">+</button>
                                            </form>
                                        </div>
                                    </td>
                                    <td>
                                        <form action="{{ route('billing.removeItem', $item['product_id']) }}" method="POST" onsubmit="return confirm('Remove this item from cart?')">
                                            @csrf
                                            <button type="submit" class="delete-btn">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="empty-state">Cart is empty. Scan products to start billing.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="billing-summary-grid">
                    <div class="summary-card">
                        <h3>Total Items</h3>
                        <p>{{ $totalItems }}</p>
                    </div>

                    <div class="summary-card">
                        <h3>Grand Total</h3>
                        <p>₹{{ number_format($cartTotal, 2) }}</p>
                    </div>
                </div>

                <div class="billing-generate-btn-wrap">
                    <button type="button" id="openBillPreviewBtn" class="primary-action-btn generate-btn">Generate Bill</button>
                </div>
            </div>
        </div>
    </div>

    {{-- BILL PREVIEW MODAL --}}
    <div id="billPreviewModal" class="bill-modal-overlay">
        <div class="bill-modal-card">
            <div class="bill-modal-header">
                <div>
                    <h2>Bill Preview</h2>
                    <p>Review all items and payable amount before payment.</p>
                </div>
                <button type="button" id="closeBillPreviewBtn" class="bill-modal-close-btn">&times;</button>
            </div>

            <div class="bill-modal-body">
                <div class="bill-preview-top">
                    <div class="bill-preview-customer">
                        <h3>Customer Details</h3>
                        <p id="previewCustomerType"><strong>Type:</strong> -</p>
                        <p id="previewCustomerName"><strong>Name:</strong> -</p>
                        <p id="previewCustomerPhone"><strong>Phone:</strong> -</p>
                    </div>

                    <div class="bill-preview-summary-box">
                        <h3>Bill Summary</h3>
                        <p><strong>Total Items:</strong> {{ $totalItems }}</p>
                        <p><strong>Gross Total:</strong> ₹{{ number_format($cartTotal, 2) }}</p>
                        <p class="payable-amount"><strong>Total Payable:</strong> ₹{{ number_format($cartTotal, 2) }}</p>
                    </div>
                </div>

                <div class="table-wrapper" style="margin-top: 20px;">
                    <table class="custom-table bill-preview-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Barcode</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Line Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cart as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item['product_name'] }}</td>
                                    <td>{{ $item['barcode'] }}</td>
                                    <td>₹{{ number_format($item['price'], 2) }}</td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td>₹{{ number_format($item['line_total'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="empty-state">Cart is empty. Add products before generating bill.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bill-modal-footer">
                <button type="button" id="cancelBillPreviewBtn" class="secondary-action-btn">Close</button>
                <button type="submit" form="generateBillForm" class="primary-action-btn pay-btn">Pay</button>
            </div>
        </div>
    </div>

    <script>
        const customerType = document.getElementById('customer_type');
        const registeredCustomerWrapper = document.getElementById('registeredCustomerWrapper');
        const walkInCustomerWrapper = document.getElementById('walkInCustomerWrapper');
        const customerId = document.getElementById('customer_id');
        const walkInName = document.getElementById('walk_in_name');
        const walkInPhone = document.getElementById('walk_in_phone');

        const openBillPreviewBtn = document.getElementById('openBillPreviewBtn');
        const billPreviewModal = document.getElementById('billPreviewModal');
        const closeBillPreviewBtn = document.getElementById('closeBillPreviewBtn');
        const cancelBillPreviewBtn = document.getElementById('cancelBillPreviewBtn');

        const previewCustomerType = document.getElementById('previewCustomerType');
        const previewCustomerName = document.getElementById('previewCustomerName');
        const previewCustomerPhone = document.getElementById('previewCustomerPhone');

        const cartTotal = {{ $cartTotal }};
        const totalItems = {{ $totalItems }};

        function toggleCustomerFields() {
            if (customerType.value === 'registered') {
                registeredCustomerWrapper.style.display = 'block';
                walkInCustomerWrapper.style.display = 'none';
            } else {
                registeredCustomerWrapper.style.display = 'none';
                walkInCustomerWrapper.style.display = 'block';
            }
        }

        function saveCustomerInfo() {
            fetch("{{ route('billing.saveCustomerInfo') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    customer_type: customerType ? customerType.value : 'walk_in',
                    customer_id: customerId ? customerId.value : '',
                    walk_in_name: walkInName ? walkInName.value : '',
                    walk_in_phone: walkInPhone ? walkInPhone.value : ''
                })
            }).catch(error => {
                console.error('Error saving billing customer info:', error);
            });
        }

        let saveTimeout;

        function debounceSaveCustomerInfo() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                saveCustomerInfo();
            }, 300);
        }

        function validateBeforePreview() {
            if (totalItems <= 0) {
                alert('Cart is empty. Please add products first.');
                return false;
            }

            if (customerType.value === 'registered') {
                if (!customerId.value) {
                    alert('Please select a registered customer.');
                    return false;
                }

                const selectedText = customerId.options[customerId.selectedIndex].text;
                previewCustomerType.innerHTML = '<strong>Type:</strong> Registered Customer';
                previewCustomerName.innerHTML = '<strong>Customer:</strong> ' + selectedText;
                previewCustomerPhone.innerHTML = '<strong>Phone:</strong> Saved in selected customer';
            } else {
                if (!walkInName.value.trim() || !walkInPhone.value.trim()) {
                    alert('For walk-in customer, please enter name and phone number.');
                    return false;
                }

                previewCustomerType.innerHTML = '<strong>Type:</strong> Walk-in Customer';
                previewCustomerName.innerHTML = '<strong>Name:</strong> ' + walkInName.value;
                previewCustomerPhone.innerHTML = '<strong>Phone:</strong> ' + walkInPhone.value;
            }

            return true;
        }

        function openBillPreview() {
            if (!validateBeforePreview()) return;
            billPreviewModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeBillPreview() {
            billPreviewModal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        customerType.addEventListener('change', function () {
            toggleCustomerFields();
            saveCustomerInfo();
        });

        if (customerId) {
            customerId.addEventListener('change', saveCustomerInfo);
        }

        if (walkInName) {
            walkInName.addEventListener('input', debounceSaveCustomerInfo);
        }

        if (walkInPhone) {
            walkInPhone.addEventListener('input', debounceSaveCustomerInfo);
        }

        openBillPreviewBtn.addEventListener('click', openBillPreview);
        closeBillPreviewBtn.addEventListener('click', closeBillPreview);
        cancelBillPreviewBtn.addEventListener('click', closeBillPreview);

        billPreviewModal.addEventListener('click', function(e) {
            if (e.target === billPreviewModal) {
                closeBillPreview();
            }
        });

        toggleCustomerFields();
    </script>
@endsection