<x-pelanggan-layout title="LPKKS GROOMING LAB">
    <div class="space-y-6 pb-6">
        <!-- Active Booking Banners (if any) -->
        @if (isset($activeBookings) && $activeBookings->isNotEmpty())
            <div class="space-y-3">
                @foreach ($activeBookings as $b)
                    <div class="bg-gradient-to-r from-white via-[#F7FAFE] to-[#EAF3FF] dark:from-slate-900 dark:via-slate-900 dark:to-slate-800 border border-[#D7E7FA] dark:border-slate-800 rounded-2xl p-4 flex items-center justify-between shadow-[0_4px_25px_rgba(11,45,80,0.05)]">
                        <div class="flex items-center gap-3">
                            <div class="min-w-[60px] h-11 px-3 rounded-xl bg-[#0B2D50] border border-[#0B2D50] text-white flex-shrink-0 flex items-center justify-center font-bold font-mono text-sm tracking-tight whitespace-nowrap leading-none shadow-sm">
                                {{ $b->queue_number }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-xs uppercase tracking-wider font-bold text-[#0B2D50] dark:text-sky-300">Antrian Aktif Anda</span>
                                    <span class="px-2.5 py-0.5 text-[10px] rounded-full font-bold uppercase bg-[#0B2D50] text-white">
                                        {{ ucfirst($b->service_category) }}
                                    </span>
                                    <span class="px-2.5 py-0.5 text-[10px] rounded-full font-semibold bg-[#EAF3FF] text-[#0B2D50] dark:bg-slate-800 dark:text-sky-300 border border-[#D7E7FA]/60 dark:border-slate-700">
                                        {{ $b->status_label }}
                                    </span>
                                </div>
                                <p class="text-sm font-semibold text-slate-800 dark:text-white mt-0.5">
                                    {{ $b->booking_date->isToday() ? 'Hari Ini' : $b->booking_date->translatedFormat('d M Y') }} &bull; {{ $b->booking_time }} WIB
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('pelanggan.order.index', ['category' => $b->service_category]) }}" class="px-4 py-2 bg-gradient-to-r from-[#0B2D50] to-[#1769AA] hover:from-[#082747] hover:to-[#103E69] text-white text-xs font-bold rounded-xl transition shadow-sm flex items-center gap-1">
                            <span>Lihat Tiket</span> &rarr;
                        </a>
                    </div>
                @endforeach
            </div>
        @elseif (isset($activeBooking) && $activeBooking)
            <div class="bg-gradient-to-r from-white via-[#F7FAFE] to-[#EAF3FF] dark:from-slate-900 dark:via-slate-900 dark:to-slate-800 border border-[#D7E7FA] dark:border-slate-800 rounded-2xl p-4 flex items-center justify-between shadow-[0_4px_25px_rgba(11,45,80,0.05)]">
                <div class="flex items-center gap-3">
                    <div class="min-w-[60px] h-11 px-3 rounded-xl bg-[#0B2D50] border border-[#0B2D50] text-white flex-shrink-0 flex items-center justify-center font-bold font-mono text-sm tracking-tight whitespace-nowrap leading-none shadow-sm">
                        {{ $activeBooking->queue_number }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs uppercase tracking-wider font-bold text-[#0B2D50] dark:text-sky-300">Antrian Aktif Anda</span>
                            <span class="px-2.5 py-0.5 text-[10px] rounded-full font-bold uppercase bg-[#0B2D50] text-white">
                                {{ ucfirst($activeBooking->service_category) }}
                            </span>
                            <span class="px-2.5 py-0.5 text-[10px] rounded-full font-semibold bg-[#EAF3FF] text-[#0B2D50] dark:bg-slate-800 dark:text-sky-300 border border-[#D7E7FA]/60 dark:border-slate-700">
                                {{ $activeBooking->status_label }}
                            </span>
                        </div>
                        <p class="text-sm font-semibold text-slate-800 dark:text-white mt-0.5">
                            {{ ucfirst($activeBooking->service_category) }} &bull; {{ $activeBooking->booking_date->isToday() ? 'Hari Ini' : $activeBooking->booking_date->translatedFormat('d M Y') }} &bull; {{ $activeBooking->booking_time }} WIB
                        </p>
                    </div>
                </div>
                <a href="{{ route('pelanggan.order.index', ['category' => $activeBooking->service_category]) }}" class="px-4 py-2 bg-gradient-to-r from-[#0B2D50] to-[#1769AA] hover:from-[#082747] hover:to-[#103E69] text-white text-xs font-bold rounded-xl transition shadow-sm flex items-center gap-1">
                    <span>Lihat Tiket</span> &rarr;
                </a>
            </div>
        @endif

        <!-- Hero / Promo Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#082747] via-[#0B2D50] to-[#103E69] p-6 sm:p-8 shadow-[0_12px_36px_rgba(11,45,80,0.18)] text-white">
            <!-- Modern Barbershop Interior & Barber Chair Focal Point (Right Side) -->
            <div class="absolute right-0 top-0 bottom-0 w-full sm:w-7/12 pointer-events-none overflow-hidden flex justify-end">
                <img src="{{ asset('images/hero-barber-chair.png') }}" alt="Modern Barbershop" class="h-full w-auto object-cover object-right opacity-95">
            </div>

            <!-- Gradient Overlay from left to preserve extreme text legibility -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#082747] via-[#082747]/90 sm:via-[#082747]/75 to-transparent pointer-events-none"></div>

            <!-- Abstract wave & ambient lighting accents -->
            <div class="absolute -right-6 -bottom-10 w-72 h-44 bg-gradient-to-t from-sky-400/20 via-blue-500/10 to-transparent rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute left-1/3 top-0 w-64 h-32 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 max-w-lg">
                <span class="inline-block px-3 py-1 bg-white/15 border border-white/20 text-sky-100 rounded-full text-xs font-bold tracking-wide uppercase mb-3 backdrop-blur-xs">
                    E-Booking & Antrian Real-Time
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white leading-tight tracking-tight">
                    Tampil Maksimal Tanpa <span class="text-sky-300">Antre Lama</span>
                </h1>
                <p class="text-sm text-slate-200 mt-2 leading-relaxed">
                    Pilih perawatan rambut maskulin atau tata rias glamor MUA profesional. Reservasi sekarang dan pantau nomor antrian Anda secara langsung dari smartphone.
                </p>

                <!-- CTA Button (FR-CUST-02) -->
                <div class="mt-5">
                    <a href="{{ route('pelanggan.booking.category') }}"
                       class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-white hover:bg-slate-50 text-[#0B2D50] font-extrabold text-sm rounded-xl shadow-md transition transform hover:-translate-y-0.5 active:translate-y-0">
                        <svg class="w-5 h-5 text-[#1769AA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Booking Sekarang</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Category Shortcut Cards (Menjelaskan Layanan Masing-Masing Kategori) -->
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('categories.show', 'barbershop') }}"
               class="service-card-decorated group p-5 bg-white dark:bg-slate-900 border border-[#D7E7FA] dark:border-slate-800 hover:border-[#1769AA]/50 dark:hover:border-blue-700/60 rounded-3xl transition duration-200 shadow-[0_4px_20px_rgba(11,45,80,0.04)] hover:shadow-md">
                <div class="w-11 h-11 rounded-2xl bg-[#EAF3FF] dark:bg-blue-950/40 border border-[#D7E7FA] dark:border-blue-900/40 text-[#1769AA] dark:text-sky-400 flex items-center justify-center mb-3 group-hover:scale-105 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879a3 3 0 11-4.242-4.242L7.757 7.757m0 0l-2.879-2.879a3 3 0 10-4.242 4.242L3.515 12" />
                    </svg>
                </div>
                <h3 class="font-bold text-[#0B2D50] dark:text-white text-base">Barbershop</h3>
                <p class="text-xs text-[#64748B] dark:text-slate-400 mt-1 line-clamp-2">Potong rambut, shaving, pomade, & treatment pria.</p>
                <div class="mt-4 flex items-center gap-1 text-xs font-bold text-[#1769AA] dark:text-sky-400 group-hover:translate-x-0.5 transition">
                    <span>Lihat Detail Layanan</span> &rarr;
                </div>
            </a>

            <a href="{{ route('categories.show', 'mua') }}"
               class="service-card-decorated group p-5 bg-white dark:bg-slate-900 border border-[#D7E7FA] dark:border-slate-800 hover:border-[#1769AA]/50 dark:hover:border-blue-700/60 rounded-3xl transition duration-200 shadow-[0_4px_20px_rgba(11,45,80,0.04)] hover:shadow-md">
                <div class="w-11 h-11 rounded-2xl bg-[#EAF3FF] dark:bg-blue-950/40 border border-[#D7E7FA] dark:border-blue-900/40 text-[#1769AA] dark:text-sky-400 flex items-center justify-center mb-3 group-hover:scale-105 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
                <h3 class="font-bold text-[#0B2D50] dark:text-white text-base">MUA Studio</h3>
                <p class="text-xs text-[#64748B] dark:text-slate-400 mt-1 line-clamp-2">Party, graduation, bridal & daily natural makeup.</p>
                <div class="mt-4 flex items-center gap-1 text-xs font-bold text-[#1769AA] dark:text-sky-400 group-hover:translate-x-0.5 transition">
                    <span>Lihat Detail Layanan</span> &rarr;
                </div>
            </a>
        </div>

        <!-- Featured Barbershop Services (FR-CUST-01) -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-base sm:text-lg font-bold text-[#0B2D50] dark:text-white flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#1769AA]"></span>
                    Katalog Barbershop
                </h2>
                <a href="{{ route('pelanggan.booking.create', 'barbershop') }}" class="text-xs text-[#1769AA] dark:text-sky-400 hover:underline font-semibold">
                    Booking &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach ($barbershopServices as $service)
                    <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-2xl flex justify-between items-start hover:border-slate-300 dark:hover:border-slate-700 transition shadow-sm">
                        <div class="space-y-1 pr-3">
                            <h4 class="font-bold text-slate-800 dark:text-slate-100 text-sm">{{ $service->name }}</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">{{ $service->description }}</p>
                            <div class="flex items-center gap-2 text-[11px] text-slate-400 dark:text-slate-500 pt-1">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $service->duration_minutes }} Menit
                                </span>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="text-sm font-bold text-[#1769AA] dark:text-sky-400">{{ $service->formatted_price }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Featured MUA Services (FR-CUST-01) -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-base sm:text-lg font-bold text-[#0B2D50] dark:text-white flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-pink-500"></span>
                    Katalog MUA & Tata Rias
                </h2>
                <a href="{{ route('pelanggan.booking.create', 'mua') }}" class="text-xs text-pink-600 dark:text-pink-400 hover:underline font-semibold">
                    Booking &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach ($muaServices as $service)
                    <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-2xl flex justify-between items-start hover:border-slate-300 dark:hover:border-slate-700 transition shadow-sm">
                        <div class="space-y-1 pr-3">
                            <h4 class="font-bold text-slate-800 dark:text-slate-100 text-sm">{{ $service->name }}</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">{{ $service->description }}</p>
                            <div class="flex items-center gap-2 text-[11px] text-slate-400 dark:text-slate-500 pt-1">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $service->duration_minutes }} Menit
                                </span>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="text-sm font-bold text-pink-600 dark:text-pink-400">{{ $service->formatted_price }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-pelanggan-layout>
