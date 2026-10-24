<x-kasir-layout title="Laporan Pelayanan Kasir - Grooming Lab">
    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-5 shadow-sm">
            <div>
                <h1 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    LAPORAN PELAYANAN KASIR
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Rekapitulasi pendapatan & transaksi pelayanan salon. Tersedia opsi unduh untuk periode harian dan mingguan.
                </p>
            </div>

            <!-- Report Type Tabs (Harian & Mingguan Saja) -->
            <div class="w-full sm:w-auto grid grid-cols-2 gap-1.5 bg-slate-100 dark:bg-slate-900 p-1 sm:p-1.5 rounded-xl border border-slate-200 dark:border-slate-750">
                <a href="{{ route('kasir.reports.index', ['type' => 'daily', 'date' => $selectedDate, 'category' => $category]) }}"
                   class="flex items-center justify-center px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-bold transition {{ $reportType === 'daily' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                    Laporan Harian
                </a>
                <a href="{{ route('kasir.reports.index', ['type' => 'weekly', 'date' => $selectedDate, 'category' => $category]) }}"
                   class="flex items-center justify-center px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-bold transition {{ $reportType === 'weekly' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                    Laporan Mingguan
                </a>
            </div>
        </div>

        <!-- KPI Metrics Summary for Selected Period (Minimalis 2 Kotak per Baris di HP) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4">
            <!-- Total Revenue -->
            <div class="bg-white dark:bg-slate-800 border-l-4 border-l-emerald-500 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl p-3 sm:p-5 shadow-sm">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 block truncate">Total Pendapatan</span>
                <div class="text-lg sm:text-2xl lg:text-3xl font-black text-slate-900 dark:text-white mt-1 font-mono">
                    Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}
                </div>
                <span class="text-[10px] sm:text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 block truncate">Selesai</span>
            </div>

            <!-- Completed Orders -->
            <div class="bg-white dark:bg-slate-800 border-l-4 border-l-blue-500 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl p-3 sm:p-5 shadow-sm">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 block truncate">Pesanan Selesai</span>
                <div class="text-lg sm:text-2xl lg:text-3xl font-black text-slate-900 dark:text-white mt-1 font-mono">
                    {{ $summary['completed_orders'] }}
                </div>
                <span class="text-[10px] sm:text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 block truncate">Dari {{ $summary['total_orders'] }} total</span>
            </div>

            <!-- Barbershop Revenue -->
            <div class="bg-white dark:bg-slate-800 border-l-4 border-l-amber-500 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl p-3 sm:p-5 shadow-sm">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400 block truncate">Barbershop</span>
                <div class="text-lg sm:text-2xl font-black text-amber-500 dark:text-amber-400 mt-1 font-mono">
                    Rp {{ number_format($summary['barbershop_revenue'], 0, ',', '.') }}
                </div>
                <span class="text-[10px] sm:text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 block truncate">{{ $summary['barbershop_orders'] }} selesai</span>
            </div>

            <!-- MUA Revenue -->
            <div class="bg-white dark:bg-slate-800 border-l-4 border-l-pink-500 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl p-3 sm:p-5 shadow-sm">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-pink-600 dark:text-pink-400 block truncate">MUA Studio</span>
                <div class="text-lg sm:text-2xl font-black text-pink-500 dark:text-pink-400 mt-1 font-mono">
                    Rp {{ number_format($summary['mua_revenue'], 0, ',', '.') }}
                </div>
                <span class="text-[10px] sm:text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 block truncate">{{ $summary['mua_orders'] }} selesai</span>
            </div>
        </div>

        <!-- Filter & Download Toolbar -->
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-4 sm:p-5 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <form method="GET" action="{{ route('kasir.reports.index') }}" class="flex flex-wrap items-center gap-3">
                <input type="hidden" name="type" value="{{ $reportType }}">

                <!-- Date Picker -->
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase">
                        {{ $reportType === 'weekly' ? 'Pilih Minggu (Tanggal Acuan):' : 'Pilih Tanggal:' }}
                    </label>
                    <input type="date" name="date" value="{{ $selectedDate }}" onchange="this.form.submit()"
                           class="px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                </div>

                <!-- Category Filter -->
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase">Kategori:</label>
                    <select name="category" onchange="this.form.submit()"
                            class="px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                        <option value="all">Semua Kategori</option>
                        <option value="barbershop" {{ $category === 'barbershop' ? 'selected' : '' }}>Barbershop</option>
                        <option value="mua" {{ $category === 'mua' ? 'selected' : '' }}>MUA Studio</option>
                    </select>
                </div>
            </form>

            <!-- Export Buttons (Excel & Cetak) -->
            <div class="flex items-center gap-2">
                <a href="{{ route('kasir.reports.export-excel', ['type' => $reportType, 'date' => $selectedDate, 'category' => $category]) }}"
                   class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center gap-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Unduh Excel {{ $reportType === 'weekly' ? 'Mingguan' : 'Harian' }}</span>
                </a>

                <a href="{{ route('kasir.reports.export-pdf', ['type' => $reportType, 'date' => $selectedDate, 'category' => $category]) }}" target="_blank"
                   class="px-4 py-2.5 bg-slate-700 hover:bg-slate-600 text-white font-bold text-xs sm:text-sm rounded-xl shadow transition flex items-center gap-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak Print</span>
                </a>
            </div>
        </div>

        <!-- Period info notification -->
        <div class="px-4 py-2.5 bg-slate-200 dark:bg-slate-800/60 rounded-xl text-xs text-slate-700 dark:text-slate-300 font-medium flex items-center justify-between">
            <span>
                Menampilkan data periode: <strong>{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</strong>
            </span>
            <span class="text-[11px] text-slate-500">
                (Unduh khusus laporan harian & mingguan)
            </span>
        </div>

        <!-- Table Report -->
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl overflow-hidden shadow-lg">
            <!-- Mobile View: Kartu Transaksi Ringkas di HP -->
            <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-700/70">
                @forelse ($bookings as $b)
                    <div class="p-3.5 space-y-2">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-black text-xl {{ $b->service_category === 'barbershop' ? 'text-amber-500 dark:text-amber-400' : 'text-pink-500 dark:text-pink-400' }}">
                                    {{ $b->queue_number }}
                                </span>
                                <span class="text-[10px] text-slate-500 font-mono bg-slate-100 dark:bg-slate-750 px-1.5 py-0.5 rounded">
                                    {{ $b->booking_code }}
                                </span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase whitespace-nowrap inline-block {{ $b->status === 'completed' ? 'bg-emerald-500 text-slate-950' : ($b->status === 'cancelled' ? 'bg-rose-500 text-white' : ($b->status === 'in_progress' ? 'bg-sky-500 text-slate-950 font-black' : ($b->status === 'called' ? 'bg-indigo-600 text-white' : 'bg-amber-400 text-slate-950'))) }}">
                                {{ $b->status_label }}
                            </span>
                        </div>

                        <div class="flex items-start justify-between gap-2 text-xs">
                            <div>
                                <div class="font-bold text-sm text-slate-900 dark:text-white">{{ $b->customer_name }}</div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">{{ $b->booking_date->format('d/m/Y') }} &bull; {{ $b->booking_time }} WIB</div>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded {{ $b->service_category === 'barbershop' ? 'bg-amber-500/15 text-amber-600 dark:text-amber-400' : 'bg-pink-500/15 text-pink-600 dark:text-pink-400' }}">
                                    {{ ucfirst($b->service_category) }}
                                </span>
                                <div class="font-black text-xs text-slate-900 dark:text-white font-mono mt-1">
                                    Rp {{ number_format($b->total_price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <div class="text-xs text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-900/60 p-2 rounded-lg space-y-1">
                            <div>
                                <span class="text-slate-500 dark:text-slate-400">Layanan:</span>
                                <span class="font-semibold text-slate-900 dark:text-slate-200">{{ $b->items->map(fn($i) => $i->service->name)->join(', ') }}</span>
                            </div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 pt-0.5 border-t border-slate-200/60 dark:border-slate-800">
                                Kasir: <span class="font-bold text-slate-800 dark:text-slate-200">{{ $b->cashier->name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center text-slate-500 dark:text-slate-400 px-4">
                        <div class="text-3xl">📊</div>
                        <p class="font-bold text-sm text-slate-700 dark:text-slate-300 mt-2">Tidak ada transaksi pada periode ini.</p>
                    </div>
                @endforelse
            </div>

            <!-- Desktop View: Tabel -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-200 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-black text-xs uppercase tracking-wider border-b border-slate-300 dark:border-slate-800">
                        <tr>
                            <th class="py-4 px-4 font-black">No. Antrian</th>
                            <th class="py-4 px-4 font-black">Kode Booking</th>
                            <th class="py-4 px-4 font-black">Tanggal & Jam</th>
                            <th class="py-4 px-4 font-black">Pelanggan</th>
                            <th class="py-4 px-4 font-black">Kategori</th>
                            <th class="py-4 px-4 font-black">Layanan</th>
                            <th class="py-4 px-4 font-black">Petugas Kasir</th>
                            <th class="py-4 px-4 font-black">Total Tarif</th>
                            <th class="py-4 px-4 font-black">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/70">
                        @forelse ($bookings as $b)
                            <tr class="hover:bg-slate-100/70 dark:hover:bg-slate-700/60 transition">
                                <td class="py-4 px-4 font-mono font-black text-base {{ $b->service_category === 'barbershop' ? 'text-amber-500 dark:text-amber-400' : 'text-pink-500 dark:text-pink-400' }}">
                                    {{ $b->queue_number }}
                                </td>
                                <td class="py-4 px-4 font-mono text-xs text-slate-500 dark:text-slate-300">
                                    {{ $b->booking_code }}
                                </td>
                                <td class="py-4 px-4 font-mono text-xs">
                                    {{ $b->booking_date->format('d/m/Y') }} {{ $b->booking_time }}
                                </td>
                                <td class="py-4 px-4 font-bold text-slate-900 dark:text-white">
                                    {{ $b->customer_name }}
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-2.5 py-1 rounded-md text-xs font-bold uppercase {{ $b->service_category === 'barbershop' ? 'bg-amber-500/20 text-amber-700 dark:text-amber-300 border border-amber-500/40' : 'bg-pink-500/20 text-pink-700 dark:text-pink-300 border border-pink-500/40' }}">
                                        {{ ucfirst($b->service_category) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 font-semibold text-slate-900 dark:text-white max-w-xs">
                                    {{ $b->items->map(fn($i) => $i->service->name)->join(', ') }}
                                </td>
                                <td class="py-4 px-4 text-xs font-semibold text-slate-800 dark:text-slate-200">
                                    {{ $b->cashier->name ?? '-' }}
                                </td>
                                <td class="py-4 px-4 font-black font-mono text-slate-900 dark:text-white">
                                    Rp {{ number_format($b->total_price, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase whitespace-nowrap inline-block {{ $b->status === 'completed' ? 'bg-emerald-500 text-slate-950' : ($b->status === 'cancelled' ? 'bg-rose-500 text-white' : ($b->status === 'in_progress' ? 'bg-sky-500 text-slate-950 font-black' : ($b->status === 'called' ? 'bg-indigo-600 text-white' : 'bg-amber-400 text-slate-950'))) }}">
                                        {{ $b->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-12 text-center text-slate-500 dark:text-slate-400">
                                    <div class="max-w-xs mx-auto space-y-2">
                                        <div class="text-3xl">📊</div>
                                        <p class="font-bold text-sm text-slate-700 dark:text-slate-300">Tidak ada transaksi pada periode ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($bookings->hasPages())
                <div class="px-5 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</x-kasir-layout>

