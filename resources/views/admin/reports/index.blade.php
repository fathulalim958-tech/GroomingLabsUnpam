<x-admin-layout title="Laporan Transaksi - Grooming Lab & MUA">
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Laporan Transaksi & Pendapatan</h1>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Rekapitulasi penjualan layanan Barbershop dan MUA Studio</p>
            </div>

            <!-- Export Buttons -->
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.reports.export-excel', request()->all()) }}"
                   class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-lg transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Ekspor Excel (.xlsx)</span>
                </a>

                <a href="{{ route('admin.reports.export-excel', array_merge(request()->all(), ['format' => 'csv'])) }}"
                   class="px-3 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-bold text-xs rounded-xl border border-slate-200 dark:border-slate-700 transition flex items-center gap-1 shadow-sm" title="Unduh format CSV">
                    <span>CSV</span>
                </a>

                <a href="{{ route('admin.reports.export-pdf', request()->all()) }}" target="_blank"
                   class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-bold text-xs rounded-xl border border-slate-200 dark:border-slate-700 transition flex items-center gap-1.5 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak / PDF</span>
                </a>
            </div>
        </div>

        <!-- Filter Toolbar -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-400 mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                           class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-400 mb-1">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                           class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-400 mb-1">Kategori Layanan</label>
                    <select name="category"
                            class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs focus:ring-emerald-500">
                        <option value="all" {{ $category === 'all' ? 'selected' : '' }}>Semua Layanan</option>
                        <option value="barbershop" {{ $category === 'barbershop' ? 'selected' : '' }}>Barbershop</option>
                        <option value="mua" {{ $category === 'mua' ? 'selected' : '' }}>MUA Studio</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="w-full py-2 px-4 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow transition">
                        Terapkan Filter
                    </button>
                    <a href="{{ route('admin.reports.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs rounded-xl border border-slate-300 dark:border-slate-700 transition" title="Reset Filter">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-slate-900 border border-emerald-300 dark:border-emerald-500/30 rounded-2xl p-5 shadow-sm dark:shadow-lg">
                <span class="text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Total Pendapatan Selesai</span>
                <div class="mt-2 text-2xl font-black text-slate-900 dark:text-white">
                    Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}
                </div>
                <span class="text-[11px] text-slate-500 dark:text-slate-400 block mt-1">{{ $summary['total_completed_orders'] }} Selesai / {{ $summary['total_all_orders'] }} Semua Order</span>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-amber-300 dark:border-amber-500/30 rounded-2xl p-5 shadow-sm dark:shadow-lg">
                <span class="text-xs font-semibold uppercase tracking-wider text-amber-600 dark:text-amber-400">Pendapatan Barbershop</span>
                <div class="mt-2 text-2xl font-black text-amber-600 dark:text-amber-300">
                    Rp {{ number_format($summary['barbershop_revenue'], 0, ',', '.') }}
                </div>
                <span class="text-[11px] text-slate-500 dark:text-slate-400 block mt-1">{{ $summary['barbershop_orders'] }} Transaksi Selesai</span>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-pink-300 dark:border-pink-500/30 rounded-2xl p-5 shadow-sm dark:shadow-lg">
                <span class="text-xs font-semibold uppercase tracking-wider text-pink-600 dark:text-pink-400">Pendapatan MUA Studio</span>
                <div class="mt-2 text-2xl font-black text-pink-600 dark:text-pink-300">
                    Rp {{ number_format($summary['mua_revenue'], 0, ',', '.') }}
                </div>
                <span class="text-[11px] text-slate-500 dark:text-slate-400 block mt-1">{{ $summary['mua_orders'] }} Transaksi Selesai</span>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm dark:shadow-lg">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Rentang Periode</span>
                <div class="mt-2 text-sm font-bold text-slate-800 dark:text-slate-200">
                    {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
                </div>
                <span class="text-xs text-slate-500 dark:text-slate-400 block">s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</span>
            </div>
        </div>

        <!-- Report Table -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm dark:shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-950/60 text-xs uppercase tracking-wider text-slate-600 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="py-3 px-4">No.</th>
                            <th class="py-3 px-4">Tanggal & Waktu</th>
                            <th class="py-3 px-4">Jenis</th>
                            <th class="py-3 px-4">Pelanggan</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4">Layanan</th>
                            <th class="py-3 px-4">Staff</th>
                            <th class="py-3 px-4">Kasir</th>
                            <th class="py-3 px-4 text-right">Harga</th>
                            <th class="py-3 px-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        @forelse ($bookings as $index => $b)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-3.5 px-4 text-xs font-mono text-slate-500">{{ $bookings->firstItem() + $index }}</td>
                                <td class="py-3.5 px-4 text-xs text-slate-600 dark:text-slate-400">
                                    {{ $b->booking_date->format('d/m/Y') }} &bull; {{ substr($b->booking_time, 0, 5) }}
                                </td>
                                <td class="py-3.5 px-4 text-xs">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ ($b->cashier_id || ($b->notes && str_contains(strtolower($b->notes), 'walk-in'))) ? 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300' }}">
                                        {{ ($b->cashier_id || ($b->notes && str_contains(strtolower($b->notes), 'walk-in'))) ? 'Offline' : 'Online' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-medium text-slate-900 dark:text-white">{{ $b->customer_name }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $b->service_category === 'barbershop' ? 'bg-amber-500/10 text-amber-700 dark:text-amber-400' : 'bg-pink-500/10 text-pink-700 dark:text-pink-400' }}">
                                        {{ $b->service_category === 'mua' ? 'MUA' : 'Barbershop' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-xs text-slate-600 dark:text-slate-300">{{ $b->items->map(fn($i) => $i->service->name)->join(', ') ?: '-' }}</td>
                                <td class="py-3.5 px-4 text-xs text-slate-600 dark:text-slate-300">{{ $b->staff->name ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $b->cashier->name ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-right font-bold text-emerald-600 dark:text-emerald-400">
                                    Rp {{ number_format($b->total_price, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold whitespace-nowrap inline-block {{ \App\Models\Booking::STATUS_COLORS[$b->status] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ $b->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-8 text-center text-slate-500 text-sm">
                                    Tidak ada data transaksi pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
