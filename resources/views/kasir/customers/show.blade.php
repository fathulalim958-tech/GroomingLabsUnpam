<x-kasir-layout title="Detail Pelanggan - {{ $customer->name }}">
    <div class="space-y-6">
        <!-- Back Button & Title -->
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('kasir.customers.index') }}"
                   class="p-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">
                        RIWAYAT PELANGGAN: {{ $customer->name }}
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ $customer->phone ?? 'Tidak ada nomor telepon' }} &bull; {{ $customer->email }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Bookings History Table -->
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl overflow-hidden shadow-lg">
            <div class="p-3.5 sm:p-5 border-b border-slate-200 dark:border-slate-700">
                <h3 class="font-bold text-sm text-slate-900 dark:text-white">Daftar Transaksi / Reservasi Pelanggan Ini</h3>
            </div>

            <!-- Mobile View: Kartu Riwayat Order di HP -->
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
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase whitespace-nowrap inline-block {{ $b->status_color }}">
                                {{ $b->status_label }}
                            </span>
                        </div>

                        <div class="flex items-start justify-between gap-2 text-xs">
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">
                                {{ $b->booking_date->translatedFormat('d M Y') }} &bull; {{ $b->booking_time }} WIB
                            </div>
                            <div class="font-black text-xs text-slate-900 dark:text-white font-mono">
                                Rp {{ number_format($b->total_price, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="text-xs text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-900/60 p-2 rounded-lg">
                            <span class="text-slate-500 dark:text-slate-400">Layanan:</span>
                            <span class="font-semibold text-slate-900 dark:text-slate-200">{{ $b->items->map(fn($i) => $i->service->name)->join(', ') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-slate-500">Belum ada riwayat pesanan.</div>
                @endforelse
            </div>

            <!-- Desktop View: Tabel -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-200 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-black text-xs uppercase tracking-wider border-b border-slate-300 dark:border-slate-800">
                        <tr>
                            <th class="py-4 px-4 font-black">No. Antrian</th>
                            <th class="py-4 px-4 font-black">Kode Booking</th>
                            <th class="py-4 px-4 font-black">Kategori</th>
                            <th class="py-4 px-4 font-black">Layanan</th>
                            <th class="py-4 px-4 font-black">Tanggal & Jam</th>
                            <th class="py-4 px-4 font-black">Total Tarif</th>
                            <th class="py-4 px-4 font-black">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/70">
                        @forelse ($bookings as $b)
                            <tr class="hover:bg-slate-100/70 dark:hover:bg-slate-700/60 transition">
                                <td class="py-4 px-4 font-mono font-black text-base text-amber-500 dark:text-amber-400">
                                    {{ $b->queue_number }}
                                </td>
                                <td class="py-4 px-4 font-mono text-xs text-slate-500 dark:text-slate-300">
                                    {{ $b->booking_code }}
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-2 py-0.5 rounded text-xs font-bold uppercase {{ $b->service_category === 'barbershop' ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400' : 'bg-pink-500/10 text-pink-600 dark:text-pink-400' }}">
                                        {{ ucfirst($b->service_category) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 font-medium text-slate-900 dark:text-white">
                                    {{ $b->items->map(fn($i) => $i->service->name)->join(', ') }}
                                </td>
                                <td class="py-4 px-4 font-mono text-xs">
                                    {{ $b->booking_date->translatedFormat('d M Y') }}, {{ $b->booking_time }} WIB
                                </td>
                                <td class="py-4 px-4 font-black font-mono">
                                    Rp {{ number_format($b->total_price, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase whitespace-nowrap inline-block {{ $b->status_color }}">
                                        {{ $b->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-500">Belum ada riwayat pesanan.</td>
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

