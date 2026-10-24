<x-kasir-layout title="Manajemen Order & Reservasi - Grooming Lab">
    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-5 shadow-sm">
            <div>
                <h1 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                    MANAJEMEN ORDER & RESERVASI PELANGGAN
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Pantau reservasi yang akan datang dan telusuri riwayat order bulanan secara lengkap.
                </p>
            </div>

            <!-- View Switcher Tabs -->
            <div class="w-full sm:w-auto grid grid-cols-3 gap-1 bg-slate-100 dark:bg-slate-900 p-1 sm:p-1.5 rounded-xl border border-slate-200 dark:border-slate-700">
                <a href="{{ route('kasir.orders.index', ['view' => 'upcoming']) }}"
                   class="px-2.5 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-bold transition flex items-center justify-center gap-1.5 {{ $viewType === 'upcoming' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                    <span>Mendatang</span>
                    @if ($upcomingCount > 0)
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $viewType === 'upcoming' ? 'bg-blue-800 text-white' : 'bg-blue-500/20 text-blue-600 dark:text-blue-300 font-bold' }}">
                            {{ $upcomingCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('kasir.orders.index', ['view' => 'month']) }}"
                   class="px-2.5 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-bold transition flex items-center justify-center gap-1.5 {{ $viewType === 'month' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                    <span>Bulanan</span>
                </a>

                <a href="{{ route('kasir.orders.index', ['view' => 'all']) }}"
                   class="px-2.5 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-bold transition flex items-center justify-center {{ $viewType === 'all' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                    Semua
                </a>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-4 sm:p-5 shadow-sm space-y-4">
            <form method="GET" action="{{ route('kasir.orders.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <input type="hidden" name="view" value="{{ $viewType }}">

                <!-- Month Picker (when viewing monthly) -->
                @if ($viewType === 'month')
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase mb-1">Pilih Bulan:</label>
                        <input type="month" name="month" value="{{ $selectedMonth }}" onchange="this.form.submit()"
                               class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    </div>
                @endif

                <!-- Category -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase mb-1">Kategori:</label>
                    <select name="category" onchange="this.form.submit()"
                            class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        <option value="all">Semua Kategori</option>
                        <option value="barbershop" {{ $category === 'barbershop' ? 'selected' : '' }}>Barbershop</option>
                        <option value="mua" {{ $category === 'mua' ? 'selected' : '' }}>MUA Studio</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase mb-1">Status:</label>
                    <select name="status" onchange="this.form.submit()"
                            class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        <option value="all">Semua Status</option>
                        <option value="waiting" {{ $status === 'waiting' ? 'selected' : '' }}>Menunggu</option>
                        <option value="called" {{ $status === 'called' ? 'selected' : '' }}>Dipanggil</option>
                        <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>Sedang Dilayani</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <!-- Search Input -->
                <div class="{{ $viewType === 'month' ? 'lg:col-span-2' : 'lg:col-span-3' }}">
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase mb-1">Cari Pelanggan / Kode:</label>
                    <div class="flex gap-2">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Nama, HP, atau kode booking..."
                               class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl shadow transition">
                            Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Order -->
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl overflow-hidden shadow-lg">
            <!-- Mobile View: Kartu Order Ringkas di HP -->
            <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-700/70">
                @forelse ($orders as $order)
                    @php
                        $solidStatusClass = match($order->status) {
                            'waiting' => 'bg-amber-400 text-slate-950 font-black shadow-sm',
                            'called' => 'bg-indigo-600 text-white font-black shadow-sm',
                            'in_progress' => 'bg-sky-500 text-slate-950 font-black shadow-sm',
                            'skipped' => 'bg-purple-600 text-white font-bold shadow-sm',
                            'completed' => 'bg-emerald-500 text-slate-950 font-black shadow-sm',
                            'cancelled' => 'bg-rose-500 text-white font-black shadow-sm',
                            default => 'bg-slate-600 text-white font-bold',
                        };
                    @endphp
                    <div class="p-3.5 space-y-2">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-black text-xl {{ $order->service_category === 'barbershop' ? 'text-amber-500 dark:text-amber-400' : 'text-pink-500 dark:text-pink-400' }}">
                                    {{ $order->queue_number }}
                                </span>
                                <span class="text-[10px] text-slate-500 font-mono bg-slate-100 dark:bg-slate-750 px-1.5 py-0.5 rounded">
                                    {{ $order->booking_code }}
                                </span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $order->service_category === 'barbershop' ? 'bg-amber-500/15 text-amber-600 dark:text-amber-400' : 'bg-pink-500/15 text-pink-600 dark:text-pink-400' }}">
                                    {{ ucfirst($order->service_category) }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] uppercase tracking-wider whitespace-nowrap inline-block {{ $solidStatusClass }}">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-start justify-between gap-2 text-xs">
                            <div>
                                <div class="font-bold text-sm text-slate-900 dark:text-white">{{ $order->customer_name }}</div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">{{ $order->customer_phone ?? '-' }}</div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-slate-900 dark:text-white font-mono">
                                    @if ($order->booking_date->isToday())
                                        <span class="text-emerald-600 dark:text-emerald-400 font-bold">Hari Ini,</span>
                                    @else
                                        <span class="text-indigo-600 dark:text-indigo-300 font-bold">{{ $order->booking_date->translatedFormat('d M Y') }},</span>
                                    @endif
                                    {{ $order->booking_time }} WIB
                                </div>
                                <div class="font-black text-xs text-slate-900 dark:text-white font-mono mt-0.5">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <div class="text-xs text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-900/60 p-2 rounded-lg">
                            <span class="text-slate-500 dark:text-slate-400">Layanan:</span>
                            <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $order->items->map(fn($item) => $item->service->name)->join(', ') }}</span>
                            @if ($order->notes)
                                <div class="text-[11px] text-amber-600 dark:text-amber-400 italic mt-0.5">Catatan: {{ $order->notes }}</div>
                            @endif
                        </div>

                        @if (in_array($order->status, ['waiting', 'called', 'skipped']))
                            <div class="pt-1 flex justify-end">
                                <form method="POST" action="{{ route('kasir.queue.cancel', $order) }}"
                                      onsubmit="return confirm('Batalkan pesanan {{ $order->booking_code }} ({{ $order->customer_name }})?');">
                                    @csrf
                                    <button type="submit"
                                            class="py-1.5 px-3 bg-rose-500/10 hover:bg-rose-600 text-rose-600 dark:text-rose-400 hover:text-white border border-rose-500/30 text-xs font-bold rounded-lg transition active:scale-95">
                                        Batalkan Pesanan
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="py-10 text-center text-slate-500 dark:text-slate-400 px-4">
                        <div class="text-3xl">📅</div>
                        <p class="font-bold text-sm text-slate-700 dark:text-slate-300 mt-2">Tidak ada order yang ditemukan.</p>
                        <p class="text-xs text-slate-500 mt-0.5">Ubah kriteria filter untuk melihat data lain.</p>
                    </div>
                @endforelse
            </div>

            <!-- Desktop View: Tabel Lengkap -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-200 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-black text-xs uppercase tracking-wider border-b border-slate-300 dark:border-slate-800">
                        <tr>
                            <th class="py-4 px-4 font-black">No. Antrian</th>
                            <th class="py-4 px-4 font-black">Pelanggan</th>
                            <th class="py-4 px-4 font-black">Kategori</th>
                            <th class="py-4 px-4 font-black">Layanan</th>
                            <th class="py-4 px-4 font-black">Tanggal & Jam</th>
                            <th class="py-4 px-4 font-black">Total Tarif</th>
                            <th class="py-4 px-4 font-black">Status</th>
                            <th class="py-4 px-4 font-black text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/70">
                        @forelse ($orders as $order)
                            @php
                                $solidStatusClass = match($order->status) {
                                    'waiting' => 'bg-amber-400 text-slate-950 font-black shadow-sm',
                                    'called' => 'bg-indigo-600 text-white font-black shadow-sm',
                                    'in_progress' => 'bg-sky-500 text-slate-950 font-black shadow-sm',
                                    'skipped' => 'bg-purple-600 text-white font-bold shadow-sm',
                                    'completed' => 'bg-emerald-500 text-slate-950 font-black shadow-sm',
                                    'cancelled' => 'bg-rose-500 text-white font-black shadow-sm',
                                    default => 'bg-slate-600 text-white font-bold',
                                };
                            @endphp
                            <tr class="hover:bg-slate-100/70 dark:hover:bg-slate-700/60 transition">
                                <td class="py-4 px-4">
                                    <div class="font-mono font-black text-lg {{ $order->service_category === 'barbershop' ? 'text-amber-500 dark:text-amber-400' : 'text-pink-500 dark:text-pink-400' }}">
                                        {{ $order->queue_number }}
                                    </div>
                                    <span class="text-xs text-slate-500 dark:text-slate-300 font-mono tracking-tight">{{ $order->booking_code }}</span>
                                </td>

                                <td class="py-4 px-4">
                                    <div class="font-bold text-base text-slate-900 dark:text-white">{{ $order->customer_name }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-300 font-mono">{{ $order->customer_phone ?? '-' }}</div>
                                </td>

                                <td class="py-4 px-4">
                                    <span class="px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wider {{ $order->service_category === 'barbershop' ? 'bg-amber-500/20 text-amber-700 dark:text-amber-300 border border-amber-500/40' : 'bg-pink-500/20 text-pink-700 dark:text-pink-300 border border-pink-500/40' }}">
                                        {{ ucfirst($order->service_category) }}
                                    </span>
                                </td>

                                <td class="py-4 px-4 max-w-xs">
                                    <div class="font-semibold text-sm text-slate-900 dark:text-slate-100">
                                        {{ $order->items->map(fn($item) => $item->service->name)->join(', ') }}
                                    </div>
                                    @if ($order->notes)
                                        <div class="text-xs text-amber-700 dark:text-amber-300 font-medium italic mt-0.5 truncate">
                                            📝 {{ $order->notes }}
                                        </div>
                                    @endif
                                </td>

                                <td class="py-4 px-4 text-sm font-mono text-slate-700 dark:text-slate-300">
                                    <div class="font-bold text-slate-900 dark:text-white">
                                        @if ($order->booking_date->isToday())
                                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">Hari Ini</span>
                                        @else
                                            <span class="text-indigo-600 dark:text-indigo-300 font-bold">
                                                {{ $order->booking_date->translatedFormat('d M Y') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 font-semibold mt-0.5">{{ $order->booking_time }} WIB</div>
                                </td>

                                <td class="py-4 px-4 font-black text-base text-slate-900 dark:text-white font-mono">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>

                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="px-3.5 py-1 rounded-full text-xs uppercase tracking-wider inline-block whitespace-nowrap {{ $solidStatusClass }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    @if (in_array($order->status, ['waiting', 'called', 'skipped']))
                                        <form method="POST" action="{{ route('kasir.queue.cancel', $order) }}"
                                              onsubmit="return confirm('Batalkan pesanan {{ $order->booking_code }} ({{ $order->customer_name }})?');">
                                            @csrf
                                            <button type="submit"
                                                    class="px-3 py-1.5 bg-rose-500/10 hover:bg-rose-600 text-rose-600 dark:text-rose-400 hover:text-white border border-rose-500/30 text-xs font-bold rounded-xl transition">
                                                Batalkan
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400 font-medium">&mdash;</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-500 dark:text-slate-400">
                                    <div class="max-w-xs mx-auto space-y-2">
                                        <div class="text-3xl">📅</div>
                                        <p class="font-bold text-sm text-slate-700 dark:text-slate-300">Tidak ada order yang ditemukan.</p>
                                        <p class="text-xs">Ubah kriteria filter tanggal, bulan, atau status di atas untuk melihat data lain.</p>
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

