<x-admin-layout title="Admin Dashboard - Grooming Lab & MUA">
    <div class="space-y-6">
        <!-- Header Title -->
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Dashboard Overview</h1>
            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Ringkasan performa bisnis dan antrian Barbershop & MUA</p>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Pelanggan -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm dark:shadow-lg">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Pelanggan</span>
                    <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 text-2xl font-black text-slate-900 dark:text-white">{{ number_format($stats['total_customers']) }}</div>
                <span class="text-[11px] text-slate-500 block mt-1">Pelanggan terdaftar</span>
            </div>

            <!-- Total Layanan Aktif -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm dark:shadow-lg">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Layanan Aktif</span>
                    <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879a3 3 0 11-4.242-4.242L7.757 7.757m0 0l-2.879-2.879a3 3 0 10-4.242 4.242L3.515 12" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 text-2xl font-black text-amber-600 dark:text-amber-400">{{ $stats['total_services'] }}</div>
                <span class="text-[11px] text-slate-500 block mt-1">Barbershop & MUA</span>
            </div>

            <!-- Booking Hari Ini -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm dark:shadow-lg">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Order Hari Ini</span>
                    <div class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 text-2xl font-black text-slate-900 dark:text-white">
                    {{ $stats['today_bookings'] }}
                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">({{ $stats['today_completed'] }} selesai)</span>
                </div>
                <span class="text-[11px] text-slate-500 block mt-1">Transaksi hari berjalan</span>
            </div>

            <!-- Pendapatan Hari Ini -->
            <div class="bg-white dark:bg-slate-900 border border-emerald-300 dark:border-emerald-500/30 rounded-2xl p-5 shadow-sm dark:shadow-lg">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Pendapatan Hari Ini</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 text-xl font-black text-emerald-600 dark:text-emerald-400">Rp {{ number_format($stats['today_revenue'], 0, ',', '.') }}</div>
                <span class="text-[11px] text-slate-500 dark:text-slate-400 block mt-1">Bulan ini: Rp {{ number_format($stats['month_revenue'], 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Recent Bookings Table -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm dark:shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Pesanan Antrian Terbaru</h2>
                    <p class="text-xs text-slate-600 dark:text-slate-400">10 transaksi booking terakhir di sistem</p>
                </div>
                <a href="{{ route('admin.reports.index') }}" class="text-xs text-emerald-600 dark:text-emerald-400 hover:text-emerald-500 font-semibold">
                    Lihat Semua Laporan &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-950/60 text-xs uppercase tracking-wider text-slate-600 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="py-3 px-4">No. Antrian</th>
                            <th class="py-3 px-4">Pelanggan</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4">Layanan</th>
                            <th class="py-3 px-4">Tanggal & Jam</th>
                            <th class="py-3 px-4">Total</th>
                            <th class="py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        @forelse ($recentBookings as $b)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-3 px-4 font-mono font-bold text-amber-600 dark:text-amber-400">{{ $b->queue_number }}</td>
                                <td class="py-3 px-4 font-medium text-slate-900 dark:text-white">{{ $b->user->name }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $b->service_category === 'barbershop' ? 'bg-amber-500/10 text-amber-700 dark:text-amber-400' : 'bg-pink-500/10 text-pink-700 dark:text-pink-400' }}">
                                        {{ ucfirst($b->service_category) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-xs text-slate-600 dark:text-slate-300">{{ $b->items->map(fn($i) => $i->service->name)->join(', ') }}</td>
                                <td class="py-3 px-4 text-xs text-slate-600 dark:text-slate-400">{{ $b->booking_date->format('d/m/Y') }} &bull; {{ $b->booking_time }}</td>
                                <td class="py-3 px-4 font-bold text-slate-900 dark:text-white">Rp {{ number_format($b->total_price, 0, ',', '.') }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $b->status_color }}">
                                        {{ $b->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 text-center text-slate-500 text-sm">Belum ada data pesanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
