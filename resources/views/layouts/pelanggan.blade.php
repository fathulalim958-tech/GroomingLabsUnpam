<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Grooming Lab & MUA Studio' }}</title>

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
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ showLogoutModal: false }" class="font-sans text-slate-900 bg-slate-50 dark:bg-slate-950 dark:text-slate-100 min-h-screen pb-24 md:pb-8 antialiased selection:bg-amber-500 selection:text-white transition-colors duration-200">
    <!-- Top Navbar -->
    <header class="sticky top-0 z-40 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-200 dark:border-slate-800/80">
        <div class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('pelanggan.home') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-amber-500 to-yellow-400 flex items-center justify-center text-slate-950 font-black text-sm shadow">
                    GL
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-sm tracking-wide text-slate-900 dark:text-white uppercase">GROOMING LAB</span>
                    <span class="text-[9px] uppercase tracking-wider text-amber-600 dark:text-amber-400 font-semibold">& MUA</span>
                </div>
            </a>

            <!-- Right actions: Theme Toggle, User info & Logout -->
            <div class="flex items-center gap-2 sm:gap-3">
                <x-theme-toggle />

                <span class="text-xs text-slate-500 dark:text-slate-400 hidden sm:inline">Hai, <strong class="text-slate-800 dark:text-slate-200">{{ auth()->user()->name }}</strong></span>
                <button type="button"
                        @click="showLogoutModal = true"
                        class="text-xs text-slate-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 px-2.5 py-1.5 rounded-lg border border-slate-200 dark:border-slate-800 hover:border-red-300 dark:hover:border-red-900/50 bg-slate-100 dark:bg-slate-900 transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="hidden sm:inline">Keluar</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Flash Notifications -->
    <div class="max-w-4xl mx-auto px-4 mt-4">
        @if (session('success'))
            <div class="p-4 mb-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-600 dark:text-emerald-400 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 mb-4 bg-red-500/10 border border-red-500/30 rounded-xl text-red-600 dark:text-red-400 text-sm">
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
    <nav class="fixed bottom-0 inset-x-0 z-50 bg-white/95 dark:bg-slate-900/95 backdrop-blur-lg border-t border-slate-200 dark:border-slate-800/80 px-2 py-2 shadow-lg">
        <div class="max-w-md mx-auto grid grid-cols-4 gap-1">
            <!-- Home -->
            <a href="{{ route('pelanggan.home') }}"
               class="flex flex-col items-center justify-center py-1.5 px-2 rounded-xl transition {{ request()->routeIs('pelanggan.home') ? 'text-amber-600 dark:text-amber-400 bg-amber-500/10 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-[11px]">Home</span>
            </a>

            <!-- Order (Antrian Aktif) -->
            <a href="{{ route('pelanggan.order.index') }}"
               class="flex flex-col items-center justify-center py-1.5 px-2 rounded-xl transition relative {{ request()->routeIs('pelanggan.order.*') ? 'text-amber-600 dark:text-amber-400 bg-amber-500/10 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
                <span class="text-[11px]">Order</span>
            </a>

            <!-- Riwayat -->
            <a href="{{ route('pelanggan.history.index') }}"
               class="flex flex-col items-center justify-center py-1.5 px-2 rounded-xl transition {{ request()->routeIs('pelanggan.history.*') ? 'text-amber-600 dark:text-amber-400 bg-amber-500/10 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-[11px]">Riwayat</span>
            </a>

            <!-- Profil -->
            <a href="{{ route('pelanggan.profile.edit') }}"
               class="flex flex-col items-center justify-center py-1.5 px-2 rounded-xl transition {{ request()->routeIs('pelanggan.profile.*') ? 'text-amber-600 dark:text-amber-400 bg-amber-500/10 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-[11px]">Profil</span>
            </a>
        </div>
    </nav>
    <!-- Pop-up Konfirmasi Logout Pelanggan -->
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
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Konfirmasi Keluar</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Apakah Anda yakin ingin keluar dari akun Grooming Lab?
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
