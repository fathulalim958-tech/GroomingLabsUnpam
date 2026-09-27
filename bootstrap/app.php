<?php

use App\Http\Middleware\UserAkses;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan import Facade Auth

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Alias untuk Middleware kustom
        $middleware->alias([
            'userAkses' => UserAkses::class,
        ]);

        // Pengaturan Redirect Otomatis Middleware Guest & Auth
        $middleware->redirectTo(
            guests: '/',
            users: function () {
                $user = Auth::user(); // <-- Menggunakan Auth::user() menggantikan auth()->user()

                return match ($user?->role) {
                    'admin' => '/dashboard/admin',
                    'kasir' => '/dashboard/kasir',
                    'pelanggan' => '/dashboard/pelanggan',
                    default => '/',
                };
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson()
        );
    })
    ->create();