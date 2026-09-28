<?php

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
        Route::get('/booking/input', [DashboardController::class, 'bookingInput'])->name('pelanggan.booking.input');
        });
    });

    // Group khusus Kasir
    Route::middleware(['userAkses:kasir'])->group(function () {
        Route::get('/dashboard/kasir', [DashboardController::class, 'kasir']);
    });

    // Group khusus Admin
    Route::middleware(['userAkses:admin'])->group(function () {
        Route::get('/dashboard/admin', [DashboardController::class, 'admin']);
    });

    // Logout
    Route::get('/logout', [SesiController::class, 'logout'])->name('logout');