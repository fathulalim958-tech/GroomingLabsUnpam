<x-pelanggan-layout title="Home - Grooming Lab & MUA">
    <div class="space-y-6 pb-6">
        <!-- Active Booking Banner (if any) -->
        @if ($activeBooking)
            <div class="bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-white dark:from-amber-900/60 dark:via-amber-800/40 dark:to-slate-900 border border-amber-500/40 rounded-2xl p-4 flex items-center justify-between shadow-sm dark:shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center text-amber-600 dark:text-amber-400 font-extrabold text-base">
                        {{ $activeBooking->queue_number }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs uppercase tracking-wider font-bold text-amber-600 dark:text-amber-400">Antrian Aktif Anda</span>
                            <span class="px-2 py-0.5 text-[10px] rounded-full font-semibold {{ $activeBooking->status_color }}">
                                {{ $activeBooking->status_label }}
                            </span>
                        </div>
                        <p class="text-sm font-semibold text-slate-900 dark:text-white mt-0.5">
                            {{ ucfirst($activeBooking->service_category) }} - {{ $activeBooking->booking_time }} WIB
                        </p>
                    </div>
                </div>
                <a href="{{ route('pelanggan.order.index') }}" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold rounded-lg transition shadow">
                    Lihat Tiket &rarr;
                </a>
            </div>
        @endif

        <!-- Hero / Promo Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-amber-950/40 border border-slate-800 p-6 sm:p-8 shadow-2xl">
            <div class="relative z-10 max-w-lg">
                <span class="inline-block px-3 py-1 bg-amber-500/10 border border-amber-500/30 text-amber-400 rounded-full text-xs font-bold tracking-wide uppercase mb-3">
                    E-Booking & Antrian Real-Time
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white leading-tight tracking-tight">
                    Tampil Maksimal Tanpa <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-yellow-300">Antre Lama</span>
                </h1>
                <p class="text-sm text-slate-300 mt-2 leading-relaxed">
                    Pilih perawatan rambut maskulin atau tata rias glamor MUA profesional. Reservasi sekarang dan pantau nomor antrian Anda secara langsung dari smartphone.
                </p>

                <!-- CTA Button (FR-CUST-02) -->
                <div class="mt-5">
                    <a href="{{ route('pelanggan.booking.category') }}"
                       class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-amber-500 via-amber-400 to-yellow-400 hover:from-amber-600 hover:to-yellow-500 text-slate-950 font-extrabold text-sm rounded-xl shadow-xl shadow-amber-500/25 transition transform hover:-translate-y-0.5 active:translate-y-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Booking Sekarang</span>
                    </a>
                </div>
            </div>

            <!-- Decorative background elements -->
            <div class="absolute -right-8 -bottom-8 w-60 h-60 bg-amber-500/10 rounded-full blur-2xl"></div>
        </div>

        <!-- Category Shortcut Cards (Menjelaskan Layanan Masing-Masing Kategori) -->
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('categories.show', 'barbershop') }}"
               class="group p-4 bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 hover:border-amber-500/50 rounded-2xl transition shadow-sm dark:shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-3 group-hover:scale-110 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879a3 3 0 11-4.242-4.242L7.757 7.757m0 0l-2.879-2.879a3 3 0 10-4.242 4.242L3.515 12" />
                    </svg>
                </div>
                <h3 class="font-bold text-slate-900 dark:text-white text-base">Barbershop</h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">Potong rambut, shaving, pomade, & treatment pria.</p>
                <div class="mt-3 flex items-center gap-1 text-xs font-semibold text-amber-600 dark:text-amber-400 group-hover:translate-x-0.5 transition">
                    <span>Lihat Detail Layanan</span> &rarr;
                </div>
            </a>

            <a href="{{ route('categories.show', 'mua') }}"
               class="group p-4 bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 hover:border-pink-500/50 rounded-2xl transition shadow-sm dark:shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-pink-500/10 border border-pink-500/20 text-pink-600 dark:text-pink-400 flex items-center justify-center mb-3 group-hover:scale-110 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
                <h3 class="font-bold text-slate-900 dark:text-white text-base">MUA Studio</h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">Party, graduation, bridal & daily natural makeup.</p>
                <div class="mt-3 flex items-center gap-1 text-xs font-semibold text-pink-600 dark:text-pink-400 group-hover:translate-x-0.5 transition">
                    <span>Lihat Detail Layanan</span> &rarr;
                </div>
            </a>
        </div>

        <!-- Featured Barbershop Services (FR-CUST-01) -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    Katalog Barbershop
                </h2>
                <a href="{{ route('pelanggan.booking.create', 'barbershop') }}" class="text-xs text-amber-600 dark:text-amber-400 hover:underline font-semibold">
                    Booking &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach ($barbershopServices as $service)
                    <div class="p-4 bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800/90 rounded-2xl flex justify-between items-start hover:border-slate-300 dark:hover:border-slate-700 transition shadow-sm">
                        <div class="space-y-1 pr-3">
                            <h4 class="font-bold text-slate-900 dark:text-slate-100 text-sm">{{ $service->name }}</h4>
                            <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2">{{ $service->description }}</p>
                            <div class="flex items-center gap-2 text-[11px] text-slate-500 dark:text-slate-400 pt-1">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $service->duration_minutes }} Menit
                                </span>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="text-sm font-extrabold text-amber-600 dark:text-amber-400">{{ $service->formatted_price }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Featured MUA Services (FR-CUST-01) -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-pink-500"></span>
                    Katalog MUA & Tata Rias
                </h2>
                <a href="{{ route('pelanggan.booking.create', 'mua') }}" class="text-xs text-pink-600 dark:text-pink-400 hover:underline font-semibold">
                    Booking &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach ($muaServices as $service)
                    <div class="p-4 bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800/90 rounded-2xl flex justify-between items-start hover:border-slate-300 dark:hover:border-slate-700 transition shadow-sm">
                        <div class="space-y-1 pr-3">
                            <h4 class="font-bold text-slate-900 dark:text-slate-100 text-sm">{{ $service->name }}</h4>
                            <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2">{{ $service->description }}</p>
                            <div class="flex items-center gap-2 text-[11px] text-slate-500 dark:text-slate-400 pt-1">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $service->duration_minutes }} Menit
                                </span>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="text-sm font-extrabold text-pink-600 dark:text-pink-400">{{ $service->formatted_price }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-pelanggan-layout>
