<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SesiController;
use Illuminate\Support\Facades\Route;

Route::get('/Register', [SesiController::class, 'Register'])->name('Register');
Route::post('/Register', [SesiController::class, 'createAccount'])->name('Register.post');

// Guest Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/', [SesiController::class, 'index'])->name('login');
    Route::post('/', [SesiController::class, 'login']);
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Group khusus Pelanggan
    Route::middleware(['userAkses:pelanggan'])->prefix('dashboard/pelanggan')->group(function () {
        Route::get('/', [DashboardController::class, 'pelanggan']);
        Route::get('/Barbershop', [DashboardController::class, 'Barbershop']);
        Route::get('/MUA', [DashboardController::class, 'MUA']);
        Route::get('/booking', [DashboardController::class, 'booking']);
        Route::get('/riwayat', [DashboardController::class, 'riwayat']);
        Route::get('/order', [DashboardController::class, 'order'])->name('pelanggan.order');
        Route::get('/profile', [DashboardController::class, 'profile'])->name('pelanggan.profile');
        Route::put('/profile/password', [DashboardController::class, 'updatePassword'])->name('pelanggan.password.update');
        Route::get('/booking/input', [DashboardController::class, 'bookingInput'])->name('pelanggan.booking.input');
        Route::post('/booking/store', [DashboardController::class, 'storeBooking'])->name('pelanggan.booking.store');
        Route::patch('/booking/{booking}/cancel', [DashboardController::class, 'cancelBooking'])->name('pelanggan.booking.cancel');
    });
});

// Group khusus Kasir
Route::middleware(['userAkses:kasir'])->group(function () {
    Route::get('/dashboard/kasir', [DashboardController::class, 'kasir'])->name('kasir.dashboard');
    Route::get('/dashboard/kasir/booking-walkin', [DashboardController::class, 'bookingWalkin'])->name('kasir.booking.walkin');
    Route::post('/dashboard/kasir/booking-walkin', [DashboardController::class, 'storeWalkin'])->name('kasir.booking.walkin.store');
    Route::patch('/dashboard/kasir/bookings/{booking}/complete', [DashboardController::class, 'completeBooking'])->name('kasir.booking.complete');
});

// Group khusus Admin
Route::middleware(['userAkses:admin'])->group(function () {
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::get('/dashboard/admin/kelola-karyawan', [AdminController::class, 'employees'])->name('admin.karyawan');
    Route::post('/dashboard/admin/kelola-karyawan', [AdminController::class, 'storeEmployee'])->name('admin.karyawan.store');
    Route::put('/dashboard/admin/kelola-karyawan/{employee}', [AdminController::class, 'updateEmployee'])->name('admin.karyawan.update');
    Route::delete('/dashboard/admin/kelola-karyawan/{employee}', [AdminController::class, 'destroyEmployee'])->name('admin.karyawan.destroy');
    Route::get('/dashboard/admin/laporan-pendapatan', [DashboardController::class, 'laporanPendapatan'])->name('admin.laporan');
    Route::get('/dashboard/admin/laporan-pendapatan/export', [DashboardController::class, 'exportLaporanPendapatan'])->name('admin.laporan.export');
});

// Logout
Route::get('/logout', [SesiController::class, 'logout'])->name('logout');
