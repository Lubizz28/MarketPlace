<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Member\AddressController;
use App\Http\Controllers\Member\MemberDashboardController;
use App\Http\Controllers\Reseller\ResellerDashboardController;
use Illuminate\Support\Facades\Route;

// Public Storefront & Landing
Route::get('/', function () {
    return view('welcome');
})->name('home');

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
    });
