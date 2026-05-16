<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BillingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Public Home Page
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home.index');
})->name('home');

/*
|--------------------------------------------------------------------------
| Guest Routes (Only for not logged-in users)
|--------------------------------------------------------------------------
*/
Route::middleware('check.guest')->group(function () {
    // Register
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    

Route::get('/test-mail', function () {

    Mail::raw('Test Email Working', function ($message) {

        $message->to('rr6611397@gmail.com')
                ->subject('Test Mail');

    });

    return 'Mail Sent';
});
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Only for logged-in users)
|--------------------------------------------------------------------------
*/
Route::middleware('check.auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Product Routes
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Customer Routes
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Billing Routes
    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::post('/billing/save-customer-info', [BillingController::class, 'saveCustomerInfo'])->name('billing.saveCustomerInfo');
    Route::post('/billing/clear-customer-info', [BillingController::class, 'clearCustomerInfo'])->name('billing.clearCustomerInfo');

    Route::post('/billing/add-to-cart', [BillingController::class, 'addToCart'])->name('billing.addToCart');
    Route::post('/billing/increase/{productId}', [BillingController::class, 'increaseQty'])->name('billing.increaseQty');
    Route::post('/billing/decrease/{productId}', [BillingController::class, 'decreaseQty'])->name('billing.decreaseQty');
    Route::post('/billing/remove/{productId}', [BillingController::class, 'removeItem'])->name('billing.removeItem');
    Route::post('/billing/generate', [BillingController::class, 'generateBill'])->name('billing.generateBill');
    Route::post('/billing/print', [BillingController::class, 'printBill'])->name('billing.printBill');
    Route::post('/billing/send-email', [BillingController::class, 'sendEmail'])->name('billing.sendEmail');

    // Order History
    Route::get('/orders', [BillingController::class, 'orders'])->name('orders.index');
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


// User Management (Admin Only)
Route::middleware('check.admin')->group(function () {
    Route::get('/users', [AuthController::class, 'userList'])->name('users.index');
    Route::get('/users/create', [AuthController::class, 'showCreateUser'])->name('users.create');
    Route::post('/users', [AuthController::class, 'createUser'])->name('users.store');
    Route::delete('/users/{id}', [AuthController::class, 'deleteUser'])->name('users.destroy');
    // routes/web.php
Route::post('/users/send-otp', [AuthController::class, 'sendOtp'])->name('users.sendOtp');
Route::post('/users/verify-otp', [AuthController::class, 'verifyOtp'])->name('users.verifyOtp');
});