<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Kasir Front-Desk - Grooming Lab & MUA' }}</title>

    <!-- Anti-FOUC Theme Switcher Script -->
    <script>
        if (localStorage.theme === 'light') {
            document.documentElement.classList.remove('dark');
            document.documentElement.classList.add('light');
        } else {
            document.documentElement.classList.add('dark');
            document.documentElement.classList.remove('light');
        }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900|plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ showLogoutModal: false }" class="font-['Inter',sans-serif] text-slate-900 bg-slate-100 dark:bg-slate-900 dark:text-slate-100 min-h-screen antialiased selection:bg-blue-500 selection:text-white transition-colors duration-200 pb-28">
    <!-- Kasir Header Bar -->
    <header class="sticky top-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 py-2.5 sm:py-3 flex items-center justify-between gap-2">
            <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                <a href="{{ route('kasir.dashboard') }}" class="flex items-center gap-2 min-w-0">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black text-xs sm:text-sm shadow-md shadow-blue-500/30 flex-shrink-0">
                        GL
                    </div>
                    <div class="min-w-0">
                        <span class="font-black text-xs sm:text-sm tracking-wide text-slate-900 dark:text-white uppercase block leading-tight truncate">GROOMING LAB</span>
                        <span class="text-[9px] sm:text-[10px] uppercase tracking-wider text-blue-600 dark:text-blue-400 font-bold block truncate">KASIR & ANTRIAN</span>
                    </div>
                </a>

                <span class="hidden lg:inline-block px-2.5 py-0.5 rounded-full bg-blue-500/20 border border-blue-400/40 text-blue-600 dark:text-blue-300 text-xs font-semibold">
                    Front-Desk Mode
                </span>
            </div>

            <!-- Header actions: Walk-in button, Theme Toggle, user badge, logout -->
            <div class="flex items-center gap-1.5 sm:gap-3 flex-shrink-0">
                <a href="{{ route('kasir.walkin.create') }}"
                   class="inline-flex items-center gap-1 sm:gap-1.5 px-2.5 sm:px-4 py-1.5 sm:py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-blue-600/30 transition transform active:scale-95 whitespace-nowrap">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>+ <span class="hidden sm:inline">Order </span>Walk-In</span>
                </a>

                <x-theme-toggle />

                <div class="h-6 w-px bg-slate-200 dark:bg-slate-800 hidden md:block"></div>

                <div class="text-right hidden md:block">
                    <span class="block text-xs font-bold text-slate-800 dark:text-slate-100">{{ auth()->user()->name }}</span>
                    <span class="block text-[10px] text-slate-500 dark:text-slate-300 font-mono">{{ now()->translatedFormat('l, d M Y') }}</span>
                </div>

                <button type="button"
                        @click="showLogoutModal = true"
                        title="Keluar"
                        class="p-1.5 sm:p-2 text-slate-600 dark:text-slate-300 hover:text-red-600 dark:hover:text-red-400 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-red-300 dark:hover:border-red-800 bg-slate-100 dark:bg-slate-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Flash Notifications -->
    <div class="max-w-7xl mx-auto px-3 sm:px-6 mt-3 sm:mt-4">
        @if (session('success'))
            <div class="p-3 sm:p-4 mb-3 sm:mb-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl sm:rounded-2xl text-emerald-600 dark:text-emerald-400 text-xs sm:text-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-3 sm:p-4 mb-3 sm:mb-4 bg-red-500/10 border border-red-500/30 rounded-xl sm:rounded-2xl text-red-600 dark:text-red-400 text-xs sm:text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-3 sm:px-6 py-3 sm:py-4">
        {{ $slot }}
    </main>

    <!-- Kasir Bottom Navigation Bar (Footer) -->
    <nav class="fixed bottom-0 inset-x-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-lg border-t border-slate-200 dark:border-slate-800 px-3 py-2 shadow-2xl">
        <div class="max-w-xl mx-auto grid grid-cols-4 gap-2">
            <!-- 1. Dashboard -->
            <a href="{{ route('kasir.dashboard') }}"
               class="flex flex-col items-center justify-center py-1.5 px-2 rounded-xl transition {{ request()->routeIs('kasir.dashboard') ? 'text-blue-600 dark:text-blue-400 bg-blue-500/10 font-bold' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                <span class="text-[11px]">Dashboard</span>
            </a>

            <!-- 2. Order (Mendatang & Bulanan) -->
            <a href="{{ route('kasir.orders.index') }}"
               class="flex flex-col items-center justify-center py-1.5 px-2 rounded-xl transition relative {{ request()->routeIs('kasir.orders.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-500/10 font-bold' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span class="text-[11px]">Order</span>
            </a>

            <!-- 3. Informasi Pekerja / Kapster -->
            <a href="{{ route('kasir.staffs.index') }}"
               class="flex flex-col items-center justify-center py-1.5 px-2 rounded-xl transition {{ request()->routeIs('kasir.staffs.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-500/10 font-bold' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-[11px]">Pekerja</span>
            </a>

            <!-- 4. Laporan Pelayanan -->
            <a href="{{ route('kasir.reports.index') }}"
               class="flex flex-col items-center justify-center py-1.5 px-2 rounded-xl transition {{ request()->routeIs('kasir.reports.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-500/10 font-bold' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="text-[11px]">Laporan</span>
            </a>
        </div>
    </nav>

    <!-- Pop-up Konfirmasi Logout Kasir -->
    <div x-show="showLogoutModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4"
         style="display: none;">
        
        <div @click.away="showLogoutModal = false"
             class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl max-w-sm w-full p-6 shadow-2xl text-center space-y-4">
            
            <div class="w-14 h-14 mx-auto rounded-2xl bg-red-500/10 border border-red-500/20 text-red-500 flex items-center justify-center shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </div>

            <div>
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Konfirmasi Keluar Kasir</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Apakah Anda yakin ingin keluar dari panel kasir?
                </p>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-2">
                <button type="button"
                        @click="showLogoutModal = false"
                        class="w-full py-2.5 px-4 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-300 dark:border-slate-700 transition">
                    Batal
                </button>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit"
                            class="w-full py-2.5 px-4 bg-red-600 hover:bg-red-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-red-600/20 transition">
                        Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
