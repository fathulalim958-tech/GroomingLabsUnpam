<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Pelanggan\HomeController;
use App\Http\Controllers\Pelanggan\BookingController;
use App\Http\Controllers\Pelanggan\OrderController;
use App\Http\Controllers\Pelanggan\HistoryController;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;
use App\Http\Controllers\Kasir\QueueController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CashierController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\OperatingHourController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CategoryContentController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// FR-AUTH-01: Landing page redirects to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Category Explanation Page (Public & Customer - DOES NOT force login)
Route::get('/kategori/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Role-based dashboard redirect
Route::get('/dashboard', function () {
    $user = request()->user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'kasir') {
        return redirect()->route('kasir.dashboard');
    }
    return redirect()->route('pelanggan.home');
})->middleware(['auth'])->name('dashboard');

// Common profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================================
// PELANGGAN (Customer) Routes
// ============================================================
Route::middleware(['auth', 'role:pelanggan'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Booking Flow
    Route::get('/booking', [BookingController::class, 'selectCategory'])->name('booking.category');
    Route::get('/booking/{category}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

    // Active Order / Queue Tracking
    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    Route::get('/order/check-queue', [OrderController::class, 'checkQueue'])->name('order.check-queue');
    Route::post('/order/{booking}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');

    // History
    Route::get('/riwayat', [HistoryController::class, 'index'])->name('history.index');

    // Profile
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profil', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================================
// KASIR (Cashier) Routes
// ============================================================
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/orders', [KasirDashboardController::class, 'getOrders'])->name('dashboard.orders');

    // Queue Control
    Route::post('/queue/{booking}/process', [QueueController::class, 'process'])->name('queue.process');
    Route::post('/queue/{booking}/skip', [QueueController::class, 'skip'])->name('queue.skip');
    Route::post('/queue/{booking}/complete', [QueueController::class, 'complete'])->name('queue.complete');
    Route::post('/queue/{booking}/cancel', [QueueController::class, 'cancel'])->name('queue.cancel');

    // Walk-in Order
    Route::get('/walk-in', [KasirDashboardController::class, 'walkInForm'])->name('walkin.create');
    Route::post('/walk-in', [KasirDashboardController::class, 'walkInStore'])->name('walkin.store');
});

// ============================================================
// ADMIN Routes
// ============================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD Kasir (Cashiers)
    Route::resource('cashiers', CashierController::class);
    Route::post('/cashiers/{cashier}/toggle-active', [CashierController::class, 'toggleActive'])->name('cashiers.toggle-active');

    // CRUD Customers
    Route::resource('customers', CustomerController::class)->only(['index', 'show', 'destroy']);
    Route::post('/customers/{customer}/toggle-block', [CustomerController::class, 'toggleBlock'])->name('customers.toggle-block');
    Route::post('/customers/{customer}/reset-password', [CustomerController::class, 'resetPassword'])->name('customers.reset-password');

    // CRUD Services
    Route::resource('services', ServiceController::class);

    // CRUD Staff (Kapster & MUA)
    Route::resource('staffs', StaffController::class);

    // Operating Hours
    Route::get('/operating-hours', [OperatingHourController::class, 'index'])->name('operating-hours.index');
    Route::put('/operating-hours', [OperatingHourController::class, 'update'])->name('operating-hours.update');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');
    Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');

    // Manage Category Explanation Contents
    Route::resource('category-contents', CategoryContentController::class)->only(['index', 'edit', 'update']);
});

require __DIR__.'/auth.php';
