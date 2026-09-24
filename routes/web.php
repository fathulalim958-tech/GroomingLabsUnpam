<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\sesiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::get('/', [sesiController::class, 'index'])->name('login');
    Route::post('/', [sesiController::class, 'login'])->name('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/pelanggan', [DashboardController::class, 'pelanggan'])
        ->middleware('userAkses:pelanggan');
    Route::get('/dashboard/pelanggan/Barbershop', [DashboardController::class, 'Barbershop'])
        ->middleware('userAkses:pelanggan');
    Route::get('/dashboard/pelanggan/MUA', [DashboardController::class, 'MUA'])
        ->middleware('userAkses:pelanggan');
    Route::get('/dashboard/pelanggan/booking', [DashboardController::class, 'Booking'])
        ->middleware('userAkses:pelanggan');
    Route::get('/dashboard/kasir', [DashboardController::class, 'kasir'])
        ->middleware('userAkses:kasir');
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])
        ->middleware('userAkses:admin');
    Route::get('/logout', [sesiController::class, 'logout'])->name('logout');
});
