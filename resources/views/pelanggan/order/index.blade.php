<x-pelanggan-layout title="Order Aktif & Antrian - Grooming Lab & MUA">
    <div class="max-w-xl mx-auto py-4 space-y-5" x-data="queueMonitor()">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white">Tiket Antrian Aktif</h1>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">Pantau giliran Anda secara real-time</p>
            </div>
            <!-- Live Indicator Badge -->
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Live Sync Aktif</span>
            </div>
        </div>

        <!-- Category Switcher Tabs: Barbershop vs MUA Studio -->
        <div class="grid grid-cols-2 gap-2 p-1.5 bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm">
            <!-- Tab Barbershop -->
            <button type="button"
                    @click="activeTab = 'barbershop'"
                    :class="activeTab === 'barbershop' 
                        ? 'bg-amber-500 text-slate-950 font-bold shadow-sm' 
                        : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-semibold'"
                    class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl text-xs sm:text-sm transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879a3 3 0 11-4.242-4.242L7.757 7.757m0 0l-2.879-2.879a3 3 0 10-4.242 4.242L3.515 12" />
                </svg>
                <span>Barbershop</span>
                @if ($barbershopBookings->count() > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black"
                          :class="activeTab === 'barbershop' ? 'bg-slate-950 text-amber-400' : 'bg-amber-500/20 text-amber-600 dark:text-amber-400'">
                        {{ $barbershopBookings->count() }}
                    </span>
                @endif
            </button>

            <!-- Tab MUA Studio -->
            <button type="button"
                    @click="activeTab = 'mua'"
                    :class="activeTab === 'mua' 
                        ? 'bg-pink-500 text-white font-bold shadow-sm' 
                        : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-semibold'"
                    class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl text-xs sm:text-sm transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                <span>MUA Studio</span>
                @if ($muaBookings->count() > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black"
                          :class="activeTab === 'mua' ? 'bg-white text-pink-600' : 'bg-pink-500/20 text-pink-600 dark:text-pink-400'">
                        {{ $muaBookings->count() }}
                    </span>
                @endif
            </button>
        </div>

        <!-- ========================================== -->
        <!-- TAB 1: BARBERSHOP -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'barbershop'" class="space-y-4" x-cloak>
            <!-- Barbershop Current Serving Card -->
            <div class="bg-gradient-to-br from-amber-50 to-amber-100/40 dark:from-slate-900 dark:to-amber-950/30 border border-amber-300 dark:border-amber-500/30 rounded-2xl p-4 text-center shadow-sm dark:shadow-lg">
                <span class="text-[11px] font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400">Sedang Dilayani Hari Ini</span>
                <p class="text-xs text-slate-600 dark:text-slate-400">Barbershop</p>
                <div class="mt-2 text-4xl font-black text-amber-600 dark:text-amber-300 font-mono tracking-wider" x-text="currentServing.barbershop || '—'">
                    {{ $currentServing['barbershop']?->queue_number ?? '—' }}
                </div>
                <span class="text-[10px] text-slate-500 dark:text-slate-400 block mt-1">Antrian Di Kursi</span>
            </div>

            <!-- Barbershop Bookings List -->
            <div class="space-y-4">
                @forelse ($barbershopBookings as $booking)
                    <div class="bg-white dark:bg-slate-900 border-2 {{ $booking->status === 'called' ? 'border-indigo-500 shadow-indigo-500/20 ring-2 ring-indigo-500/30' : ($booking->status === 'in_progress' ? 'border-emerald-500 shadow-emerald-500/10' : ($booking->status === 'skipped' ? 'border-amber-500 shadow-amber-500/10' : 'border-slate-200 dark:border-slate-800')) }} rounded-3xl p-5 sm:p-6 shadow-md dark:shadow-2xl relative overflow-hidden transition-all duration-300">
                        <!-- Category & Status Badge -->
                        <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/30">
                                    Barbershop
                                </span>

                                @if ($booking->booking_date->isToday())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Hari Ini
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/30">
                                        📅 {{ $booking->booking_date->translatedFormat('d M Y') }}
                                    </span>
                                @endif
                            </div>

                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $booking->status_color }}">
                                {{ $booking->status_label }}
                            </span>
                        </div>

                        <!-- Called Notification -->
                        @if ($booking->status === 'called')
                            <div class="mb-4 p-3 bg-indigo-500/15 border border-indigo-500/50 rounded-xl text-xs text-indigo-900 dark:text-indigo-200 flex items-start gap-2.5 animate-pulse">
                                <svg class="w-5 h-5 flex-shrink-0 text-indigo-600 dark:text-indigo-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <div>
                                    <span class="font-bold text-sm text-indigo-700 dark:text-indigo-300">Nomor Antrian Anda Sedang Dipanggil!</span>
                                    <p class="text-indigo-800 dark:text-indigo-200/90 mt-0.5">Silakan segera menuju meja kasir atau kursi pelayanan Barbershop.</p>
                                </div>
                            </div>
                        @endif

                        <!-- Skipped Warning Notification -->
                        @if ($booking->status === 'skipped')
                            <div class="mb-4 p-3 bg-amber-500/10 border border-amber-500/40 rounded-xl text-xs text-amber-800 dark:text-amber-300 flex items-start gap-2">
                                <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <span class="font-bold">Antrian Terlewat!</span>
                                    <p class="text-amber-700 dark:text-amber-400/90 mt-0.5">Tiket Anda tetap aktif dan tidak hangus. Harap segera melapor ke meja kasir agar nomor Anda dipanggil kembali.</p>
                                </div>
                            </div>
                        @endif

                        @if ($booking->status === 'in_progress')
                            <div class="mb-4 p-3 bg-emerald-500/10 border border-emerald-500/40 rounded-xl text-xs text-emerald-800 dark:text-emerald-300 flex items-center gap-2 animate-pulse">
                                <svg class="w-4 h-4 flex-shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-bold">Giliran Anda sedang dilayani sekarang! Silakan menuju tempat layanan.</span>
                            </div>
                        @endif

                        <!-- Large Queue Number Display -->
                        <div class="text-center py-4 my-2 bg-slate-50 dark:bg-slate-950/60 rounded-2xl border border-slate-200 dark:border-slate-800/80">
                            <span class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-widest font-semibold block">Nomor Antrian Anda</span>
                            <div class="text-5xl sm:text-6xl font-black text-amber-600 dark:text-amber-400 font-mono tracking-widest my-1">
                                {{ $booking->queue_number }}
                            </div>
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">Kode: {{ $booking->booking_code }}</span>
                        </div>

                        <!-- Details Table -->
                        <div class="space-y-2 mt-4 pt-4 border-t border-slate-200 dark:border-slate-800 text-xs">
                            <div class="flex justify-between py-1">
                                <span class="text-slate-500 dark:text-slate-400">Tanggal & Jam:</span>
                                <span class="font-semibold text-slate-900 dark:text-white text-right">
                                    @if ($booking->booking_date->isToday())
                                        <span class="text-emerald-600 dark:text-emerald-400 font-bold">Hari Ini,</span>
                                    @else
                                        <span class="text-blue-600 dark:text-blue-400 font-bold">{{ $booking->booking_date->translatedFormat('l, d M Y') }},</span>
                                    @endif
                                    {{ $booking->booking_time }} WIB
                                </span>
                            </div>

                            <div class="flex justify-between py-1">
                                <span class="text-slate-500 dark:text-slate-400">Layanan:</span>
                                <span class="font-semibold text-slate-900 dark:text-white text-right max-w-[60%]">
                                    {{ $booking->items->map(fn($item) => $item->service->name)->join(', ') }}
                                </span>
                            </div>

                            @if ($booking->notes)
                                <div class="flex justify-between py-1">
                                    <span class="text-slate-500 dark:text-slate-400">Catatan Khusus:</span>
                                    <span class="text-slate-700 dark:text-slate-300 text-right italic max-w-[60%]">{{ $booking->notes }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between py-1 border-t border-slate-200 dark:border-slate-800/60 pt-2 text-sm">
                                <span class="font-bold text-slate-900 dark:text-white">Total Biaya:</span>
                                <span class="font-extrabold text-amber-600 dark:text-amber-400">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Cancel Button (Only if waiting, called, or skipped) -->
                        @if (in_array($booking->status, ['waiting', 'called', 'skipped']))
                            <div class="mt-5 pt-4 border-t border-slate-200 dark:border-slate-800 flex justify-end">
                                <button type="button"
                                        @click="openCancelModal({{ json_encode([
                                            'id' => $booking->id,
                                            'queueNumber' => $booking->queue_number,
                                            'category' => 'Barbershop',
                                            'service' => $booking->items->map(fn($item) => $item->service->name)->join(', '),
                                            'dateTime' => ($booking->booking_date->isToday() ? 'Hari Ini, ' : $booking->booking_date->translatedFormat('d M Y') . ', ') . $booking->booking_time . ' WIB',
                                            'actionUrl' => route('pelanggan.order.cancel', $booking),
                                        ]) }})"
                                        class="px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-red-500/10 rounded-lg border border-red-300 dark:border-red-500/30 transition flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span>Batalkan Booking</span>
                                </button>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 text-center space-y-4 shadow-sm">
                        <div class="w-16 h-16 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879a3 3 0 11-4.242-4.242L7.757 7.757m0 0l-2.879-2.879a3 3 0 10-4.242 4.242L3.515 12" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Tidak Ada Antrian Aktif Barbershop</h3>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Anda belum memiliki pesanan antrian aktif untuk Barbershop.</p>
                        </div>
                        <div>
                            <a href="{{ route('pelanggan.booking.create', 'barbershop') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs rounded-xl transition shadow">
                                Ambil Nomor Antrian Barbershop &rarr;
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: MUA STUDIO -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'mua'" class="space-y-4" x-cloak>
            <!-- MUA Current Serving Card -->
            <div class="bg-gradient-to-br from-pink-50 to-pink-100/40 dark:from-slate-900 dark:to-pink-950/30 border border-pink-300 dark:border-pink-500/30 rounded-2xl p-4 text-center shadow-sm dark:shadow-lg">
                <span class="text-[11px] font-bold uppercase tracking-wider text-pink-700 dark:text-pink-400">Sedang Dilayani Hari Ini</span>
                <p class="text-xs text-slate-600 dark:text-slate-400">MUA Studio</p>
                <div class="mt-2 text-4xl font-black text-pink-600 dark:text-pink-300 font-mono tracking-wider" x-text="currentServing.mua || '—'">
                    {{ $currentServing['mua']?->queue_number ?? '—' }}
                </div>
                <span class="text-[10px] text-slate-500 dark:text-slate-400 block mt-1">Antrian Di Studio</span>
            </div>

            <!-- MUA Bookings List -->
            <div class="space-y-4">
                @forelse ($muaBookings as $booking)
                    <div class="bg-white dark:bg-slate-900 border-2 {{ $booking->status === 'called' ? 'border-pink-500 shadow-pink-500/20 ring-2 ring-pink-500/30' : ($booking->status === 'in_progress' ? 'border-emerald-500 shadow-emerald-500/10' : ($booking->status === 'skipped' ? 'border-amber-500 shadow-amber-500/10' : 'border-slate-200 dark:border-slate-800')) }} rounded-3xl p-5 sm:p-6 shadow-md dark:shadow-2xl relative overflow-hidden transition-all duration-300">
                        <!-- Category & Status Badge -->
                        <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-pink-500/10 text-pink-700 dark:text-pink-400 border border-pink-500/30">
                                    MUA Studio
                                </span>

                                @if ($booking->booking_date->isToday())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Hari Ini
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/30">
                                        📅 {{ $booking->booking_date->translatedFormat('d M Y') }}
                                    </span>
                                @endif
                            </div>

                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $booking->status_color }}">
                                {{ $booking->status_label }}
                            </span>
                        </div>

                        <!-- Called Notification -->
                        @if ($booking->status === 'called')
                            <div class="mb-4 p-3 bg-pink-500/15 border border-pink-500/50 rounded-xl text-xs text-pink-900 dark:text-pink-200 flex items-start gap-2.5 animate-pulse">
                                <svg class="w-5 h-5 flex-shrink-0 text-pink-600 dark:text-pink-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <div>
                                    <span class="font-bold text-sm text-pink-700 dark:text-pink-300">Nomor Antrian Anda Sedang Dipanggil!</span>
                                    <p class="text-pink-800 dark:text-pink-200/90 mt-0.5">Silakan segera menuju meja kasir atau studio MUA.</p>
                                </div>
                            </div>
                        @endif

                        <!-- Skipped Warning Notification -->
                        @if ($booking->status === 'skipped')
                            <div class="mb-4 p-3 bg-amber-500/10 border border-amber-500/40 rounded-xl text-xs text-amber-800 dark:text-amber-300 flex items-start gap-2">
                                <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <span class="font-bold">Antrian Terlewat!</span>
                                    <p class="text-amber-700 dark:text-amber-400/90 mt-0.5">Tiket Anda tetap aktif dan tidak hangus. Harap segera melapor ke meja kasir agar nomor Anda dipanggil kembali.</p>
                                </div>
                            </div>
                        @endif

                        @if ($booking->status === 'in_progress')
                            <div class="mb-4 p-3 bg-emerald-500/10 border border-emerald-500/40 rounded-xl text-xs text-emerald-800 dark:text-emerald-300 flex items-center gap-2 animate-pulse">
                                <svg class="w-4 h-4 flex-shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-bold">Giliran Anda sedang dilayani sekarang! Silakan menuju tempat layanan.</span>
                            </div>
                        @endif

                        <!-- Large Queue Number Display -->
                        <div class="text-center py-4 my-2 bg-slate-50 dark:bg-slate-950/60 rounded-2xl border border-slate-200 dark:border-slate-800/80">
                            <span class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-widest font-semibold block">Nomor Antrian Anda</span>
                            <div class="text-5xl sm:text-6xl font-black text-pink-600 dark:text-pink-400 font-mono tracking-widest my-1">
                                {{ $booking->queue_number }}
                            </div>
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">Kode: {{ $booking->booking_code }}</span>
                        </div>

                        <!-- Details Table -->
                        <div class="space-y-2 mt-4 pt-4 border-t border-slate-200 dark:border-slate-800 text-xs">
                            <div class="flex justify-between py-1">
                                <span class="text-slate-500 dark:text-slate-400">Tanggal & Jam:</span>
                                <span class="font-semibold text-slate-900 dark:text-white text-right">
                                    @if ($booking->booking_date->isToday())
                                        <span class="text-emerald-600 dark:text-emerald-400 font-bold">Hari Ini,</span>
                                    @else
                                        <span class="text-blue-600 dark:text-blue-400 font-bold">{{ $booking->booking_date->translatedFormat('l, d M Y') }},</span>
                                    @endif
                                    {{ $booking->booking_time }} WIB
                                </span>
                            </div>

                            <div class="flex justify-between py-1">
                                <span class="text-slate-500 dark:text-slate-400">Layanan:</span>
                                <span class="font-semibold text-slate-900 dark:text-white text-right max-w-[60%]">
                                    {{ $booking->items->map(fn($item) => $item->service->name)->join(', ') }}
                                </span>
                            </div>

                            @if ($booking->notes)
                                <div class="flex justify-between py-1">
                                    <span class="text-slate-500 dark:text-slate-400">Catatan Khusus:</span>
                                    <span class="text-slate-700 dark:text-slate-300 text-right italic max-w-[60%]">{{ $booking->notes }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between py-1 border-t border-slate-200 dark:border-slate-800/60 pt-2 text-sm">
                                <span class="font-bold text-slate-900 dark:text-white">Total Biaya:</span>
                                <span class="font-extrabold text-pink-600 dark:text-pink-400">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Cancel Button (Only if waiting, called, or skipped) -->
                        @if (in_array($booking->status, ['waiting', 'called', 'skipped']))
                            <div class="mt-5 pt-4 border-t border-slate-200 dark:border-slate-800 flex justify-end">
                                <button type="button"
                                        @click="openCancelModal({{ json_encode([
                                            'id' => $booking->id,
                                            'queueNumber' => $booking->queue_number,
                                            'category' => 'MUA Studio',
                                            'service' => $booking->items->map(fn($item) => $item->service->name)->join(', '),
                                            'dateTime' => ($booking->booking_date->isToday() ? 'Hari Ini, ' : $booking->booking_date->translatedFormat('d M Y') . ', ') . $booking->booking_time . ' WIB',
                                            'actionUrl' => route('pelanggan.order.cancel', $booking),
                                        ]) }})"
                                        class="px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-red-500/10 rounded-lg border border-red-300 dark:border-red-500/30 transition flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span>Batalkan Booking</span>
                                </button>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 text-center space-y-4 shadow-sm">
                        <div class="w-16 h-16 rounded-2xl bg-pink-500/10 text-pink-600 dark:text-pink-400 flex items-center justify-center mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Tidak Ada Antrian Aktif MUA Studio</h3>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Anda belum memiliki pesanan antrian aktif untuk MUA Studio.</p>
                        </div>
                        <div>
                            <a href="{{ route('pelanggan.booking.create', 'mua') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-pink-500 hover:bg-pink-400 text-white font-bold text-xs rounded-xl transition shadow">
                                Ambil Nomor Antrian MUA Studio &rarr;
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- ========================================== -->
        <!-- POP-UP MODAL KONFIRMASI PEMBATALAN BOOKING -->
        <!-- ========================================== -->
        <div x-show="showCancelModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/75 backdrop-blur-sm flex items-center justify-center p-4"
             style="display: none;">
            
            <div @click.away="closeCancelModal()"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl max-w-sm w-full p-6 shadow-2xl space-y-4 text-center">
                
                <!-- Red Warning Icon with Soft Glow -->
                <div class="w-14 h-14 mx-auto rounded-2xl bg-red-500/10 border border-red-500/30 text-red-500 flex items-center justify-center shadow-inner">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <div>
                    <h3 class="text-base sm:text-lg font-extrabold text-slate-900 dark:text-white">Batalkan Reservasi?</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Apakah Anda yakin ingin membatalkan antrian reservasi ini?
                    </p>
                </div>

                <!-- Booking Details Card inside Modal -->
                <div class="bg-slate-50 dark:bg-slate-950/70 border border-slate-200 dark:border-slate-800/80 rounded-2xl p-4 text-left space-y-2">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Nomor Antrian:</span>
                        <span class="font-mono font-black text-xl text-red-600 dark:text-red-400" x-text="cancelData.queueNumber"></span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 dark:text-slate-400">Kategori:</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200" x-text="cancelData.category"></span>
                    </div>
                    <div class="flex items-start justify-between text-xs gap-2">
                        <span class="text-slate-500 dark:text-slate-400 flex-shrink-0">Layanan:</span>
                        <span class="font-semibold text-slate-900 dark:text-white text-right truncate" x-text="cancelData.service"></span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 dark:text-slate-400">Jadwal:</span>
                        <span class="font-semibold text-slate-900 dark:text-white text-right" x-text="cancelData.dateTime"></span>
                    </div>
                </div>

                <!-- Warning Notice -->
                <div class="p-3 bg-red-500/10 border border-red-500/20 rounded-xl text-left text-[11px] text-red-700 dark:text-red-300 leading-relaxed">
                    <span class="font-bold">⚠️ Perhatian:</span> Nomor antrian Anda akan dilepas dan status pesanan menjadi <strong>Dibatalkan</strong>.
                </div>

                <!-- Modal Action Buttons -->
                <div class="grid grid-cols-2 gap-3 pt-1">
                    <button type="button"
                            @click="closeCancelModal()"
                            class="w-full py-2.5 px-4 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-300 dark:border-slate-700 transition">
                        Kembali
                    </button>

                    <form :action="cancelData.actionUrl" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                                class="w-full py-2.5 px-4 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-red-600/30 transition flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span>Ya, Batalkan</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function queueMonitor() {
            return {
                activeTab: '{{ $activeTab }}',
                showCancelModal: false,
                cancelData: {
                    id: null,
                    queueNumber: '',
                    category: '',
                    service: '',
                    dateTime: '',
                    actionUrl: ''
                },
                openCancelModal(data) {
                    this.cancelData = data;
                    this.showCancelModal = true;
                },
                closeCancelModal() {
                    this.showCancelModal = false;
                },
                currentServing: {
                    barbershop: '{{ $currentServing['barbershop']?->queue_number ?? '' }}',
                    mua: '{{ $currentServing['mua']?->queue_number ?? '' }}'
                },
                init() {
                    // Real-time polling every 5 seconds (FR non-fungsional)
                    setInterval(() => {
                        this.fetchQueueStatus();
                    }, 5000);
                },
                fetchQueueStatus() {
                    fetch('{{ route('pelanggan.order.check-queue') }}', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.current_serving) {
                            this.currentServing.barbershop = data.current_serving.barbershop || '—';
                            this.currentServing.mua = data.current_serving.mua || '—';
                        }
                    })
                    .catch(err => console.error('Error fetching queue status:', err));
                }
            }
        }
    </script>
    @endpush
</x-pelanggan-layout>