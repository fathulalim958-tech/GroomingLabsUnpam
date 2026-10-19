<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Grooming Lab & MUA Studio' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-100 bg-slate-950 min-h-screen pb-24 md:pb-8 antialiased selection:bg-amber-500 selection:text-white">
    <!-- Top Navbar -->
    <header class="sticky top-0 z-40 bg-slate-900/90 backdrop-blur-md border-b border-slate-800/80">
        <div class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('pelanggan.home') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-amber-500 to-yellow-400 flex items-center justify-center text-slate-950 font-black text-sm shadow">
                    GL
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-sm tracking-wide text-white uppercase">GROOMING LAB</span>
                    <span class="text-[9px] uppercase tracking-wider text-amber-400 font-semibold">& MUA</span>
                </div>
            </a>

            <!-- Right info: user info & logout -->
            <div class="flex items-center gap-3">
                <span class="text-xs text-slate-400 hidden sm:inline">Hai, <strong class="text-slate-200">{{ auth()->user()->name }}</strong></span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-xs text-slate-400 hover:text-red-400 px-2.5 py-1.5 rounded-lg border border-slate-800 hover:border-red-900/50 bg-slate-900 transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="hidden sm:inline">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Flash Notifications -->
    <div class="max-w-4xl mx-auto px-4 mt-4">
        @if (session('success'))
            <div class="p-4 mb-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-400 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 mb-4 bg-red-500/10 border border-red-500/30 rounded-xl text-red-400 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="max-w-4xl mx-auto px-4">
        {{ $slot }}
    </main>

    <!-- Mobile Bottom Navigation (4 Menus: Home, Order, Riwayat, Profil) -->
    <nav class="fixed bottom-0 inset-x-0 z-50 bg-slate-900/95 backdrop-blur-lg border-t border-slate-800/80 px-2 py-2">
        <div class="max-w-md mx-auto grid grid-cols-4 gap-1">
            <!-- Home -->
            <a href="{{ route('pelanggan.home') }}"
               class="flex flex-col items-center justify-center py-1.5 px-2 rounded-xl transition {{ request()->routeIs('pelanggan.home') ? 'text-amber-400 bg-amber-500/10 font-semibold' : 'text-slate-400 hover:text-slate-200' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-[11px]">Home</span>
            </a>

            <!-- Order (Antrian Aktif) -->
            <a href="{{ route('pelanggan.order.index') }}"
               class="flex flex-col items-center justify-center py-1.5 px-2 rounded-xl transition relative {{ request()->routeIs('pelanggan.order.*') ? 'text-amber-400 bg-amber-500/10 font-semibold' : 'text-slate-400 hover:text-slate-200' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
                <span class="text-[11px]">Order</span>
            </a>

            <!-- Riwayat -->
            <a href="{{ route('pelanggan.history.index') }}"
               class="flex flex-col items-center justify-center py-1.5 px-2 rounded-xl transition {{ request()->routeIs('pelanggan.history.*') ? 'text-amber-400 bg-amber-500/10 font-semibold' : 'text-slate-400 hover:text-slate-200' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-[11px]">Riwayat</span>
            </a>

            <!-- Profil -->
            <a href="{{ route('pelanggan.profile.edit') }}"
               class="flex flex-col items-center justify-center py-1.5 px-2 rounded-xl transition {{ request()->routeIs('pelanggan.profile.*') ? 'text-amber-400 bg-amber-500/10 font-semibold' : 'text-slate-400 hover:text-slate-200' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-[11px]">Profil</span>
            </a>
        </div>
    </nav>
    @stack('scripts')
</body>
</html>

