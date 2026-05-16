<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    /**
     * Show Billing Page
     */
    public function index(){
        $customers = Customer::latest()->get();
        $cart = session()->get('cart', []);
        $billingCustomer = session()->get('billing_customer', [
            'customer_type' => 'walk_in',
            'customer_id' => '',
            'walk_in_name' => '',
            'walk_in_phone' => '',
            'walk_in_email' => '',
        ]);

        return view('billing.index', compact('customers', 'cart', 'billingCustomer'));
    }
    /**
 * Save temporary billing customer info in session
 */
    public function saveCustomerInfo(Request $request) {
        session()->put('billing_customer', [
            'customer_type' => $request->customer_type ?? 'walk_in',
            'customer_id'   => $request->customer_id ?? '',
            'walk_in_name'  => $request->walk_in_name ?? '',
            'walk_in_phone' => $request->walk_in_phone ?? '',
            'walk_in_email' => $request->walk_in_email ?? '',
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Add Product to Cart by Barcode
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        $product = Product::where('barcode', $request->barcode)->first();

        if (!$product) {
            return redirect()->route('billing.index')->with('error', 'Product not found for this barcode.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += 1;
            $cart[$product->id]['line_total'] = $cart[$product->id]['price'] * $cart[$product->id]['quantity'];
        } else {
            $cart[$product->id] = [
                'product_id'   => $product->id,
                'product_name' => $product->product_name,
                'barcode'      => $product->barcode,
                'price'        => $product->price,
                'quantity'     => 1,
                'line_total'   => $product->price,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('billing.index')->with('success', 'Product added to cart!');
    }

    /**
     * Increase Quantity
     */
    public function increaseQty($productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += 1;
            $cart[$productId]['line_total'] = $cart[$productId]['price'] * $cart[$productId]['quantity'];

            session()->put('cart', $cart);
        }

        return redirect()->route('billing.index');
    }

    /**
     * Decrease Quantity
     */
    public function decreaseQty($productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            if ($cart[$productId]['quantity'] > 1) {
                $cart[$productId]['quantity'] -= 1;
                $cart[$productId]['line_total'] = $cart[$productId]['price'] * $cart[$productId]['quantity'];
            } else {
                unset($cart[$productId]);
            }

            session()->put('cart', $cart);
        }

        return redirect()->route('billing.index');
    }

    /**
     * Remove Product from Cart
     */
    public function removeItem($productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return redirect()->route('billing.index')->with('success', 'Product removed from cart.');
    }

    /**
 * Clear temporary billing customer info from session
 */
    public function clearCustomerInfo(){
        session()->forget('billing_customer');

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Generate Bill / Save Order
     */
    public function generateBill(Request $request){
    $request->validate([
        'customer_type'  => 'required|in:walk_in,registered',
        'customer_id'    => 'nullable|exists:customers,id',
        'walk_in_name'   => 'nullable|string|max:255',
        'walk_in_phone'  => 'nullable|string|max:20',
        'walk_in_email'  => 'nullable|string|email|max:255',
    ]);

    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return redirect()->route('billing.index')->with('error', 'Cart is empty. Add products first.');
    }

    $customerId = null;

    // Registered customer flow
    if ($request->customer_type === 'registered') {
        if (!$request->customer_id) {
            return redirect()->route('billing.index')->with('error', 'Please select a registered customer.');
        }

        $customerId = $request->customer_id;
    }

    // Walk-in customer flow
    if ($request->customer_type === 'walk_in') {
        if (!$request->walk_in_name || !$request->walk_in_phone || !$request->walk_in_email) {
            return redirect()->route('billing.index')->with('error', 'For walk-in customer, name, phone number, and email are required.');
        }

        // Check if customer already exists by phone
        $existingCustomer = Customer::where('customer_phone', $request->walk_in_phone)->first();

        if ($existingCustomer) {
            $customerId = $existingCustomer->id;
        } else {
            $newCustomer = Customer::create([
                'customer_name'  => $request->walk_in_name,
                'customer_phone' => $request->walk_in_phone,
                'customer_email' => $request->walk_in_email,
            ]);

            $customerId = $newCustomer->id;
        }
    }

    DB::beginTransaction();

    try {
        $totalAmount = collect($cart)->sum('line_total');

        $order = Order::create([
            'order_number'  => 'ORD-' . now()->format('YmdHis'),
            'customer_id'   => $customerId,
            'customer_type' => $request->customer_type,
            'user_id'       => Auth::id(),
            'total_amount'  => $totalAmount,
            'status'        => 'pending',
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $item['product_id'],
                'barcode'      => $item['barcode'],
                'product_name' => $item['product_name'],
                'price'        => $item['price'],
                'quantity'     => $item['quantity'],
                'line_total'   => $item['line_total'],
            ]);
        }

        session()->forget('cart');
        session()->forget('billing_customer');

        DB::commit();

        return redirect()->route('orders.index')->with('success', 'Bill generated successfully! Order Number: ' . $order->order_number);
    } catch (\Exception $e) {
        DB::rollBack();

        return redirect()->route('billing.index')->with('error', 'Something went wrong while generating the bill.');
    }
}

    /**
     * Order History Page
     */
    public function orders()
    {
        $orders = Order::with(['customer', 'user'])->latest()->get();

        return view('orders.index', compact('orders'));
    }


  public function printBill(Request $request)
{
    $cart = session()->get('cart', []);
    $billingCustomer = session()->get('billing_customer', []);

    // Resolve customer name & phone based on type
    if (($billingCustomer['customer_type'] ?? 'walk_in') === 'registered') {
        $customer = \App\Models\Customer::find($billingCustomer['customer_id']);
        $customerName  = $customer?->customer_name ?? 'Unknown';
        $customerPhone = $customer?->customer_phone ?? '-';
    } else {
        $customerName  = $billingCustomer['walk_in_name'] ?? 'Walk-in Customer';
        $customerPhone = $billingCustomer['walk_in_phone'] ?? '-';
    }

    $totalAmount = collect($cart)->sum('line_total'); // ✅ matches cart key

    return view('billing.print', [
        'items'         => $cart,
        'customerName'  => $customerName,
        'customerPhone' => $customerPhone,
        'totalAmount'   => $totalAmount,
    ]);
}


public function sendEmail(Request $request)
{
    $billingCustomer = session('billing_customer', []);
    $cart            = session('cart', []);

    if (empty($cart)) {
        return back()->with('error', 'Cart is empty. Nothing to email.');
    }

    if (($billingCustomer['customer_type'] ?? 'walk_in') === 'registered') {
        $customer = \App\Models\Customer::find($billingCustomer['customer_id']);

        if (!$customer) {
            return back()->with('error', 'Selected customer not found.');
        }

        $customerData = [
            'name'    => $customer->customer_name,
            'email'   => $customer->customer_email,
            'phone'   => $customer->customer_phone,
            'address' => $customer->customer_address ?? '',
        ];
    } else {
        $email = $billingCustomer['walk_in_email'] ?? '';

        if (empty($email)) {
            return back()->with('error', 'No customer email found.');
        }

        $customerData = [
            'name'    => $billingCustomer['walk_in_name']  ?? 'Customer',
            'email'   => $email,
            'phone'   => $billingCustomer['walk_in_phone'] ?? '',
            'address' => '',
        ];
    }

    $items = collect($cart)->map(fn($item) => [
        'name'       => $item['product_name'],
        'sku'        => $item['barcode'] ?? '',
        'quantity'   => $item['quantity'],
        'unit_price' => $item['price'],
    ])->toArray();

    $company = [
        'name'       => config('app.name', 'Your Store'),
        'tagline'    => 'Retail · POS System',
        'legal_name' => config('app.name', 'Your Store') . ' Pvt. Ltd.',
        'gst'        => '',
        'address'    => 'Imagine Tech Park, Kochi, Kerala',
        'email'      => config('mail.from.address'),
    ];

    $invoice = [
        'number'         => 'INV-' . strtoupper(uniqid()),
        'status'         => 'paid',
        'issue_date'     => now()->toDateString(),
        'due_date'       => now()->toDateString(),
        'currency'       => '₹',
        'tax_rate'       => 0,
        'discount_rate'  => 0,
        'shipping'       => 0,
        'payment_method' => 'Cash',
        'notes'          => '',
    ];

    // ── DEBUG: dump what we're about to send ──
    \Illuminate\Support\Facades\Log::info('Attempting to send bill email', [
        'to'    => $customerData['email'],
        'items' => count($items),
    ]);

    try {
        \Illuminate\Support\Facades\Mail::to($customerData['email'])
            ->send(new \App\Mail\BillMail($company, $customerData, $invoice, $items));

        \Illuminate\Support\Facades\Log::info('Bill email sent successfully to ' . $customerData['email']);

        return back()->with('success', 'Bill sent to ' . $customerData['email'] . ' successfully.');

    } catch (\Throwable $e) {

    dd(
        $e->getMessage(),
        $e->getFile(),
        $e->getLine(),
        $e->getTraceAsString()
    );

}
}

}