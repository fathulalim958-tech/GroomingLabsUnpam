<x-kasir-layout title="Riwayat Pelanggan Dilayani - Grooming Lab">
    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-4 sm:p-5 shadow-sm">
            <div>
                <h1 class="text-sm sm:text-base lg:text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight flex items-center gap-2">
                    <span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                    RIWAYAT PELANGGAN YANG SUDAH DILAYANI
                </h1>
                <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Arsip data pelanggan yang telah selesai dilayani per hari. Tersedia fitur ekspor berkas Excel harian.
                </p>
            </div>

            <!-- Tombol Ekspor Excel Harian -->
            <div class="flex items-center gap-2">
                <a href="{{ route('kasir.staffs.export-excel', ['date' => $selectedDate, 'category' => $category]) }}"
                   class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 sm:py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs sm:text-sm rounded-xl shadow-lg shadow-emerald-600/20 transition transform active:scale-95 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Unduh Excel Harian</span>
                </a>
            </div>
        </div>

        <!-- Section KPI Metrik Harian -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4">
            <!-- 1. Total Dilayani -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block truncate">Total Dilayani</span>
                    <div class="text-lg sm:text-2xl lg:text-3xl font-black text-slate-900 dark:text-white mt-0.5 sm:mt-1 font-mono">{{ $stats['total_served'] }}</div>
                    <span class="text-[10px] text-slate-500 block">Pelanggan Selesai</span>
                </div>
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-emerald-500/10 text-emerald-500 border border-emerald-500/30 flex items-center justify-center font-bold text-xs sm:text-sm flex-shrink-0">
                    ✓
                </div>
            </div>

            <!-- 2. Barbershop -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-amber-500 block truncate">Barbershop</span>
                    <div class="text-lg sm:text-2xl lg:text-3xl font-black text-amber-500 mt-0.5 sm:mt-1 font-mono">{{ $stats['barbershop_served'] }}</div>
                    <span class="text-[10px] text-slate-500 block">Layanan Pria</span>
                </div>
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-amber-500/10 text-amber-500 border border-amber-500/30 flex items-center justify-center font-bold text-xs sm:text-sm flex-shrink-0">
                    💈
                </div>
            </div>

            <!-- 3. MUA Studio -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-pink-400 block truncate">MUA Studio</span>
                    <div class="text-lg sm:text-2xl lg:text-3xl font-black text-pink-400 mt-0.5 sm:mt-1 font-mono">{{ $stats['mua_served'] }}</div>
                    <span class="text-[10px] text-slate-500 block">Tata Rias</span>
                </div>
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-pink-500/10 text-pink-400 border border-pink-500/30 flex items-center justify-center font-bold text-xs sm:text-sm flex-shrink-0">
                    💄
                </div>
            </div>

            <!-- 4. Total Omzet Tanggal Ini -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-sm flex items-center justify-between">
                <div class="min-w-0 pr-1">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-emerald-500 block truncate">Total Tarif Harian</span>
                    <div class="text-sm sm:text-lg lg:text-2xl font-black text-emerald-500 mt-0.5 sm:mt-1 font-mono truncate">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
                    <span class="text-[10px] text-slate-500 block truncate">Selesai Dibayar</span>
                </div>
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-emerald-500/10 text-emerald-500 border border-emerald-500/30 flex items-center justify-center font-bold text-xs sm:text-sm flex-shrink-0">
                    💰
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar (Per Hari / Filter Tanggal) -->
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-4 sm:p-5 shadow-sm">
            <form method="GET" action="{{ route('kasir.staffs.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- Date Picker Filter (Harian) -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase mb-1 flex items-center justify-between">
                        <span>Pilih Tanggal:</span>
                        <div class="flex items-center gap-1.5 text-[10px]">
                            <a href="{{ route('kasir.staffs.index', ['date' => \Illuminate\Support\Carbon::today()->format('Y-m-d'), 'category' => $category]) }}"
                               class="{{ $selectedDate === \Illuminate\Support\Carbon::today()->format('Y-m-d') ? 'text-amber-500 font-black' : 'text-slate-400 hover:text-white' }}">
                                Hari Ini
                            </a>
                            <span>&bull;</span>
                            <a href="{{ route('kasir.staffs.index', ['date' => \Illuminate\Support\Carbon::yesterday()->format('Y-m-d'), 'category' => $category]) }}"
                               class="{{ $selectedDate === \Illuminate\Support\Carbon::yesterday()->format('Y-m-d') ? 'text-amber-500 font-black' : 'text-slate-400 hover:text-white' }}">
                                Kemarin
                            </a>
                        </div>
                    </label>
                    <input type="date" name="date" value="{{ $selectedDate }}" onchange="this.form.submit()"
                           class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 font-mono">
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase mb-1">Kategori Layanan:</label>
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
                        <input type="text" name="search" value="{{ $search }}" placeholder="Nama pelanggan, no. HP, kode booking..."
                               class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500">
                        <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs rounded-xl shadow transition whitespace-nowrap">
                            Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Riwayat Pelanggan Dilayani -->
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl overflow-hidden shadow-lg">
            <!-- Mobile View: Kartu Riwayat di HP -->
            <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-700/70">
                @forelse ($bookings as $b)
                    <div class="p-4 space-y-2.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-black text-base {{ $b->service_category === 'barbershop' ? 'text-amber-500 dark:text-amber-400' : 'text-pink-500 dark:text-pink-400' }}">
                                    {{ $b->queue_number }}
                                </span>
                                <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">#{{ $b->booking_code }}</span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] uppercase tracking-wider font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/40">
                                Selesai
                            </span>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="relative flex-shrink-0">
                                @if($b->user?->avatar)
                                    <img src="{{ asset('storage/' . $b->user->avatar) }}" alt="{{ $b->customer_name }}" class="w-10 h-10 rounded-xl object-cover ring-1 ring-amber-500/50 shadow-sm">
                                @else
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500/20 via-slate-100 dark:via-slate-800 to-amber-500/10 ring-1 ring-amber-500/30 flex items-center justify-center text-amber-500 font-black text-xs shadow-sm flex-shrink-0">
                                        {{ strtoupper(substr($b->customer_name, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="font-bold text-sm text-slate-900 dark:text-white">{{ $b->customer_name }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 font-mono">{{ $b->customer_phone ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="text-xs text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-900/60 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700/60">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-bold uppercase {{ $b->service_category === 'barbershop' ? 'text-amber-500' : 'text-pink-400' }}">
                                    {{ ucfirst($b->service_category) }}
                                </span>
                                <span class="font-mono font-bold text-slate-900 dark:text-white">
                                    Rp {{ number_format($b->total_price, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="text-slate-600 dark:text-slate-300 font-medium">
                                {{ $b->items->map(fn($item) => $item->service->name)->join(', ') }}
                            </div>
                            <div class="mt-1.5 flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 font-mono border-t border-slate-200 dark:border-slate-700 pt-1">
                                <span>Kasir: {{ $b->cashier->name ?? $b->staff->name ?? 'Kasir Utama' }}</span>
                                <span>⏰ {{ substr($b->booking_time, 0, 5) }} WIB</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-500 dark:text-slate-400 p-4">
                        <div class="text-3xl mb-2">📋</div>
                        <p class="font-bold text-sm text-slate-700 dark:text-slate-300">Belum ada pelanggan yang dilayani pada tanggal ini.</p>
                        <p class="text-xs mt-1">Gunakan filter tanggal di atas untuk melihat riwayat hari lain.</p>
                    </div>
                @endforelse
            </div>

            <!-- Desktop View: Tabel Riwayat Lengkap -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-900/90 text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="py-3.5 px-4 font-bold">No. Antrian & Kode</th>
                            <th class="py-3.5 px-4 font-bold">Data Pelanggan</th>
                            <th class="py-3.5 px-4 font-bold">Kategori</th>
                            <th class="py-3.5 px-4 font-bold">Layanan Dikerjakan</th>
                            <th class="py-3.5 px-4 font-bold">Waktu Layanan</th>
                            <th class="py-3.5 px-4 font-bold">Kasir / Pelayan</th>
                            <th class="py-3.5 px-4 font-bold">Total Tarif</th>
                            <th class="py-3.5 px-4 font-bold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                        @forelse ($bookings as $b)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                <td class="py-4 px-4">
                                    <div class="font-mono font-black text-lg {{ $b->service_category === 'barbershop' ? 'text-amber-500 dark:text-amber-400' : 'text-pink-500 dark:text-pink-400' }}">
                                        {{ $b->queue_number }}
                                    </div>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 font-mono tracking-tight">{{ $b->booking_code }}</span>
                                </td>

                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="relative flex-shrink-0">
                                            @if($b->user?->avatar)
                                                <img src="{{ asset('storage/' . $b->user->avatar) }}" alt="{{ $b->customer_name }}" class="w-10 h-10 rounded-xl object-cover ring-1 ring-amber-500/50 shadow-sm">
                                            @else
                                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500/20 via-slate-100 dark:via-slate-800 to-amber-500/10 ring-1 ring-amber-500/30 flex items-center justify-center text-amber-500 font-black text-xs shadow-sm flex-shrink-0">
                                                    {{ strtoupper(substr($b->customer_name, 0, 2)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-base text-slate-900 dark:text-white">{{ $b->customer_name }}</div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400 font-mono">{{ $b->customer_phone ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-4">
                                    <span class="px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wider {{ $b->service_category === 'barbershop' ? 'bg-amber-500/20 text-amber-500 border border-amber-500/40' : 'bg-pink-500/20 text-pink-400 border border-pink-500/40' }}">
                                        {{ ucfirst($b->service_category) }}
                                    </span>
                                </td>

                                <td class="py-4 px-4 max-w-xs">
                                    <div class="font-semibold text-sm text-slate-900 dark:text-slate-100">
                                        {{ $b->items->map(fn($item) => $item->service->name)->join(', ') }}
                                    </div>
                                    @if ($b->notes)
                                        <div class="text-xs text-amber-500 dark:text-amber-300 font-medium italic mt-0.5 truncate">
                                            📝 {{ $b->notes }}
                                        </div>
                                    @endif
                                </td>

                                <td class="py-4 px-4 text-sm font-mono text-slate-700 dark:text-slate-300">
                                    <div class="font-bold text-slate-900 dark:text-white">
                                        {{ $b->booking_date ? \Illuminate\Support\Carbon::parse($b->booking_date)->translatedFormat('d M Y') : '-' }}
                                    </div>
                                    <div class="text-xs text-slate-400 font-semibold mt-0.5">{{ substr($b->booking_time, 0, 5) }} WIB</div>
                                </td>

                                <td class="py-4 px-4 font-medium text-sm text-slate-300">
                                    {{ $b->cashier->name ?? $b->staff->name ?? 'Kasir Utama' }}
                                </td>

                                <td class="py-4 px-4 font-black text-base text-slate-900 dark:text-white font-mono">
                                    Rp {{ number_format($b->total_price, 0, ',', '.') }}
                                </td>

                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    <span class="px-3.5 py-1 rounded-full text-xs uppercase tracking-wider font-bold inline-block whitespace-nowrap bg-emerald-500/20 text-emerald-400 border border-emerald-500/40">
                                        Selesai
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-500 dark:text-slate-400">
                                    <div class="max-w-xs mx-auto space-y-2">
                                        <div class="text-3xl">📋</div>
                                        <p class="font-bold text-sm text-slate-700 dark:text-slate-300">Belum ada pelanggan yang dilayani pada tanggal ini.</p>
                                        <p class="text-xs">Silakan pilih tanggal lain pada formulir filter di atas.</p>
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
