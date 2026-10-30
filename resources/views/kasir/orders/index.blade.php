<x-kasir-layout title="Order Mendatang - Grooming Lab">
    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-4 sm:p-5 shadow-sm">
            <div>
                <h1 class="text-sm sm:text-base lg:text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight flex items-center gap-2">
                    <span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-amber-500 animate-pulse"></span>
                    MANAJEMEN ORDER MENDATANG
                </h1>
                <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Pesanan mendatang (belum dilayani). Menampilkan jadwal order untuk <strong>Besok</strong> secara default, dapat difilter hingga 7 hari ke depan.
                </p>
            </div>

            <div>
                <span class="px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-xl bg-amber-500/15 border border-amber-500/30 text-amber-500 dark:text-amber-400 text-[11px] sm:text-xs font-bold flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Jadwal: {{ $selectedDate ? \Illuminate\Support\Carbon::parse($selectedDate)->translatedFormat('l, d M Y') : 'Semua Mendatang' }}</span>
                </span>
            </div>
        </div>

        <!-- Filter & Search Toolbar (Pilih Tanggal s/d 7 Hari Ke Depan) -->
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-4 sm:p-5 shadow-sm">
            <form method="GET" action="{{ route('kasir.orders.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- Date Picker Filter (Harian s/d 7 Hari Ke Depan) -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase mb-1 flex items-center justify-between">
                        <span>Pilih Tanggal:</span>
                        @if ($selectedDate !== $tomorrow)
                            <a href="{{ route('kasir.orders.index', ['date' => $tomorrow, 'category' => $category]) }}" class="text-[10px] text-amber-500 hover:underline font-semibold">Kembali ke Besok</a>
                        @endif
                    </label>
                    <input type="date" name="date" value="{{ $selectedDate }}" onchange="this.form.submit()"
                           min="{{ $today->format('Y-m-d') }}"
                           max="{{ $max7Days }}"
                           class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 font-mono">
                    <span class="text-[10px] text-slate-400 mt-1 block">Tersedia jadwal hari ini s/d 7 hari ke depan</span>
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase mb-1">Kategori:</label>
                    <select name="category" onchange="this.form.submit()"
                            class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500">
                        <option value="all">Semua Kategori</option>
                        <option value="barbershop" {{ $category === 'barbershop' ? 'selected' : '' }}>Barbershop</option>
                        <option value="mua" {{ $category === 'mua' ? 'selected' : '' }}>MUA Studio</option>
                    </select>
                </div>

                <!-- Search Input -->
                <div class="sm:col-span-2 lg:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase mb-1">Cari Pelanggan / Kode:</label>
                    <div class="flex gap-2">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Nama, HP, atau kode booking..."
                               class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500">
                        <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs rounded-xl shadow transition whitespace-nowrap">
                            Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Order Mendatang -->
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl overflow-hidden shadow-lg">
            <!-- Mobile View: Kartu Order Ringkas di HP -->
            <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-700/70">
                @forelse ($orders as $order)
                    <div class="p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-black text-base {{ $order->service_category === 'barbershop' ? 'text-amber-500 dark:text-amber-400' : 'text-pink-500 dark:text-pink-400' }}">
                                    {{ $order->queue_number }}
                                </span>
                                <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">#{{ $order->booking_code }}</span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] uppercase tracking-wider font-bold bg-amber-500/20 text-amber-500 border border-amber-500/40">
                                {{ $order->status_label }}
                            </span>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="relative flex-shrink-0">
                                @if($order->user?->avatar)
                                    <img src="{{ asset('storage/' . $order->user->avatar) }}" alt="{{ $order->customer_name }}" class="w-10 h-10 rounded-xl object-cover ring-1 ring-amber-500/50 shadow-sm">
                                @else
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500/20 via-slate-100 dark:via-slate-800 to-amber-500/10 ring-1 ring-amber-500/30 flex items-center justify-center text-amber-500 font-black text-xs shadow-sm flex-shrink-0">
                                        {{ strtoupper(substr($order->customer_name, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="font-bold text-sm text-slate-900 dark:text-white">{{ $order->customer_name }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 font-mono">{{ $order->customer_phone ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="text-xs text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-900/60 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700/60">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-bold uppercase {{ $order->service_category === 'barbershop' ? 'text-amber-500' : 'text-pink-400' }}">
                                    {{ ucfirst($order->service_category) }}
                                </span>
                                <span class="font-mono font-bold text-slate-900 dark:text-white">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="text-slate-600 dark:text-slate-300 font-medium">
                                {{ $order->items->map(fn($item) => $item->service->name)->join(', ') }}
                            </div>
                            <div class="mt-1.5 flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 font-mono border-t border-slate-200 dark:border-slate-700 pt-1">
                                <span>📅 {{ $order->booking_date->translatedFormat('d M Y') }}</span>
                                <span>⏰ {{ $order->booking_time }} WIB</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-1">
                            <form method="POST" action="{{ route('kasir.queue.cancel', $order) }}"
                                  onsubmit="return confirm('Batalkan pesanan {{ $order->booking_code }} ({{ $order->customer_name }})?');">
                                @csrf
                                <button type="submit"
                                        class="px-3 py-1.5 bg-rose-500/10 hover:bg-rose-600 text-rose-500 hover:text-white border border-rose-500/30 text-xs font-bold rounded-lg transition">
                                    Batalkan
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-500 dark:text-slate-400 p-4">
                        <div class="text-3xl mb-2">⏳</div>
                        <p class="font-bold text-sm text-slate-700 dark:text-slate-300">Tidak ada orderan mendatang.</p>
                        <p class="text-xs mt-1">Seluruh orderan untuk kriteria ini telah selesai diproses atau belum ada reservasi baru.</p>
                    </div>
                @endforelse
            </div>

            <!-- Desktop View: Tabel Order Lengkap -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-900/90 text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="py-3.5 px-4 font-bold">No. Antrian</th>
                            <th class="py-3.5 px-4 font-bold">Pelanggan</th>
                            <th class="py-3.5 px-4 font-bold">Kategori</th>
                            <th class="py-3.5 px-4 font-bold">Layanan</th>
                            <th class="py-3.5 px-4 font-bold">Jadwal Tanggal & Jam</th>
                            <th class="py-3.5 px-4 font-bold">Total Tarif</th>
                            <th class="py-3.5 px-4 font-bold">Status</th>
                            <th class="py-3.5 px-4 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                <td class="py-4 px-4">
                                    <div class="font-mono font-black text-lg {{ $order->service_category === 'barbershop' ? 'text-amber-500 dark:text-amber-400' : 'text-pink-500 dark:text-pink-400' }}">
                                        {{ $order->queue_number }}
                                    </div>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 font-mono tracking-tight">{{ $order->booking_code }}</span>
                                </td>

                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="relative flex-shrink-0">
                                            @if($order->user?->avatar)
                                                <img src="{{ asset('storage/' . $order->user->avatar) }}" alt="{{ $order->customer_name }}" class="w-10 h-10 rounded-xl object-cover ring-1 ring-amber-500/50 shadow-sm">
                                            @else
                                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500/20 via-slate-100 dark:via-slate-800 to-amber-500/10 ring-1 ring-amber-500/30 flex items-center justify-center text-amber-500 font-black text-xs shadow-sm flex-shrink-0">
                                                    {{ strtoupper(substr($order->customer_name, 0, 2)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-base text-slate-900 dark:text-white">{{ $order->customer_name }}</div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400 font-mono">{{ $order->customer_phone ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-4">
                                    <span class="px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wider {{ $order->service_category === 'barbershop' ? 'bg-amber-500/20 text-amber-500 border border-amber-500/40' : 'bg-pink-500/20 text-pink-400 border border-pink-500/40' }}">
                                        {{ ucfirst($order->service_category) }}
                                    </span>
                                </td>

                                <td class="py-4 px-4 max-w-xs">
                                    <div class="font-semibold text-sm text-slate-900 dark:text-slate-100">
                                        {{ $order->items->map(fn($item) => $item->service->name)->join(', ') }}
                                    </div>
                                    @if ($order->notes)
                                        <div class="text-xs text-amber-500 dark:text-amber-300 font-medium italic mt-0.5 truncate">
                                            📝 {{ $order->notes }}
                                        </div>
                                    @endif
                                </td>

                                <td class="py-4 px-4 text-sm font-mono text-slate-700 dark:text-slate-300">
                                    <div class="font-bold text-slate-900 dark:text-white">
                                        @if ($order->booking_date->isToday())
                                            <span class="text-emerald-500 font-bold">Hari Ini</span>
                                        @elseif ($order->booking_date->isTomorrow())
                                            <span class="text-amber-400 font-bold">Besok ({{ $order->booking_date->format('d M') }})</span>
                                        @else
                                            <span class="text-slate-200 font-bold">
                                                {{ $order->booking_date->translatedFormat('d M Y') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-slate-400 font-semibold mt-0.5">{{ $order->booking_time }} WIB</div>
                                </td>

                                <td class="py-4 px-4 font-black text-base text-slate-900 dark:text-white font-mono">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>

                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="px-3.5 py-1 rounded-full text-xs uppercase tracking-wider font-bold inline-block whitespace-nowrap bg-amber-500/20 text-amber-500 border border-amber-500/40">
                                        {{ $order->status_label }}
                                    </span>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    <form method="POST" action="{{ route('kasir.queue.cancel', $order) }}"
                                          onsubmit="return confirm('Batalkan pesanan {{ $order->booking_code }} ({{ $order->customer_name }})?');">
                                        @csrf
                                        <button type="submit"
                                                class="px-3 py-1.5 bg-rose-500/10 hover:bg-rose-600 text-rose-500 hover:text-white border border-rose-500/30 text-xs font-bold rounded-xl transition">
                                            Batalkan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-500 dark:text-slate-400">
                                    <div class="max-w-xs mx-auto space-y-2">
                                        <div class="text-3xl">⏳</div>
                                        <p class="font-bold text-sm text-slate-700 dark:text-slate-300">Tidak ada orderan mendatang.</p>
                                        <p class="text-xs">Seluruh orderan untuk kriteria atau tanggal ini telah selesai diproses.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($orders->hasPages())
                <div class="px-5 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</x-kasir-layout>
