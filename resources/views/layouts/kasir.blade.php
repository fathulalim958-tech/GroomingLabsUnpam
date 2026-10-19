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
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-900 bg-slate-50 dark:bg-slate-950 dark:text-slate-100 min-h-screen antialiased selection:bg-blue-500 selection:text-white transition-colors duration-200">
    <!-- Kasir Header Bar -->
    <header class="sticky top-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('kasir.dashboard') }}" class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-cyan-500 flex items-center justify-center text-white font-black text-sm shadow-lg shadow-blue-500/20">
                        GL
                    </div>
                    <div>
                        <span class="font-black text-sm tracking-wide text-slate-900 dark:text-white uppercase block leading-tight">GROOMING LAB</span>
                        <span class="text-[10px] uppercase tracking-widest text-blue-600 dark:text-blue-400 font-bold">KASIR & OPERASIONAL ANTRIAN</span>
                    </div>
                </a>

                <span class="hidden md:inline-block px-2.5 py-0.5 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-600 dark:text-blue-400 text-xs font-semibold">
                    Front-Desk Mode
                </span>
            </div>

            <!-- Header actions: Walk-in button, Theme Toggle, user badge, logout -->
            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('kasir.walkin.create') }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-500 hover:to-cyan-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-blue-600/20 transition transform active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>+ Order Walk-In</span>
                </a>

                <x-theme-toggle />

                <div class="h-6 w-px bg-slate-200 dark:bg-slate-800 hidden sm:block"></div>

                <div class="text-right hidden sm:block">
                    <span class="block text-xs font-bold text-slate-800 dark:text-slate-200">{{ auth()->user()->name }}</span>
                    <span class="block text-[10px] text-slate-500 dark:text-slate-400 font-mono">{{ now()->translatedFormat('l, d M Y') }}</span>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" title="Keluar" class="p-2 text-slate-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-red-300 dark:hover:border-red-900/50 bg-slate-100 dark:bg-slate-900 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Flash Notifications -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 mt-4">
        @if (session('success'))
            <div class="p-4 mb-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-600 dark:text-emerald-400 text-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 mb-4 bg-red-500/10 border border-red-500/30 rounded-2xl text-red-600 dark:text-red-400 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-4">
        {{ $slot }}
    </main>

    @stack('scripts')
</body>
</html>
