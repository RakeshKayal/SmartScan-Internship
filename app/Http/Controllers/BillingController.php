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
        if (!$request->walk_in_name || !$request->walk_in_phone) {
            return redirect()->route('billing.index')->with('error', 'For walk-in customer, name and phone number are required.');
        }

        // Check if customer already exists by phone
        $existingCustomer = Customer::where('customer_phone', $request->walk_in_phone)->first();

        if ($existingCustomer) {
            $customerId = $existingCustomer->id;
        } else {
            $newCustomer = Customer::create([
                'customer_name'  => $request->walk_in_name,
                'customer_phone' => $request->walk_in_phone,
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
}