<x-admin-layout title="Laporan Transaksi - Grooming Lab & MUA">
    <div class="space-y-6" x-data="{ showDeleteAllModal: false, confirmKeyword: '', showSuccessModal: {{ session('success') ? 'true' : 'false' }} }">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Laporan Transaksi & Pendapatan</h1>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Rekapitulasi penjualan layanan Barbershop dan MUA Studio</p>
            </div>

            <!-- Action & Export Buttons -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.reports.export-excel', request()->all()) }}"
                   class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow transition flex items-center gap-1.5 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Ekspor Excel (.xlsx)</span>
                </a>

                <a href="{{ route('admin.reports.export-pdf', request()->all()) }}" target="_blank"
                   class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-bold text-xs rounded-xl border border-slate-200 dark:border-slate-700 transition flex items-center gap-1.5 shadow-sm active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak / PDF</span>
                </a>

                <!-- Tombol Hapus Semua Data -->
                <button type="button"
                        @click="showDeleteAllModal = true; confirmKeyword = ''"
                        class="px-3.5 py-2 bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-rose-600/20 transition flex items-center gap-1.5 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Hapus Semua Data</span>
                </button>
            </div>
        </div>

        <!-- Filter & Pencarian Toolbar (Auto-submit saat input diubah) -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
            <form id="filterForm" method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                <!-- Search Input -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-400 mb-1">Cari Transaksi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ $search ?? '' }}"
                               placeholder="Nama, kode, layanan, staff..."
                               class="w-full pl-9 pr-8 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @if (!empty($search))
                            <a href="{{ route('admin.reports.index', ['start_date' => $startDate, 'end_date' => $endDate, 'category' => $category]) }}"
                               title="Hapus pencarian"
                               class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-rose-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-400 mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                           onchange="this.form.submit()"
                           class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs focus:ring-emerald-500 cursor-pointer">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-400 mb-1">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                           onchange="this.form.submit()"
                           class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs focus:ring-emerald-500 cursor-pointer">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-400 mb-1">Kategori Layanan</label>
                    <select name="category"
                            onchange="this.form.submit()"
                            class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs focus:ring-emerald-500 cursor-pointer">
                        <option value="all" {{ $category === 'all' ? 'selected' : '' }}>Semua Layanan</option>
                        <option value="barbershop" {{ $category === 'barbershop' ? 'selected' : '' }}>Barbershop</option>
                        <option value="mua" {{ $category === 'mua' ? 'selected' : '' }}>MUA Studio</option>
                    </select>
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
                <table class="w-full text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-950/60 text-xs uppercase tracking-wider text-slate-600 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="py-3 px-2 text-center">No.</th>
                            <th class="py-3 px-3 text-center">Tanggal & Waktu</th>
                            <th class="py-3 px-2 text-center">Jenis</th>
                            <th class="py-3 px-3 text-center">Pelanggan</th>
                            <th class="py-3 px-2 text-center">Kategori</th>
                            <th class="py-3 px-3 text-center">Layanan</th>
                            <th class="py-3 px-3 text-center">Staff / Kasir</th>
                            <th class="py-3 px-3 text-center">Harga</th>
                            <th class="py-3 px-2 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        @forelse ($bookings as $index => $b)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-3 px-2 text-xs font-mono text-center text-slate-500">{{ $bookings->firstItem() + $index }}</td>
                                <td class="py-3 px-3 text-xs text-center text-slate-600 dark:text-slate-400 whitespace-nowrap">
                                    {{ $b->booking_date->format('d/m/Y') }} &bull; {{ substr($b->booking_time, 0, 5) }}
                                </td>
                                <td class="py-3 px-2 text-xs text-center whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $b->is_walk_in ? 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300' }}">
                                        {{ $b->is_walk_in ? 'Offline' : 'Online' }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 font-medium text-center text-slate-900 dark:text-white">{{ $b->customer_name }}</td>
                                <td class="py-3 px-2 text-center whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $b->service_category === 'barbershop' ? 'bg-amber-500/10 text-amber-700 dark:text-amber-400' : 'bg-pink-500/10 text-pink-700 dark:text-pink-400' }}">
                                        {{ $b->service_category === 'mua' ? 'MUA' : 'Barbershop' }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-xs text-center text-slate-600 dark:text-slate-300">{{ $b->items->map(fn($i) => $i->service->name)->join(', ') ?: '-' }}</td>
                                <td class="py-3 px-3 text-xs text-center font-semibold text-slate-700 dark:text-slate-300 whitespace-nowrap">{{ $b->staff->name ?? $b->cashier->name ?? '-' }}</td>
                                <td class="py-3 px-3 text-center font-bold text-emerald-600 dark:text-emerald-400 font-mono whitespace-nowrap">
                                    Rp {{ number_format($b->total_price, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-2 text-center whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold whitespace-nowrap inline-block {{ \App\Models\Booking::STATUS_COLORS[$b->status] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ $b->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-8 text-center text-slate-500 text-sm">
                                    @if (!empty($search))
                                        Tidak ditemukan transaksi dengan kata kunci "<span class="font-bold text-slate-700 dark:text-slate-300">{{ $search }}</span>".
                                    @else
                                        Tidak ada data transaksi pada periode ini.
                                    @endif
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

        <!-- Pop-up Konfirmasi Danger untuk Hapus Semua Data -->
        <div x-show="showDeleteAllModal"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             role="dialog"
             aria-modal="true"
             style="display: none;">
            <!-- Backdrop Blur -->
            <div x-show="showDeleteAllModal"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="showDeleteAllModal = false; confirmKeyword = ''"
                 class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity"></div>

            <div class="min-h-full flex items-center justify-center p-4">
                <div x-show="showDeleteAllModal"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.away="showDeleteAllModal = false; confirmKeyword = ''"
                     class="relative bg-white dark:bg-slate-900 rounded-3xl border-2 border-rose-500/40 text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-md p-6 space-y-5">

                    <!-- Header Danger -->
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-rose-500/15 text-rose-500 border border-rose-500/30 flex items-center justify-center text-2xl flex-shrink-0 shadow-inner">
                            ⚠️
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 mb-1">
                                Danger Zone &bull; Tindakan Kritis
                            </span>
                            <h3 class="text-base font-black text-slate-900 dark:text-white">
                                Hapus Semua Data Transaksi?
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Seluruh riwayat transaksi & pemesanan akan dihapus permanen
                            </p>
                        </div>
                    </div>

                    <!-- Warning Info Box -->
                    <div class="p-4 bg-rose-50 dark:bg-rose-950/40 rounded-2xl border border-rose-200 dark:border-rose-900/60 space-y-2 text-xs text-rose-800 dark:text-rose-300">
                        <div class="font-bold flex items-center gap-1.5 text-rose-600 dark:text-rose-400">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>PERINGATAN KERAS:</span>
                        </div>
                        <p class="leading-relaxed">
                            Tindakan ini akan <strong>MENGHAPUS SELURUH</strong> data riwayat transaksi & pemesanan tanpa terkecuali. Data yang telah dihapus tidak dapat dipulihkan kembali.
                        </p>
                    </div>

                    <!-- Security Confirmation Input -->
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                            Ketik kata <span class="font-black text-rose-600 dark:text-rose-400 tracking-wider">HAPUS</span> untuk mengonfirmasi:
                        </label>
                        <input type="text"
                               x-model="confirmKeyword"
                               placeholder="Ketik HAPUS di sini..."
                               class="w-full text-center font-black tracking-widest uppercase py-2.5 px-4 bg-slate-50 dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-700 focus:border-rose-500 rounded-xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-rose-500 transition">
                    </div>

                    <!-- Actions -->
                    <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <button type="button"
                                @click="showDeleteAllModal = false; confirmKeyword = ''"
                                class="w-full py-2.5 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl transition">
                            Batal
                        </button>
                        <form method="POST" action="{{ route('admin.reports.destroy-all') }}" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    :disabled="confirmKeyword.trim().toUpperCase() !== 'HAPUS'"
                                    :class="confirmKeyword.trim().toUpperCase() === 'HAPUS' ? 'bg-rose-600 hover:bg-rose-500 text-white cursor-pointer shadow-lg shadow-rose-600/30' : 'bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-600 cursor-not-allowed opacity-60'"
                                    class="w-full py-2.5 px-4 font-black text-xs rounded-xl transition active:scale-95 flex items-center justify-center gap-1.5">
                                <span>Ya, Hapus Semua</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pop-up Notifikasi Sukses -->
        @if (session('success'))
            <div x-show="showSuccessModal"
                 x-cloak
                 class="fixed inset-0 z-50 overflow-y-auto"
                 role="dialog"
                 aria-modal="true"
                 style="display: none;">
                <div x-show="showSuccessModal"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="showSuccessModal = false"
                     class="fixed inset-0 bg-slate-950/75 backdrop-blur-sm transition-opacity"></div>

                <div class="min-h-full flex items-center justify-center p-4">
                    <div x-show="showSuccessModal"
                         x-transition:enter="ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         @click.away="showSuccessModal = false"
                         class="relative bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 text-center overflow-hidden shadow-2xl transform transition-all w-full max-w-md p-6 space-y-5">

                        <div class="w-16 h-16 rounded-3xl bg-emerald-500/15 border-2 border-emerald-500/30 text-emerald-500 flex items-center justify-center text-3xl mx-auto shadow-inner">
                            ✓
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900 dark:text-white">Operasi Berhasil!</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                {{ session('success') }}
                            </p>
                        </div>

                        <button type="button" @click="showSuccessModal = false"
                                class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs rounded-xl shadow-lg shadow-emerald-600/30 transition active:scale-95">
                            Selesai & Tutup
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>
