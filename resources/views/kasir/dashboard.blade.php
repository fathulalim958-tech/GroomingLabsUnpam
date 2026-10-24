<x-kasir-layout title="Dashboard Kasir - Grooming Lab & MUA">
    <div class="space-y-6" x-data="kasirDashboard()">
        <!-- Top Category Navigation: Pisahkan Dashboard Barbershop dan MUA Studio -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-sm">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full {{ $activeCategory === 'barbershop' ? 'bg-amber-500 animate-pulse' : 'bg-pink-500 animate-pulse' }}"></span>
                <div>
                    <h1 class="text-sm sm:text-base lg:text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight">
                        OPERASIONAL HARI INI &bull; {{ $activeCategory === 'barbershop' ? 'BARBERSHOP' : 'MUA STUDIO' }}
                    </h1>
                    <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400">
                        {{ now()->translatedFormat('l, d F Y') }} &bull; Antrian & transaksi hari ini
                    </p>
                </div>
            </div>

            <!-- Category Switcher Tabs -->
            <div class="w-full sm:w-auto grid grid-cols-2 gap-1.5 bg-slate-100 dark:bg-slate-900 p-1 sm:p-1.5 rounded-xl border border-slate-200 dark:border-slate-750">
                <a href="{{ route('kasir.dashboard', ['category' => 'barbershop']) }}"
                   class="flex items-center justify-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-bold transition {{ $activeCategory === 'barbershop' ? 'bg-amber-500 text-slate-950 font-black shadow-md' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                    <span>💈 Barbershop</span>
                </a>
                <a href="{{ route('kasir.dashboard', ['category' => 'mua']) }}"
                   class="flex items-center justify-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-bold transition {{ $activeCategory === 'mua' ? 'bg-pink-500 text-white font-black shadow-md' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                    <span>💄 MUA Studio</span>
                </a>
            </div>
        </div>

        @if ($upcomingCount > 0)
            <!-- Upcoming reservations banner notice linking to Order menu -->
            <div class="p-3 sm:p-4 bg-indigo-500/10 border border-indigo-500/30 rounded-xl sm:rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 sm:gap-3 text-xs sm:text-sm text-indigo-900 dark:text-indigo-200 shadow-sm">
                <div class="flex items-center gap-2 sm:gap-3">
                    <span class="text-lg sm:text-xl">📅</span>
                    <div>
                        <span class="font-bold">Ada {{ $upcomingCount }} Reservasi Mendatang</span> yang telah dibooking oleh pelanggan untuk tanggal berikutnya.
                    </div>
                </div>
                <a href="{{ route('kasir.orders.index', ['view' => 'upcoming']) }}" class="self-start sm:self-auto px-3.5 py-1.5 sm:py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-lg sm:rounded-xl transition shadow text-xs sm:text-sm flex items-center gap-1.5 whitespace-nowrap">
                    <span>Lihat di Menu Order</span> &rarr;
                </a>
            </div>
        @endif

        <!-- Section KPI: Grid 2 Kolom Minimalis di HP (2 Kotak ke samping berpasangan kebawah) -->
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-4">
            <!-- Card 1: Total Hari Ini -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl p-3 sm:p-5 shadow-sm dark:shadow-md flex items-center justify-between">
                <div class="min-w-0 pr-1">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-300 block truncate">Total Hari Ini</span>
                    <div class="text-xl sm:text-3xl lg:text-4xl font-black text-slate-900 dark:text-white mt-0.5 sm:mt-1.5 font-mono">{{ $stats['total'] }}</div>
                </div>
                <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-slate-100 dark:bg-slate-700/80 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-600/60 flex items-center justify-center shadow-inner flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>

            <!-- Card 2: Menunggu -->
            <div class="bg-white dark:bg-slate-800 border-l-4 border-l-amber-500 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl p-3 sm:p-5 shadow-sm dark:shadow-md flex items-center justify-between">
                <div class="min-w-0 pr-1">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-amber-500 dark:text-amber-400 block truncate">Menunggu</span>
                    <div class="text-xl sm:text-3xl lg:text-4xl font-black text-amber-500 dark:text-amber-400 mt-0.5 sm:mt-1.5 font-mono">{{ $stats['waiting'] }}</div>
                </div>
                <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-amber-500/10 dark:bg-amber-500/20 text-amber-500 dark:text-amber-400 border border-amber-500/30 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <!-- Card 3: Dipanggil -->
            <div class="bg-white dark:bg-slate-800 border-l-4 border-l-indigo-500 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl p-3 sm:p-5 shadow-sm dark:shadow-md flex items-center justify-between">
                <div class="min-w-0 pr-1">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-indigo-500 dark:text-indigo-300 block truncate">Dipanggil</span>
                    <div class="text-xl sm:text-3xl lg:text-4xl font-black text-indigo-500 dark:text-indigo-300 mt-0.5 sm:mt-1.5 font-mono">{{ $stats['called'] }}</div>
                </div>
                <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-indigo-500/10 dark:bg-indigo-500/20 text-indigo-500 dark:text-indigo-300 border border-indigo-500/30 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                </div>
            </div>

            <!-- Card 4: Sedang Dilayani -->
            <div class="bg-white dark:bg-slate-800 border-l-4 border-l-sky-500 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl p-3 sm:p-5 shadow-sm dark:shadow-md flex items-center justify-between">
                <div class="min-w-0 pr-1">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400 block truncate">Sedang Dilayani</span>
                    <div class="text-xl sm:text-3xl lg:text-4xl font-black text-sky-600 dark:text-sky-400 mt-0.5 sm:mt-1.5 font-mono">{{ $stats['in_progress'] }}</div>
                </div>
                <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-sky-500/10 dark:bg-sky-500/20 text-sky-600 dark:text-sky-400 border border-sky-500/30 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879a3 3 0 11-4.242-4.242L7.757 7.757m0 0l-2.879-2.879a3 3 0 10-4.242 4.242L3.515 12" />
                    </svg>
                </div>
            </div>

            <!-- Card 5: Selesai -->
            <div class="bg-white dark:bg-slate-800 border-l-4 border-l-emerald-500 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl p-3 sm:p-5 shadow-sm dark:shadow-md flex items-center justify-between">
                <div class="min-w-0 pr-1">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 block truncate">Selesai</span>
                    <div class="text-xl sm:text-3xl lg:text-4xl font-black text-emerald-600 dark:text-emerald-400 mt-0.5 sm:mt-1.5 font-mono">{{ $stats['completed'] }}</div>
                </div>
                <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-emerald-500/10 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <!-- Card 6: Dibatalkan / Dilewati -->
            <div class="bg-white dark:bg-slate-800 border-l-4 border-l-rose-500 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl p-3 sm:p-5 shadow-sm dark:shadow-md flex items-center justify-between">
                <div class="min-w-0 pr-1">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-rose-500 dark:text-rose-400 block truncate">Dibatalkan</span>
                    <div class="text-xl sm:text-3xl lg:text-4xl font-black text-rose-500 dark:text-rose-400 mt-0.5 sm:mt-1.5 font-mono">{{ $stats['cancelled'] }}</div>
                </div>
                <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-rose-500/10 dark:bg-rose-500/20 text-rose-500 dark:text-rose-400 border border-rose-500/30 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Monitor Status: Kartu Highlight Pelayanan yang Sedang Berlangsung Hari Ini -->
        @if ($activeCategory === 'barbershop')
            <div class="bg-white dark:bg-slate-800 border-2 border-amber-500/70 rounded-2xl p-5 sm:p-6 shadow-lg shadow-amber-500/5 relative overflow-hidden flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="relative z-10">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-ping"></span>
                        <span class="text-xs font-black uppercase tracking-widest text-amber-500 dark:text-amber-400">Sedang Anda Layani &bull; Barbershop</span>
                    </div>
                    @if ($currentServing)
                        <div class="flex items-baseline gap-3 mt-2">
                            <span class="text-4xl sm:text-5xl font-black text-amber-500 dark:text-amber-400 font-mono tracking-tight">{{ $currentServing->queue_number }}</span>
                            <span class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white">{{ $currentServing->customer_name }}</span>
                        </div>
                        <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">
                            Layanan: <strong class="text-slate-900 dark:text-white">{{ $currentServing->items->map(fn($i) => $i->service->name)->join(', ') }}</strong>
                            @if ($currentServing->cashier)
                                &bull; <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">Kasir: {{ $currentServing->cashier_id === auth()->id() ? 'Anda' : $currentServing->cashier->name }}</span>
                            @endif
                        </p>
                    @else
                        <div class="text-2xl font-black text-slate-900 dark:text-white mt-2">Tidak ada antrian aktif di akun Anda</div>
                        <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">Gunakan tombol <strong>Panggil</strong> atau <strong>Layani</strong> pada antrian di bawah untuk mulai melayani pelanggan.</p>
                    @endif
                </div>

                @if ($currentServing)
                    <div class="relative z-10 flex-shrink-0">
                        <button type="button"
                                @click="openConfirm({
                                    actionUrl: '{{ route('kasir.queue.complete', $currentServing) }}',
                                    title: 'Konfirmasi Selesai & Bayar',
                                    message: 'Selesaikan sesi pelayanan untuk antrian ini dan konfirmasi pembayaran tagihan.',
                                    queueNumber: '{{ $currentServing->queue_number }}',
                                    customerName: '{{ addslashes($currentServing->customer_name) }}',
                                    serviceName: '{{ addslashes($currentServing->items->map(fn($i) => $i->service->name)->join(', ')) }}',
                                    totalPrice: 'Rp {{ number_format($currentServing->total_price, 0, ',', '.') }}',
                                    buttonText: 'Ya, Selesai & Bayar',
                                    buttonColor: 'green',
                                    icon: 'check'
                                })"
                                class="w-full sm:w-auto px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-black text-sm rounded-xl shadow-lg shadow-emerald-600/30 transition flex items-center justify-center gap-2 active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Selesai & Bayar</span>
                        </button>
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white dark:bg-slate-800 border-2 border-pink-500/70 rounded-2xl p-5 sm:p-6 shadow-lg shadow-pink-500/5 relative overflow-hidden flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="relative z-10">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-pink-400 animate-ping"></span>
                        <span class="text-xs font-black uppercase tracking-widest text-pink-500 dark:text-pink-400">Sedang Anda Layani &bull; MUA Studio</span>
                    </div>
                    @if ($currentServing)
                        <div class="flex items-baseline gap-3 mt-2">
                            <span class="text-4xl sm:text-5xl font-black text-pink-500 dark:text-pink-400 font-mono tracking-tight">{{ $currentServing->queue_number }}</span>
                            <span class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white">{{ $currentServing->customer_name }}</span>
                        </div>
                        <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">
                            Layanan: <strong class="text-slate-900 dark:text-white">{{ $currentServing->items->map(fn($i) => $i->service->name)->join(', ') }}</strong>
                            @if ($currentServing->cashier)
                                &bull; <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">Kasir: {{ $currentServing->cashier_id === auth()->id() ? 'Anda' : $currentServing->cashier->name }}</span>
                            @endif
                        </p>
                    @else
                        <div class="text-2xl font-black text-slate-900 dark:text-white mt-2">Tidak ada antrian aktif di akun Anda</div>
                        <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">Gunakan tombol <strong>Panggil</strong> atau <strong>Layani</strong> pada antrian di bawah untuk mulai melayani pelanggan.</p>
                    @endif
                </div>

                @if ($currentServing)
                    <div class="relative z-10 flex-shrink-0">
                        <button type="button"
                                @click="openConfirm({
                                    actionUrl: '{{ route('kasir.queue.complete', $currentServing) }}',
                                    title: 'Konfirmasi Selesai & Bayar',
                                    message: 'Selesaikan sesi pelayanan untuk antrian ini dan konfirmasi pembayaran tagihan.',
                                    queueNumber: '{{ $currentServing->queue_number }}',
                                    customerName: '{{ addslashes($currentServing->customer_name) }}',
                                    serviceName: '{{ addslashes($currentServing->items->map(fn($i) => $i->service->name)->join(', ')) }}',
                                    totalPrice: 'Rp {{ number_format($currentServing->total_price, 0, ',', '.') }}',
                                    buttonText: 'Ya, Selesai & Bayar',
                                    buttonColor: 'green',
                                    icon: 'check'
                                })"
                                class="w-full sm:w-auto px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-black text-sm rounded-xl shadow-lg shadow-emerald-600/30 transition flex items-center justify-center gap-2 active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Selesai & Bayar</span>
                        </button>
                    </div>
                @endif
            </div>
        @endif

        <!-- Filter & Search Toolbar Hari Ini -->
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-4 sm:p-5 space-y-3.5 shadow-sm dark:shadow-md">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300">Antrian Hari Ini</span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $activeCategory === 'barbershop' ? 'bg-amber-500/20 text-amber-600 dark:text-amber-400' : 'bg-pink-500/20 text-pink-600 dark:text-pink-400' }}">
                        {{ ucfirst($activeCategory) }} ({{ $bookings->count() }})
                    </span>
                </div>

                <!-- Auto refresh status indicator -->
                <div class="text-xs sm:text-sm text-slate-500 dark:text-slate-300 flex items-center gap-2 font-medium">
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                    <span>Auto-sync aktif (7s)</span>
                    <button type="button" onclick="location.reload()" class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg text-slate-500 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition" title="Refresh manual">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Status Filter Form -->
            <form method="GET" action="{{ route('kasir.dashboard') }}" class="flex flex-wrap items-center gap-3 pt-3 border-t border-slate-100 dark:border-slate-700/80">
                <input type="hidden" name="category" value="{{ $activeCategory }}">

                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Status Antrian:</label>
                    <select name="status" onchange="this.form.submit()"
                            class="px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-medium text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="waiting" {{ request('status') === 'waiting' ? 'selected' : '' }}>Menunggu</option>
                        <option value="called" {{ request('status') === 'called' ? 'selected' : '' }}>Dipanggil</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Sedang Dilayani</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                @if (request()->has('status'))
                    <a href="{{ route('kasir.dashboard', ['category' => $activeCategory]) }}" class="text-xs sm:text-sm font-bold text-sky-500 dark:text-sky-400 hover:underline px-2 py-1">
                        Reset Filter
                    </a>
                @endif
            </form>
        </div>

        <!-- Orders Table / Queue Control (Dengan 3 Aksi Utama: Panggil, Layani, Batalkan) -->
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl overflow-hidden shadow-lg">
            <!-- Mobile Card View (Khusus Tampilan Layar HP: Minimalis, Ringkas, Mudah Ditekan) -->
            <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-700/70">
                @forelse ($bookings as $b)
                    @php
                        $solidStatusClass = match($b->status) {
                            'waiting' => 'bg-amber-400 text-slate-950 font-black shadow-sm',
                            'called' => 'bg-indigo-600 text-white font-black shadow-sm animate-pulse',
                            'in_progress' => 'bg-sky-500 text-slate-950 font-black shadow-sm',
                            'skipped' => 'bg-purple-600 text-white font-bold shadow-sm',
                            'completed' => 'bg-emerald-500 text-slate-950 font-black shadow-sm',
                            'cancelled' => 'bg-rose-500 text-white font-black shadow-sm',
                            default => 'bg-slate-600 text-white font-bold',
                        };
                    @endphp
                    <div class="p-3.5 space-y-2.5 transition {{ $b->status === 'in_progress' ? 'bg-sky-50/70 dark:bg-sky-950/20' : ($b->status === 'called' ? 'bg-indigo-50/70 dark:bg-indigo-950/20' : '') }}">
                        <!-- Top Row: Queue number & status -->
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-black text-2xl {{ $b->service_category === 'barbershop' ? 'text-amber-500 dark:text-amber-400' : 'text-pink-500 dark:text-pink-400' }}">
                                    {{ $b->queue_number }}
                                </span>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono bg-slate-100 dark:bg-slate-750 px-2 py-0.5 rounded">
                                    {{ $b->booking_code }}
                                </span>
                            </div>
                            <div class="flex flex-col items-end gap-0.5">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] uppercase tracking-wider whitespace-nowrap inline-block {{ $solidStatusClass }}">
                                    {{ $b->status_label }}
                                </span>
                                @if ($b->cashier && $b->status === 'in_progress')
                                    <span class="text-[10px] text-sky-600 dark:text-sky-300 font-bold">
                                        {{ $b->cashier_id === auth()->id() ? '• Kasir: Anda' : '• Kasir: ' . $b->cashier->name }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Middle Row: Customer & Time -->
                        <div class="flex items-start justify-between gap-2 text-xs">
                            <div>
                                <div class="font-bold text-sm text-slate-900 dark:text-white">{{ $b->customer_name }}</div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">{{ $b->customer_phone ?? '-' }}</div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-slate-900 dark:text-white font-mono flex items-center justify-end gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    {{ $b->booking_time }} WIB
                                </div>
                                <div class="font-black text-xs text-slate-900 dark:text-white font-mono mt-0.5">
                                    Rp {{ number_format($b->total_price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <!-- Service detail -->
                        <div class="text-xs text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-900/60 p-2 rounded-lg border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500 dark:text-slate-400">Layanan:</span>
                            <span class="font-semibold text-slate-900 dark:text-slate-100">
                                {{ $b->items->map(fn($item) => $item->service->name)->join(', ') }}
                            </span>
                            @if ($b->notes)
                                <div class="text-[11px] text-amber-600 dark:text-amber-400 italic mt-0.5">
                                    Catatan: {{ $b->notes }}
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons Row (Touch-Friendly on Phone) -->
                        <div class="pt-1 flex items-center gap-1.5 flex-wrap">
                            @if (in_array($b->status, ['waiting', 'called', 'skipped']))
                                <button type="button"
                                        @click="openConfirm({
                                            actionUrl: '{{ route('kasir.queue.call', $b) }}',
                                            title: 'Konfirmasi Panggil Antrian',
                                            message: 'Panggil nomor antrian ke meja kasir atau kursi pelayanan dan kirim notifikasi aktif ke pelanggan.',
                                            queueNumber: '{{ $b->queue_number }}',
                                            customerName: '{{ addslashes($b->customer_name) }}',
                                            serviceName: '{{ addslashes($b->items->map(fn($item) => $item->service->name)->join(', ')) }}',
                                            buttonText: 'Ya, Panggil Antrian',
                                            buttonColor: 'blue',
                                            icon: 'bell'
                                        })"
                                        class="flex-1 min-w-[90px] py-2 px-2.5 {{ $b->status === 'called' ? 'bg-indigo-600 hover:bg-indigo-500 ring-2 ring-indigo-400' : 'bg-blue-600 hover:bg-blue-500' }} text-white text-xs font-bold rounded-lg transition shadow flex items-center justify-center gap-1 active:scale-95">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                    </svg>
                                    <span>{{ $b->status === 'called' ? 'Panggil Ulang' : 'Panggil' }}</span>
                                </button>

                                <button type="button"
                                        @click="openConfirm({
                                            actionUrl: '{{ route('kasir.queue.process', $b) }}',
                                            title: 'Konfirmasi Mulai Pelayanan',
                                            message: 'Mulai proses pelayanan untuk antrian ini sekarang.',
                                            queueNumber: '{{ $b->queue_number }}',
                                            customerName: '{{ addslashes($b->customer_name) }}',
                                            serviceName: '{{ addslashes($b->items->map(fn($item) => $item->service->name)->join(', ')) }}',
                                            buttonText: 'Ya, Mulai Layani',
                                            buttonColor: 'green',
                                            icon: 'play'
                                        })"
                                        class="flex-1 min-w-[90px] py-2 px-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-lg transition shadow flex items-center justify-center gap-1 active:scale-95">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879a3 3 0 11-4.242-4.242L7.757 7.757m0 0l-2.879-2.879a3 3 0 10-4.242 4.242L3.515 12" />
                                    </svg>
                                    <span>Layani</span>
                                </button>

                                <button type="button"
                                        @click="openConfirm({
                                            actionUrl: '{{ route('kasir.queue.cancel', $b) }}',
                                            title: 'Konfirmasi Pembatalan Antrian',
                                            message: 'Apakah Anda yakin ingin membatalkan antrian ini? Status antrian akan diubah menjadi Dibatalkan.',
                                            queueNumber: '{{ $b->queue_number }}',
                                            customerName: '{{ addslashes($b->customer_name) }}',
                                            serviceName: '{{ addslashes($b->items->map(fn($item) => $item->service->name)->join(', ')) }}',
                                            buttonText: 'Ya, Batalkan',
                                            buttonColor: 'red',
                                            icon: 'x'
                                        })"
                                        class="py-2 px-2.5 bg-rose-500/10 hover:bg-rose-600 text-rose-600 dark:text-rose-400 hover:text-white border border-rose-500/30 text-xs font-bold rounded-lg transition flex items-center justify-center gap-1 active:scale-95 flex-shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span class="sr-only sm:not-sr-only">Batal</span>
                                </button>
                            @endif

                            @if ($b->status === 'in_progress')
                                @if ($b->cashier_id === auth()->id() || is_null($b->cashier_id))
                                    <button type="button"
                                            @click="openConfirm({
                                                actionUrl: '{{ route('kasir.queue.complete', $b) }}',
                                                title: 'Konfirmasi Selesai & Bayar',
                                                message: 'Selesaikan sesi pelayanan untuk antrian ini dan konfirmasi pembayaran tagihan.',
                                                queueNumber: '{{ $b->queue_number }}',
                                                customerName: '{{ addslashes($b->customer_name) }}',
                                                serviceName: '{{ addslashes($b->items->map(fn($item) => $item->service->name)->join(', ')) }}',
                                                totalPrice: 'Rp {{ number_format($b->total_price, 0, ',', '.') }}',
                                                buttonText: 'Ya, Selesai & Bayar',
                                                buttonColor: 'green',
                                                icon: 'check'
                                            })"
                                            class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-black rounded-lg transition shadow shadow-emerald-600/30 flex items-center justify-center gap-1.5 active:scale-95">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Selesai & Bayar</span>
                                    </button>
                                @else
                                    <div class="w-full py-2 px-3 text-center text-xs font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-750 rounded-lg border border-slate-200 dark:border-slate-700 flex items-center justify-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-sky-500 animate-pulse"></span>
                                        <span>Diproses oleh {{ $b->cashier ? $b->cashier->name : 'Kasir Lain' }}</span>
                                    </div>
                                @endif
                            @endif

                            @if ($b->status === 'completed')
                                <div class="w-full py-1.5 text-center text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 rounded-lg">
                                    ✓ Transaksi Selesai
                                </div>
                            @elseif ($b->status === 'cancelled')
                                <div class="w-full py-1.5 text-center text-xs font-bold text-rose-500 dark:text-rose-400 bg-rose-500/10 rounded-lg">
                                    &times; Antrian Dibatalkan
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center text-slate-500 dark:text-slate-400 px-4">
                        <div class="text-3xl">📋</div>
                        <p class="font-bold text-sm text-slate-700 dark:text-slate-300 mt-2">Belum ada antrian {{ ucfirst($activeCategory) }} hari ini.</p>
                        <p class="text-xs text-slate-500 mt-0.5">Antrian akan otomatis tampil di sini saat masuk.</p>
                    </div>
                @endforelse
            </div>

            <!-- Desktop View: Tabel Lengkap -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <!-- Table Header dengan Kontras Tinggi di Dark & Light Mode -->
                    <thead class="bg-slate-200 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-black text-xs uppercase tracking-wider border-b border-slate-300 dark:border-slate-800">
                        <tr>
                            <th class="py-4 px-4 font-black">No. Antrian</th>
                            <th class="py-4 px-4 font-black">Pelanggan</th>
                            <th class="py-4 px-4 font-black">Layanan</th>
                            <th class="py-4 px-4 font-black">Waktu</th>
                            <th class="py-4 px-4 font-black">Total</th>
                            <th class="py-4 px-4 font-black">Status</th>
                            <th class="py-4 px-4 font-black text-center">Kontrol Antrian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/70">
                        @forelse ($bookings as $b)
                            @php
                                $solidStatusClass = match($b->status) {
                                    'waiting' => 'bg-amber-400 text-slate-950 font-black shadow-sm',
                                    'called' => 'bg-indigo-600 text-white font-black shadow-sm animate-pulse',
                                    'in_progress' => 'bg-sky-500 text-slate-950 font-black shadow-sm',
                                    'skipped' => 'bg-purple-600 text-white font-bold shadow-sm',
                                    'completed' => 'bg-emerald-500 text-slate-950 font-black shadow-sm',
                                    'cancelled' => 'bg-rose-500 text-white font-black shadow-sm',
                                    default => 'bg-slate-600 text-white font-bold',
                                };
                            @endphp
                            <tr class="hover:bg-slate-100/70 dark:hover:bg-slate-700/60 transition {{ $b->status === 'in_progress' ? 'bg-blue-50/60 dark:bg-blue-500/10' : ($b->status === 'called' ? 'bg-indigo-50/60 dark:bg-indigo-500/10' : '') }}">
                                <!-- Queue Number -->
                                <td class="py-4 px-4">
                                    <div class="font-mono font-black text-xl {{ $b->service_category === 'barbershop' ? 'text-amber-500 dark:text-amber-400' : 'text-pink-500 dark:text-pink-400' }}">
                                        {{ $b->queue_number }}
                                    </div>
                                    <span class="text-xs text-slate-500 dark:text-slate-300 font-mono tracking-tight">{{ $b->booking_code }}</span>
                                </td>

                                <!-- Customer -->
                                <td class="py-4 px-4">
                                    <div class="font-bold text-base text-slate-900 dark:text-white">{{ $b->customer_name }}</div>
                                    <div class="text-sm text-slate-500 dark:text-slate-300 font-mono font-medium">{{ $b->customer_phone ?? '-' }}</div>
                                </td>

                                <!-- Services & Notes -->
                                <td class="py-4 px-4 max-w-xs">
                                    <div class="font-semibold text-base text-slate-900 dark:text-slate-100">
                                        {{ $b->items->map(fn($item) => $item->service->name)->join(', ') }}
                                    </div>
                                    @if ($b->notes)
                                        <div class="text-xs text-amber-700 dark:text-amber-300 font-medium italic mt-1 truncate" title="{{ $b->notes }}">
                                            📝 {{ $b->notes }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Time -->
                                <td class="py-4 px-4 text-sm font-mono text-slate-700 dark:text-slate-300">
                                    <div class="font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        {{ $b->formatted_booking_time }} WIB
                                    </div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Hari Ini</div>
                                </td>

                                <!-- Total Price -->
                                <td class="py-4 px-4 font-black text-base text-slate-900 dark:text-white font-mono">
                                    Rp {{ number_format($b->total_price, 0, ',', '.') }}
                                </td>

                                <!-- Solid Status Badge -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="px-3.5 py-1 rounded-full text-xs uppercase tracking-wider inline-block whitespace-nowrap {{ $solidStatusClass }}">
                                        {{ $b->status_label }}
                                    </span>
                                    @if ($b->cashier && $b->status === 'in_progress')
                                        <div class="text-[11px] text-sky-600 dark:text-sky-300 font-bold mt-1 whitespace-nowrap">
                                            {{ $b->cashier_id === auth()->id() ? '• Kasir: Anda' : '• Kasir: ' . $b->cashier->name }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Queue Control: 3 Aksi Utama (Panggil, Layani, Batalkan) dengan Konfirmasi Pop-up -->
                                <td class="py-4 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2 flex-wrap">
                                        <!-- AKSI 1: PANGGIL (Status -> Dipanggil, kirim notifikasi/pesan) -->
                                        @if (in_array($b->status, ['waiting', 'called', 'skipped']))
                                            <button type="button"
                                                    @click="openConfirm({
                                                        actionUrl: '{{ route('kasir.queue.call', $b) }}',
                                                        title: 'Konfirmasi Panggil Antrian',
                                                        message: 'Panggil nomor antrian ke meja kasir atau kursi pelayanan dan kirim notifikasi aktif ke pelanggan.',
                                                        queueNumber: '{{ $b->queue_number }}',
                                                        customerName: '{{ addslashes($b->customer_name) }}',
                                                        serviceName: '{{ addslashes($b->items->map(fn($item) => $item->service->name)->join(', ')) }}',
                                                        buttonText: 'Ya, Panggil Antrian',
                                                        buttonColor: 'blue',
                                                        icon: 'bell'
                                                    })"
                                                    class="px-3.5 py-2 {{ $b->status === 'called' ? 'bg-indigo-600 hover:bg-indigo-500 ring-2 ring-indigo-400' : 'bg-blue-600 hover:bg-blue-500' }} text-white text-xs sm:text-sm font-bold rounded-xl transition shadow shadow-blue-600/30 flex items-center gap-1.5 active:scale-95"
                                                    title="Panggil nomor antrian ini ke meja kasir/kursi pelayanan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                                </svg>
                                                <span>{{ $b->status === 'called' ? 'Panggil Ulang' : 'Panggil' }}</span>
                                            </button>
                                        @endif

                                        <!-- AKSI 2: LAYANI (Status -> In Progress, masuk ke pelayanan berlangsung) -->
                                        @if (in_array($b->status, ['waiting', 'called', 'skipped']))
                                            <button type="button"
                                                    @click="openConfirm({
                                                        actionUrl: '{{ route('kasir.queue.process', $b) }}',
                                                        title: 'Konfirmasi Mulai Pelayanan',
                                                        message: 'Mulai proses pelayanan untuk antrian ini sekarang.',
                                                        queueNumber: '{{ $b->queue_number }}',
                                                        customerName: '{{ addslashes($b->customer_name) }}',
                                                        serviceName: '{{ addslashes($b->items->map(fn($item) => $item->service->name)->join(', ')) }}',
                                                        buttonText: 'Ya, Mulai Layani',
                                                        buttonColor: 'green',
                                                        icon: 'play'
                                                    })"
                                                    class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs sm:text-sm font-bold rounded-xl transition shadow shadow-emerald-600/30 flex items-center gap-1.5 active:scale-95"
                                                    title="Mulai sesi layanan untuk antrian ini">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879a3 3 0 11-4.242-4.242L7.757 7.757m0 0l-2.879-2.879a3 3 0 10-4.242 4.242L3.515 12" />
                                                </svg>
                                                <span>Layani</span>
                                            </button>
                                        @endif

                                        <!-- AKSI 3: BATALKAN (Dengan konfirmasi pembatalan pop-up) -->
                                        @if (in_array($b->status, ['waiting', 'called', 'skipped']))
                                            <button type="button"
                                                    @click="openConfirm({
                                                        actionUrl: '{{ route('kasir.queue.cancel', $b) }}',
                                                        title: 'Konfirmasi Pembatalan Antrian',
                                                        message: 'Apakah Anda yakin ingin membatalkan antrian ini? Status antrian akan diubah menjadi Dibatalkan.',
                                                        queueNumber: '{{ $b->queue_number }}',
                                                        customerName: '{{ addslashes($b->customer_name) }}',
                                                        serviceName: '{{ addslashes($b->items->map(fn($item) => $item->service->name)->join(', ')) }}',
                                                        buttonText: 'Ya, Batalkan',
                                                        buttonColor: 'red',
                                                        icon: 'x'
                                                    })"
                                                    class="px-3 py-2 bg-rose-500/10 hover:bg-rose-600 text-rose-600 dark:text-rose-400 hover:text-white border border-rose-500/30 text-xs sm:text-sm font-bold rounded-xl transition flex items-center gap-1 active:scale-95"
                                                    title="Batalkan antrian ini">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                <span>Batalkan</span>
                                            </button>
                                        @endif

                                        <!-- JIKA SEDANG DILAYANI: SELESAI & BAYAR (Hanya kasir yang menangani) -->
                                        @if ($b->status === 'in_progress')
                                            @if ($b->cashier_id === auth()->id() || is_null($b->cashier_id))
                                                <button type="button"
                                                        @click="openConfirm({
                                                            actionUrl: '{{ route('kasir.queue.complete', $b) }}',
                                                            title: 'Konfirmasi Selesai & Bayar',
                                                            message: 'Selesaikan sesi pelayanan untuk antrian ini dan konfirmasi pembayaran tagihan.',
                                                            queueNumber: '{{ $b->queue_number }}',
                                                            customerName: '{{ addslashes($b->customer_name) }}',
                                                            serviceName: '{{ addslashes($b->items->map(fn($item) => $item->service->name)->join(', ')) }}',
                                                            totalPrice: 'Rp {{ number_format($b->total_price, 0, ',', '.') }}',
                                                            buttonText: 'Ya, Selesai & Bayar',
                                                            buttonColor: 'green',
                                                            icon: 'check'
                                                        })"
                                                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs sm:text-sm font-black rounded-xl transition shadow shadow-emerald-600/30 flex items-center gap-1.5 active:scale-95">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>Selesai & Bayar</span>
                                                </button>
                                            @else
                                                <div class="px-3 py-2 text-xs font-semibold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-750 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center gap-1.5"
                                                     title="Antrian ini hanya dapat diselesaikan oleh kasir yang menanganinya">
                                                    <span class="w-2 h-2 rounded-full bg-sky-500 animate-pulse"></span>
                                                    <span>Diproses oleh {{ $b->cashier ? $b->cashier->name : 'Kasir Lain' }}</span>
                                                </div>
                                            @endif
                                        @endif

                                        <!-- JIKA SELESAI ATAU DIBATALKAN -->
                                        @if ($b->status === 'completed')
                                            <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                Selesai
                                            </span>
                                        @elseif ($b->status === 'cancelled')
                                            <span class="text-xs font-semibold text-rose-500 dark:text-rose-400">
                                                &times; Dibatalkan
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-500 dark:text-slate-400">
                                    <div class="max-w-xs mx-auto space-y-2">
                                        <div class="text-3xl">📋</div>
                                        <p class="font-bold text-sm text-slate-700 dark:text-slate-300">Belum ada antrian masuk untuk {{ ucfirst($activeCategory) }} hari ini.</p>
                                        <p class="text-xs">Antrian walk-in atau booking pelanggan untuk hari ini akan otomatis tampil di sini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pop-up Modal Konfirmasi Aksi Antrian (Panggil, Layani, Batalkan, Selesai & Bayar) -->
        <div x-show="confirmModal.show"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             role="dialog"
             aria-modal="true"
             style="display: none;">
            <!-- Backdrop Overlay -->
            <div x-show="confirmModal.show"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="closeConfirm()"
                 class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm transition-opacity"></div>

            <div class="min-h-full flex items-center justify-center p-4 text-center sm:p-0">
                <div x-show="confirmModal.show"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     @click.away="closeConfirm()"
                     class="relative bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700/80 text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-md my-8">

                    <!-- Modal Header -->
                    <div class="px-5 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-slate-100 dark:border-slate-750">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <!-- Dynamic Icon -->
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-lg"
                                     :class="{
                                         'bg-blue-500/10 text-blue-600 dark:text-blue-400': confirmModal.buttonColor === 'blue',
                                         'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400': confirmModal.buttonColor === 'green',
                                         'bg-rose-500/10 text-rose-600 dark:text-rose-400': confirmModal.buttonColor === 'red'
                                     }">
                                    <template x-if="confirmModal.icon === 'bell'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                        </svg>
                                    </template>
                                    <template x-if="confirmModal.icon === 'play'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879a3 3 0 11-4.242-4.242L7.757 7.757m0 0l-2.879-2.879a3 3 0 10-4.242 4.242L3.515 12" />
                                        </svg>
                                    </template>
                                    <template x-if="confirmModal.icon === 'x'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </template>
                                    <template x-if="confirmModal.icon === 'check'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </template>
                                </div>
                                <h3 class="text-base sm:text-lg font-black text-slate-900 dark:text-white" x-text="confirmModal.title"></h3>
                            </div>
                            <button type="button" @click="closeConfirm()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-5 py-4 sm:p-6 space-y-4">
                        <!-- Card Ringkasan Antrian -->
                        <div class="bg-slate-50 dark:bg-slate-900/90 rounded-xl p-3.5 border border-slate-200/80 dark:border-slate-700/60 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Nomor Antrian</span>
                                <span class="font-mono font-black text-lg text-amber-500 dark:text-amber-400" x-text="confirmModal.queueNumber"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Pelanggan</span>
                                <span class="font-bold text-sm text-slate-900 dark:text-white" x-text="confirmModal.customerName"></span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-500 dark:text-slate-400 font-medium">Layanan</span>
                                <span class="font-semibold text-slate-700 dark:text-slate-300 text-right max-w-[200px] truncate" x-text="confirmModal.serviceName"></span>
                            </div>
                            <template x-if="confirmModal.totalPrice">
                                <div class="flex items-center justify-between pt-1 border-t border-slate-200 dark:border-slate-800">
                                    <span class="text-xs font-bold text-slate-600 dark:text-slate-400">Total Tagihan</span>
                                    <span class="font-mono font-black text-base text-emerald-600 dark:text-emerald-400" x-text="confirmModal.totalPrice"></span>
                                </div>
                            </template>
                        </div>

                        <!-- Confirmation Message -->
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed" x-text="confirmModal.message"></p>
                    </div>

                    <!-- Modal Actions -->
                    <div class="px-5 py-4 sm:px-6 bg-slate-50/70 dark:bg-slate-900/60 border-t border-slate-100 dark:border-slate-750 flex items-center justify-end gap-2.5">
                        <button type="button"
                                @click="closeConfirm()"
                                class="px-4 py-2 text-xs sm:text-sm font-bold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-200/80 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-650 rounded-xl transition">
                            Batal
                        </button>
                        <form method="POST" :action="confirmModal.actionUrl" class="inline">
                            @csrf
                            <button type="submit"
                                    class="px-4 py-2 text-xs sm:text-sm font-black text-white rounded-xl transition shadow active:scale-95"
                                    :class="{
                                        'bg-blue-600 hover:bg-blue-500 shadow-blue-600/30': confirmModal.buttonColor === 'blue',
                                        'bg-emerald-600 hover:bg-emerald-500 shadow-emerald-600/30': confirmModal.buttonColor === 'green',
                                        'bg-rose-600 hover:bg-rose-500 shadow-rose-600/30': confirmModal.buttonColor === 'red'
                                    }"
                                    x-text="confirmModal.buttonText">
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function kasirDashboard() {
            return {
                confirmModal: {
                    show: false,
                    actionUrl: '',
                    title: '',
                    message: '',
                    queueNumber: '',
                    customerName: '',
                    serviceName: '',
                    totalPrice: null,
                    buttonText: 'Konfirmasi',
                    buttonColor: 'blue',
                    icon: 'bell'
                },
                openConfirm(config) {
                    this.confirmModal = {
                        show: true,
                        actionUrl: config.actionUrl || '',
                        title: config.title || 'Konfirmasi Tindakan',
                        message: config.message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
                        queueNumber: config.queueNumber || '-',
                        customerName: config.customerName || '-',
                        serviceName: config.serviceName || '-',
                        totalPrice: config.totalPrice || null,
                        buttonText: config.buttonText || 'Konfirmasi',
                        buttonColor: config.buttonColor || 'blue',
                        icon: config.icon || 'bell'
                    };
                },
                closeConfirm() {
                    this.confirmModal.show = false;
                },
                init() {
                    // Poll order updates every 7 seconds, tapi jangan reload jika modal konfirmasi sedang terbuka
                    setInterval(() => {
                        this.refreshOrders();
                    }, 7000);
                },
                refreshOrders() {
                    if (!document.hidden && !this.confirmModal.show) {
                        location.reload();
                    }
                }
            }
        }
    </script>
    @endpush
</x-kasir-layout>
