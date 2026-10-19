<x-kasir-layout title="Dashboard Kasir - Grooming Lab & MUA">
    <div class="space-y-6" x-data="kasirDashboard()">
        <!-- Top Status & Summary Metrics -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Total Order Hari Ini</span>
                <div class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $stats['total'] }}</div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-amber-300 dark:border-amber-500/30 rounded-2xl p-4 shadow-sm">
                <span class="text-xs font-semibold text-amber-600 dark:text-amber-400">Menunggu (Waiting)</span>
                <div class="text-2xl font-black text-amber-600 dark:text-amber-300 mt-1">{{ $stats['waiting'] }}</div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-blue-300 dark:border-blue-500/30 rounded-2xl p-4 shadow-sm">
                <span class="text-xs font-semibold text-blue-600 dark:text-blue-400">Sedang Diproses</span>
                <div class="text-2xl font-black text-blue-600 dark:text-blue-300 mt-1">{{ $stats['in_progress'] }}</div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-purple-300 dark:border-purple-500/30 rounded-2xl p-4 shadow-sm">
                <span class="text-xs font-semibold text-purple-600 dark:text-purple-400">Dilewati (Skipped)</span>
                <div class="text-2xl font-black text-purple-600 dark:text-purple-300 mt-1">{{ $stats['skipped'] }}</div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-emerald-300 dark:border-emerald-500/30 rounded-2xl p-4 col-span-2 sm:col-span-1 shadow-sm">
                <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Selesai (Completed)</span>
                <div class="text-2xl font-black text-emerald-600 dark:text-emerald-300 mt-1">{{ $stats['completed'] }}</div>
            </div>
        </div>

        <!-- Current Active Queues (At-A-Glance Barbershop & MUA) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Barbershop Serving Card -->
            <div class="bg-gradient-to-r from-amber-50 via-white to-amber-100/30 dark:from-slate-900 dark:via-slate-900 dark:to-amber-950/40 border-2 border-amber-300 dark:border-amber-500/40 rounded-2xl p-4 sm:p-5 flex items-center justify-between shadow-sm">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping"></span>
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400">Sedang Berlangsung &bull; Barbershop</span>
                    </div>
                    @if ($currentServing['barbershop'])
                        <div class="flex items-baseline gap-3 mt-1">
                            <span class="text-3xl font-black text-amber-600 dark:text-amber-300 font-mono">{{ $currentServing['barbershop']->queue_number }}</span>
                            <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $currentServing['barbershop']->user->name }}</span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                            Layanan: {{ $currentServing['barbershop']->items->map(fn($i) => $i->service->name)->join(', ') }}
                        </p>
                    @else
                        <div class="text-xl font-bold text-slate-500 dark:text-slate-500 mt-1">Tidak ada antrian aktif</div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Panggil antrian berikutnya di tabel di bawah</p>
                    @endif
                </div>

                @if ($currentServing['barbershop'])
                    <form method="POST" action="{{ route('kasir.queue.complete', $currentServing['barbershop']) }}">
                        @csrf
                        <button type="submit" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Selesai & Bayar</span>
                        </button>
                    </form>
                @endif
            </div>

            <!-- MUA Serving Card -->
            <div class="bg-gradient-to-r from-pink-50 via-white to-pink-100/30 dark:from-slate-900 dark:via-slate-900 dark:to-pink-950/40 border-2 border-pink-300 dark:border-pink-500/40 rounded-2xl p-4 sm:p-5 flex items-center justify-between shadow-sm">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-pink-500 animate-ping"></span>
                        <span class="text-xs font-bold uppercase tracking-wider text-pink-700 dark:text-pink-400">Sedang Berlangsung &bull; MUA Studio</span>
                    </div>
                    @if ($currentServing['mua'])
                        <div class="flex items-baseline gap-3 mt-1">
                            <span class="text-3xl font-black text-pink-600 dark:text-pink-300 font-mono">{{ $currentServing['mua']->queue_number }}</span>
                            <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $currentServing['mua']->user->name }}</span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                            Layanan: {{ $currentServing['mua']->items->map(fn($i) => $i->service->name)->join(', ') }}
                        </p>
                    @else
                        <div class="text-xl font-bold text-slate-500 dark:text-slate-500 mt-1">Tidak ada antrian aktif</div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Panggil antrian berikutnya di tabel di bawah</p>
                    @endif
                </div>

                @if ($currentServing['mua'])
                    <form method="POST" action="{{ route('kasir.queue.complete', $currentServing['mua']) }}">
                        @csrf
                        <button type="submit" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Selesai & Bayar</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Filter & Search Toolbar (FR-CASH-01) -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 flex flex-wrap items-center justify-between gap-3 shadow-sm">
            <form method="GET" action="{{ route('kasir.dashboard') }}" class="flex flex-wrap items-center gap-2">
                <!-- Category Filter -->
                <select name="category" onchange="this.form.submit()"
                        class="px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    <option value="barbershop" {{ request('category') === 'barbershop' ? 'selected' : '' }}>Barbershop</option>
                    <option value="mua" {{ request('category') === 'mua' ? 'selected' : '' }}>MUA Studio</option>
                </select>

                <!-- Status Filter -->
                <select name="status" onchange="this.form.submit()"
                        class="px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="waiting" {{ request('status') === 'waiting' ? 'selected' : '' }}>Menunggu</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Proses</option>
                    <option value="skipped" {{ request('status') === 'skipped' ? 'selected' : '' }}>Dilewati</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>

                @if (request()->hasAny(['category', 'status']))
                    <a href="{{ route('kasir.dashboard') }}" class="text-xs text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white underline px-2 py-1">
                        Reset Filter
                    </a>
                @endif
            </form>

            <!-- Auto refresh status indicator -->
            <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                <span>Auto-sync aktif (7s)</span>
                <button type="button" onclick="location.reload()" class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white" title="Refresh manual">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Orders Table / Queue Control (FR-CASH-01, FR-CASH-02, FR-CASH-03) -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm dark:shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-950/70 text-xs uppercase tracking-wider text-slate-600 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="py-3 px-4">No. Antrian</th>
                            <th class="py-3 px-4">Pelanggan</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4">Layanan</th>
                            <th class="py-3 px-4">Jam</th>
                            <th class="py-3 px-4">Total</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-center">Kontrol Antrian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        @forelse ($bookings as $b)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition {{ $b->status === 'in_progress' ? 'bg-blue-50/60 dark:bg-blue-500/5' : ($b->status === 'skipped' ? 'bg-amber-50/60 dark:bg-amber-500/5' : '') }}">
                                <!-- Queue Number -->
                                <td class="py-3.5 px-4">
                                    <div class="font-mono font-black text-base {{ $b->service_category === 'barbershop' ? 'text-amber-600 dark:text-amber-400' : 'text-pink-600 dark:text-pink-400' }}">
                                        {{ $b->queue_number }}
                                    </div>
                                    <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">{{ $b->booking_code }}</span>
                                </td>

                                <!-- Customer -->
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $b->user->name }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ $b->user->phone ?? '-' }}</div>
                                </td>

                                <!-- Category -->
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $b->service_category === 'barbershop' ? 'bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/20' : 'bg-pink-500/10 text-pink-700 dark:text-pink-400 border border-pink-500/20' }}">
                                        {{ ucfirst($b->service_category) }}
                                    </span>
                                </td>

                                <!-- Services & Notes -->
                                <td class="py-3.5 px-4 max-w-xs">
                                    <div class="font-medium text-slate-800 dark:text-slate-200">
                                        {{ $b->items->map(fn($item) => $item->service->name)->join(', ') }}
                                    </div>
                                    @if ($b->notes)
                                        <div class="text-xs text-slate-500 dark:text-slate-400 italic mt-0.5 truncate" title="{{ $b->notes }}">
                                            📝 {{ $b->notes }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Time -->
                                <td class="py-3.5 px-4 text-xs font-mono text-slate-700 dark:text-slate-300">
                                    {{ $b->booking_time }} WIB
                                </td>

                                <!-- Total Price -->
                                <td class="py-3.5 px-4 font-bold text-slate-900 dark:text-slate-100">
                                    Rp {{ number_format($b->total_price, 0, ',', '.') }}
                                </td>

                                <!-- Status Badge -->
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold inline-block {{ $b->status_color }}">
                                        {{ $b->status_label }}
                                    </span>
                                </td>

                                <!-- Queue Control Actions (FR-CASH-02 & FR-CASH-03) -->
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                        <!-- If WAITING: Panggil / Proses & Lewati (Skip) -->
                                        @if ($b->status === 'waiting')
                                            <!-- Panggil / Proses -->
                                            <form method="POST" action="{{ route('kasir.queue.process', $b) }}">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-lg transition shadow">
                                                    Panggil
                                                </button>
                                            </form>

                                            <!-- Lewati (Skip) -->
                                            <form method="POST" action="{{ route('kasir.queue.skip', $b) }}">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1.5 bg-amber-600/10 hover:bg-amber-600 text-amber-700 hover:text-white dark:bg-amber-600/30 dark:hover:bg-amber-600 dark:text-amber-300 dark:hover:text-white border border-amber-300 dark:border-amber-600/50 text-xs font-semibold rounded-lg transition" title="Pelanggan belum hadir, lewati antrian tanpa membatalkan">
                                                    Lewati
                                                </button>
                                            </form>
                                        @endif

                                        <!-- If IN_PROGRESS: Selesai -->
                                        @if ($b->status === 'in_progress')
                                            <form method="POST" action="{{ route('kasir.queue.complete', $b) }}">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-lg transition shadow flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>Selesai</span>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- If SKIPPED: Recall (Pemanggilan Ulang) -->
                                        @if ($b->status === 'skipped')
                                            <form method="POST" action="{{ route('kasir.queue.process', $b) }}">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold rounded-lg transition shadow flex items-center gap-1" title="Pelanggan sudah datang, panggil ulang antrian">
                                                    <span>Panggil Ulang</span>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Cancel option for waiting/skipped -->
                                        @if (in_array($b->status, ['waiting', 'skipped']))
                                            <form method="POST" action="{{ route('kasir.queue.cancel', $b) }}" onsubmit="return confirm('Batalkan pesanan {{ $b->queue_number }}?');">
                                                @csrf
                                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition" title="Batalkan Pesanan">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif

                                        @if (in_array($b->status, ['completed', 'cancelled']))
                                            <span class="text-xs text-slate-500 font-medium">&check; Terproses</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-slate-500 text-sm">
                                    Belum ada antrian masuk untuk kriteria yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function kasirDashboard() {
            return {
                init() {
                    // Poll order updates every 7 seconds
                    setInterval(() => {
                        this.refreshOrders();
                    }, 7000);
                },
                refreshOrders() {
                    if (!document.hidden) {
                        location.reload();
                    }
                }
            }
        }
    </script>
    @endpush
</x-kasir-layout>
