<?php

use App\Http\Controllers\Admin\CashierController;
use App\Http\Controllers\Admin\CategoryContentController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OperatingHourController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Kasir\CustomerController as KasirCustomerController;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;
use App\Http\Controllers\Kasir\OrderController as KasirOrderController;
use App\Http\Controllers\Kasir\QueueController;
use App\Http\Controllers\Kasir\ReportController as KasirReportController;
use App\Http\Controllers\Kasir\StaffController as KasirStaffController;
use App\Http\Controllers\Pelanggan\BookingController;
use App\Http\Controllers\Pelanggan\HistoryController;
use App\Http\Controllers\Pelanggan\HomeController;
use App\Http\Controllers\Pelanggan\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// FR-AUTH-01: Landing page redirects to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Clear application cache (useful after uploading updates to hosting / cPanel)
Route::get('/clear-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    return response('<h3>Semua Cache Laravel (View, Route, Config, App Cache) berhasil dibersihkan!</h3><p><a href="/pelanggan/booking">Kembali ke Booking</a></p>');
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
    Route::get('/booking/check-slots', [BookingController::class, 'checkSlots'])->name('booking.check-slots');
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
    Route::post('/queue/{booking}/call', [QueueController::class, 'call'])->name('queue.call');
    Route::post('/queue/{booking}/process', [QueueController::class, 'process'])->name('queue.process');
    Route::post('/queue/{booking}/skip', [QueueController::class, 'skip'])->name('queue.skip');
    Route::post('/queue/{booking}/complete', [QueueController::class, 'complete'])->name('queue.complete');
    Route::post('/queue/{booking}/cancel', [QueueController::class, 'cancel'])->name('queue.cancel');

    // Order Menu (Mendatang & Bulanan)
    Route::get('/orders', [KasirOrderController::class, 'index'])->name('orders.index');

    // Riwayat Pelanggan Dilayani Kasir (Pengganti Menu Pekerja)
    Route::get('/staffs', [KasirStaffController::class, 'index'])->name('staffs.index');
    Route::get('/staffs/export-excel', [KasirStaffController::class, 'exportExcel'])->name('staffs.export-excel');

    // Kelola Pelanggan Kasir
    Route::get('/customers', [KasirCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [KasirCustomerController::class, 'show'])->name('customers.show');
    Route::post('/customers/{customer}/toggle-block', [KasirCustomerController::class, 'toggleBlock'])->name('customers.toggle-block');
    Route::post('/customers/{customer}/reset-password', [KasirCustomerController::class, 'resetPassword'])->name('customers.reset-password');

    // Profil Akun Kasir & Data Diri Karyawan (Pengganti Menu Laporan)
    Route::get('/reports', [KasirReportController::class, 'index'])->name('reports.index');
    Route::put('/reports/profile', [KasirReportController::class, 'updateProfile'])->name('reports.update-profile');
    Route::get('/reports/export-excel', [KasirReportController::class, 'exportExcel'])->name('reports.export-excel');
    Route::get('/reports/export-pdf', [KasirReportController::class, 'exportPdf'])->name('reports.export-pdf');

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
    Route::put('/cashiers/{cashier}/schedule', [CashierController::class, 'updateSchedule'])->name('cashiers.schedule');

    // CRUD Customers
    Route::resource('customers', CustomerController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::post('/customers/{customer}/toggle-block', [CustomerController::class, 'toggleBlock'])->name('customers.toggle-block');
    Route::post('/customers/{customer}/reset-password', [CustomerController::class, 'resetPassword'])->name('customers.reset-password');
    Route::post('/customers/{customer}/membership', [CustomerController::class, 'updateMembership'])->name('customers.membership');

    // CRUD Services
    Route::resource('services', ServiceController::class);

    // CRUD Staff (Kapster & MUA)
    Route::resource('staffs', StaffController::class);

    // Operating Hours & Holidays Calendar
    Route::get('/operating-hours', [OperatingHourController::class, 'index'])->name('operating-hours.index');
    Route::put('/operating-hours', [OperatingHourController::class, 'update'])->name('operating-hours.update');
    Route::post('/operating-hours/holidays', [OperatingHourController::class, 'storeHoliday'])->name('operating-hours.holidays.store');
    Route::delete('/operating-hours/holidays/{holiday}', [OperatingHourController::class, 'destroyHoliday'])->name('operating-hours.holidays.destroy');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::delete('/reports/destroy-all', [ReportController::class, 'destroyAll'])->name('reports.destroy-all');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');
    Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');

    // Manage Category Explanation Contents
    Route::get('/category-contents/preview-login', [CategoryContentController::class, 'previewLogin'])->name('category-contents.preview-login');
    Route::resource('category-contents', CategoryContentController::class)->only(['index', 'edit', 'update']);

    // Admin Profile & Account Settings (Ganti Gmail, Username, Password)
    Route::get('/pengaturan-akun', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/pengaturan-akun', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/pengaturan-akun/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');
});

require __DIR__.'/auth.php';
