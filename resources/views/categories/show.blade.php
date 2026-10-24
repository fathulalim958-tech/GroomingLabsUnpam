<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $content->title }} - Grooming Lab</title>

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
<body class="font-sans text-slate-900 bg-slate-50 dark:bg-slate-950 dark:text-slate-100 min-h-screen antialiased selection:bg-amber-500 selection:text-white transition-colors duration-200 {{ auth()->check() && auth()->user()->role === 'pelanggan' ? 'pb-24' : 'pb-12' }}">
    <!-- Top Bar -->
    <header class="sticky top-0 z-40 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ auth()->check() ? (auth()->user()->role === 'pelanggan' ? route('pelanggan.home') : route('dashboard')) : route('login') }}"
                   class="p-2 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-xl bg-slate-100 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 transition"
                   title="Kembali">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>

                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-tr {{ $category === 'barbershop' ? 'from-amber-500 to-yellow-400 text-slate-950' : 'from-pink-500 to-rose-400 text-white' }} flex items-center justify-center font-black text-xs shadow">
                        GL
                    </div>
                    <div>
                        <span class="font-extrabold text-sm tracking-wide text-slate-900 dark:text-white block leading-tight">GROOMING LAB</span>
                        <span class="text-[9px] uppercase tracking-wider font-semibold {{ $category === 'barbershop' ? 'text-amber-600 dark:text-amber-400' : 'text-pink-600 dark:text-pink-400' }}">
                            {{ $category === 'barbershop' ? 'BARBERSHOP SERVICE' : 'MUA STUDIO SERVICE' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Header Action & Theme Toggle -->
            <div class="flex items-center gap-2 sm:gap-3">
                <x-theme-toggle />

                @auth
                    @if (auth()->user()->role === 'pelanggan')
                        <a href="{{ route('pelanggan.booking.create', $category) }}"
                           class="px-4 py-2 bg-gradient-to-r {{ $category === 'barbershop' ? 'from-amber-500 to-yellow-400 text-slate-950' : 'from-pink-500 to-rose-500 text-white' }} font-bold text-xs rounded-xl shadow-lg transition transform active:scale-95 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="hidden sm:inline">Booking Sekarang</span>
                            <span class="sm:hidden">Booking</span>
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="text-xs text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white underline">
                            Ke Dashboard
                        </a>
                    @endif
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="px-3 py-1.5 text-xs text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white border border-slate-300 dark:border-slate-700 rounded-lg">Masuk</a>
                        <a href="{{ route('register') }}" class="px-3 py-1.5 text-xs bg-amber-500 text-slate-950 font-bold rounded-lg">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-4xl mx-auto px-4 py-6 space-y-8">
        <!-- Hero Header Card -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br {{ $category === 'barbershop' ? 'from-amber-50 via-white to-amber-100/40 dark:from-slate-900 dark:via-slate-900 dark:to-amber-950/40 border-amber-300 dark:border-amber-500/30' : 'from-pink-50 via-white to-pink-100/40 dark:from-slate-900 dark:via-slate-900 dark:to-pink-950/40 border-pink-300 dark:border-pink-500/30' }} border-2 p-6 sm:p-8 shadow-md dark:shadow-2xl">
            <div class="relative z-10 space-y-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $category === 'barbershop' ? 'bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/30' : 'bg-pink-500/10 text-pink-700 dark:text-pink-400 border border-pink-500/30' }}">
                    <span class="w-2 h-2 rounded-full {{ $category === 'barbershop' ? 'bg-amber-500' : 'bg-pink-500' }}"></span>
                    Kategori Layanan Resmi
                </span>

                <h1 class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight">
                    {{ $content->title }}
                </h1>

                @if ($content->tagline)
                    <p class="text-base sm:text-lg font-semibold {{ $category === 'barbershop' ? 'text-amber-700 dark:text-amber-300' : 'text-pink-700 dark:text-pink-300' }}">
                        {{ $content->tagline }}
                    </p>
                @endif

                <div class="pt-2 text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line max-w-3xl">
                    {{ $content->description }}
                </div>

                <!-- Action CTA -->
                <div class="pt-4 flex flex-wrap items-center gap-3">
                    @auth
                        @if (auth()->user()->role === 'pelanggan')
                            <a href="{{ route('pelanggan.booking.create', $category) }}"
                               class="px-6 py-3 bg-gradient-to-r {{ $category === 'barbershop' ? 'from-amber-500 to-yellow-400 text-slate-950 hover:from-amber-600 hover:to-yellow-500' : 'from-pink-500 to-rose-500 text-white hover:from-pink-600 hover:to-rose-600' }} font-extrabold text-sm rounded-xl shadow-xl transition transform active:scale-95 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Reservasi & Ambil Nomor Antrian</span>
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-bold rounded-xl border border-slate-300 dark:border-slate-700">
                                Kembali ke Dashboard
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="px-6 py-3 bg-gradient-to-r from-amber-500 to-yellow-400 text-slate-950 font-extrabold text-sm rounded-xl shadow-xl transition flex items-center gap-2">
                            <span>Masuk / Daftar untuk Booking</span> &rarr;
                        </a>
                    @endauth

                    <a href="#daftar-layanan"
                       onclick="const el = document.getElementById('daftar-layanan'); if (el) { el.scrollIntoView({ behavior: 'smooth' }); history.pushState(null, null, '#daftar-layanan'); return false; }"
                       class="px-4 py-3 bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl border border-slate-300 dark:border-slate-700 transition shadow-sm inline-flex items-center gap-1.5 cursor-pointer">
                        Lihat Menu Layanan &darr;
                    </a>
                </div>
            </div>

            <!-- Background glowing blur -->
            <div class="absolute -right-12 -bottom-12 w-64 h-64 {{ $category === 'barbershop' ? 'bg-amber-500/10' : 'bg-pink-500/10' }} rounded-full blur-3xl pointer-events-none"></div>
        </div>

        <!-- Facilities & Advantages (Fasilitas & Keunggulan) -->
        @if (!empty($content->facilities_list))
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm dark:shadow-xl space-y-4">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full {{ $category === 'barbershop' ? 'bg-amber-500' : 'bg-pink-500' }}"></span>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Fasilitas & Keunggulan Kami</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    @foreach ($content->facilities_list as $facility)
                        <div class="flex items-start gap-3 p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-750">
                            <div class="w-6 h-6 rounded-lg {{ $category === 'barbershop' ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/30' : 'bg-pink-500/10 text-pink-600 dark:text-pink-400 border border-pink-500/30' }} flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span class="text-xs sm:text-sm font-medium text-slate-800 dark:text-slate-200">{{ $facility }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Terms & Flow (Tata Tertib & Ketentuan Booking) -->
        @if (!empty($content->terms_list))
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm dark:shadow-xl space-y-4">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Ketentuan & Panduan Antrian</h2>
                </div>

                <div class="space-y-2 pt-1">
                    @foreach ($content->terms_list as $index => $term)
                        <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800 text-xs text-slate-700 dark:text-slate-300">
                            <span class="font-bold text-blue-600 dark:text-blue-400 font-mono">{{ $index + 1 }}.</span>
                            <span>{{ $term }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Services Catalog Section (Daftar Menu Layanan) -->
        <div id="daftar-layanan" class="scroll-mt-20 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full {{ $category === 'barbershop' ? 'bg-amber-500' : 'bg-pink-500' }}"></span>
                        Pilihan Menu Layanan & Tarif
                    </h2>
                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">Daftar tarif resmi yang dapat Anda pilih saat reservasi</p>
                </div>

                @auth
                    @if (auth()->user()->role === 'pelanggan')
                        <a href="{{ route('pelanggan.booking.create', $category) }}"
                           class="text-xs font-bold {{ $category === 'barbershop' ? 'text-amber-600 dark:text-amber-400' : 'text-pink-600 dark:text-pink-400' }} hover:underline">
                            Reservasi Sekarang &rarr;
                        </a>
                    @endif
                @endauth
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse ($services as $service)
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 rounded-2xl p-5 shadow-sm dark:shadow-xl flex flex-col justify-between transition">
                        <div class="space-y-2">
                            <div class="flex items-start justify-between gap-2">
                                <h3 class="font-bold text-slate-900 dark:text-white text-base">{{ $service->name }}</h3>
                                <span class="font-extrabold text-base whitespace-nowrap {{ $category === 'barbershop' ? 'text-amber-600 dark:text-amber-400' : 'text-pink-600 dark:text-pink-400' }}">
                                    {{ $service->formatted_price }}
                                </span>
                            </div>

                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                                {{ $service->description ?? 'Layanan perawatan berkualitas dari tenaga ahli kami.' }}
                            </p>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Estimasi: <strong class="text-slate-800 dark:text-slate-200">{{ $service->duration_minutes }} Menit</strong>
                            </span>

                            @auth
                                @if (auth()->user()->role === 'pelanggan')
                                    <a href="{{ route('pelanggan.booking.create', $category) }}"
                                       class="px-3 py-1.5 rounded-lg {{ $category === 'barbershop' ? 'bg-amber-500/10 hover:bg-amber-500 text-amber-700 hover:text-slate-950 dark:text-amber-400 dark:hover:text-slate-950 border border-amber-500/30' : 'bg-pink-500/10 hover:bg-pink-500 text-pink-700 hover:text-white dark:text-pink-400 dark:hover:text-white border border-pink-500/30' }} text-[11px] font-bold transition">
                                        Pilih &rarr;
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 py-8 text-center text-slate-500 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm">
                        Belum ada layanan aktif pada kategori ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Final Booking Call to Action Banner -->
        <div class="rounded-3xl bg-gradient-to-r {{ $category === 'barbershop' ? 'from-amber-500 via-amber-400 to-yellow-400 text-slate-950' : 'from-pink-600 to-rose-500 text-white' }} p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xl">
            <div class="space-y-1 text-center sm:text-left">
                <h3 class="text-xl sm:text-2xl font-black">Siap Menikmati Layanan {{ ucfirst($category) }}?</h3>
                <p class="text-xs sm:text-sm font-medium {{ $category === 'barbershop' ? 'text-slate-900/90' : 'text-white/90' }}">Ambil nomor antrian Anda secara digital tanpa perlu menunggu lama di tempat.</p>
            </div>

            @auth
                @if (auth()->user()->role === 'pelanggan')
                    <a href="{{ route('pelanggan.booking.create', $category) }}"
                       class="px-6 py-3.5 bg-slate-950 hover:bg-slate-900 text-white font-extrabold text-sm rounded-xl shadow-xl transition transform active:scale-95 flex-shrink-0">
                        Buat Pesanan Sekarang &rarr;
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}"
                   class="px-6 py-3.5 bg-slate-950 hover:bg-slate-900 text-white font-extrabold text-sm rounded-xl shadow-xl transition transform active:scale-95 flex-shrink-0">
                    Login Untuk Reservasi &rarr;
                </a>
            @endauth
        </div>
    </main>

    <!-- If customer is logged in, show mobile bottom nav -->
    @auth
        @if (auth()->user()->role === 'pelanggan')
            <nav class="fixed bottom-0 inset-x-0 z-50 bg-white/95 dark:bg-slate-900/95 backdrop-blur-lg border-t border-slate-200 dark:border-slate-800/80 px-2 py-2 shadow-lg">
                <div class="max-w-md mx-auto grid grid-cols-4 gap-1">
                    <a href="{{ route('pelanggan.home') }}" class="flex flex-col items-center justify-center py-1.5 px-2 rounded-xl text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200">
                        <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="text-[11px]">Home</span>
                    </a>
                    <a href="{{ route('pelanggan.order.index') }}" class="flex flex-col items-center justify-center py-1.5 px-2 rounded-xl text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200">
                        <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                        <span class="text-[11px]">Order</span>
                    </a>
                    <a href="{{ route('pelanggan.history.index') }}" class="flex flex-col items-center justify-center py-1.5 px-2 rounded-xl text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200">
                        <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-[11px]">Riwayat</span>
                    </a>
                    <a href="{{ route('pelanggan.profile.edit') }}" class="flex flex-col items-center justify-center py-1.5 px-2 rounded-xl text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200">
                        <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="text-[11px]">Profil</span>
                    </a>
                </div>
            </nav>
        @endif
    @endauth
</body>
</html>
