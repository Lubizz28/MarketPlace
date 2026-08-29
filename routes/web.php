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
Route::post('/checkout/coupon/validate', [\App\Http\Controllers\CheckoutController::class, 'validateCoupon'])->name('checkout.coupon.validate');
Route::post('/checkout/points/calculate', [\App\Http\Controllers\CheckoutController::class, 'calculatePoints'])->name('checkout.points.calculate');
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

        // Loyalty Points & Coupons
        Route::get('/points', [MemberDashboardController::class, 'points'])->name('points.index');
        Route::get('/coupons', [MemberDashboardController::class, 'coupons'])->name('coupons.index');

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
        Route::get('/commissions', [ResellerDashboardController::class, 'commissions'])->name('commissions.index');
        Route::get('/wallet', [ResellerDashboardController::class, 'wallet'])->name('wallet.index');
        Route::get('/withdrawals', [ResellerDashboardController::class, 'withdrawals'])->name('withdrawals.index');
        Route::post('/withdrawals', [ResellerDashboardController::class, 'storeWithdrawal'])->name('withdrawals.store');
        Route::get('/profile', [ResellerDashboardController::class, 'profile'])->name('profile');
        Route::post('/profile', [ResellerDashboardController::class, 'updateProfile'])->name('profile.update');
    });

// Admin Routes (Admin ONLY)
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('users.index');

        // Executive Analytics & Reporting
        Route::get('/analytics', [\App\Http\Controllers\Admin\AdminAnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/analytics/export/orders', [\App\Http\Controllers\Admin\AdminAnalyticsController::class, 'exportOrders'])->name('analytics.export.orders');

        // Promotional Broadcast Campaigns
        Route::get('/broadcasts', [\App\Http\Controllers\Admin\AdminBroadcastController::class, 'index'])->name('broadcasts.index');
        Route::get('/broadcasts/create', [\App\Http\Controllers\Admin\AdminBroadcastController::class, 'create'])->name('broadcasts.create');
        Route::post('/broadcasts', [\App\Http\Controllers\Admin\AdminBroadcastController::class, 'store'])->name('broadcasts.store')->middleware('throttle:10,1');

        // Reseller Management & Approvals
        Route::get('/resellers', [\App\Http\Controllers\Admin\AdminResellerController::class, 'index'])->name('resellers.index');
        Route::get('/resellers/{reseller}', [\App\Http\Controllers\Admin\AdminResellerController::class, 'show'])->name('resellers.show');
        Route::post('/resellers/{reseller}/verify', [\App\Http\Controllers\Admin\AdminResellerController::class, 'verify'])->name('resellers.verify');
        Route::post('/resellers/{reseller}/reject', [\App\Http\Controllers\Admin\AdminResellerController::class, 'reject'])->name('resellers.reject');

        // Reseller Withdrawals Management
        Route::get('/withdrawals', [\App\Http\Controllers\Admin\AdminResellerController::class, 'withdrawals'])->name('withdrawals.index');
        Route::post('/withdrawals/{withdrawal}/process', [\App\Http\Controllers\Admin\AdminResellerController::class, 'processWithdrawal'])->name('withdrawals.process');

        // Order Management & Lifecycle
        Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{orderNumber}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{orderNumber}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.status');
        Route::post('/orders/{orderNumber}/shipment', [\App\Http\Controllers\Admin\OrderController::class, 'fulfillShipment'])->name('orders.shipment');

        // Coupon & Promotion Management
        Route::resource('/coupons', \App\Http\Controllers\Admin\CouponController::class)->except(['show']);
        Route::post('/coupons/{coupon}/toggle', [\App\Http\Controllers\Admin\CouponController::class, 'toggle'])->name('coupons.toggle');

        // Loyalty Points Audit & Adjustments
        Route::get('/points', [\App\Http\Controllers\Admin\PointTransactionController::class, 'index'])->name('points.index');
        Route::post('/points/adjust', [\App\Http\Controllers\Admin\PointTransactionController::class, 'adjust'])->name('points.adjust');

        // Payment Transactions Audit Log
        Route::get('/payments', [\App\Http\Controllers\Admin\PaymentTransactionController::class, 'index'])->name('payments.index');
    });
