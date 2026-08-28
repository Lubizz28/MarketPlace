<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Member\AddressController;
use App\Http\Controllers\Member\MemberDashboardController;
use App\Http\Controllers\Reseller\ResellerDashboardController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Public Storefront Routes
Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/catalog', [StorefrontController::class, 'catalog'])->name('catalog');
Route::get('/category/{slug}', [StorefrontController::class, 'category'])->name('category.show');
Route::get('/product/{slug}', [StorefrontController::class, 'show'])->name('product.show');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Checkout Routes
Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/cities', [\App\Http\Controllers\CheckoutController::class, 'getCities'])->name('checkout.cities');
Route::post('/checkout/shipping', [\App\Http\Controllers\CheckoutController::class, 'calculateShipping'])->name('checkout.shipping');
Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');

// Order Details & Tracking (Public / Protected with policy)
Route::get('/orders/{orderNumber}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
Route::get('/orders/{orderNumber}/invoice', [\App\Http\Controllers\OrderController::class, 'invoice'])->name('orders.invoice');
Route::post('/orders/{orderNumber}/cancel', [\App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');

// Payment Gateway Webhook (CSRF Exempt)
Route::post('/webhook/midtrans', [\App\Http\Controllers\PaymentWebhookController::class, 'handle'])->name('webhook.midtrans');

// Wishlist Public / Member Toggle
Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Member Routes (Member & Admin access)
Route::middleware(['auth', 'role:member|admin'])
    ->prefix('member')
    ->name('member.')
    ->group(function () {
        Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [MemberDashboardController::class, 'profile'])->name('profile');
        Route::put('/profile', [MemberDashboardController::class, 'updateProfile'])->name('profile.update');

        // Orders History
        Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'memberOrders'])->name('orders.index');

        // Wishlist
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');

        // Address Management
        Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
        Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
        Route::patch('/addresses/{address}/primary', [AddressController::class, 'setPrimary'])->name('addresses.primary');
        Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    });

// Reseller Routes (Reseller & Admin access)
Route::middleware(['auth', 'role:reseller|admin'])
    ->prefix('reseller')
    ->name('reseller.')
    ->group(function () {
        Route::get('/dashboard', [ResellerDashboardController::class, 'index'])->name('dashboard');
    });

// Admin Routes (Admin ONLY)
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('users.index');

        // Order Management & Lifecycle
        Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{orderNumber}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{orderNumber}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.status');
        Route::post('/orders/{orderNumber}/shipment', [\App\Http\Controllers\Admin\OrderController::class, 'fulfillShipment'])->name('orders.shipment');

        // Payment Transactions Audit Log
        Route::get('/payments', [\App\Http\Controllers\Admin\PaymentTransactionController::class, 'index'])->name('payments.index');
    });
