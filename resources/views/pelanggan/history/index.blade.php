<x-pelanggan-layout title="Riwayat Transaksi - Grooming Lab & MUA">
    <div class="max-w-xl mx-auto py-4 space-y-6">
        <div>
            <h1 class="text-2xl font-extrabold text-white">Riwayat Transaksi</h1>
            <p class="text-xs text-slate-400 mt-0.5">Daftar pesanan Anda yang telah selesai atau dibatalkan</p>
        </div>

        <!-- Filter Tabs (FR-CUST-08) -->
        <div class="flex items-center gap-2 p-1.5 bg-slate-900 border border-slate-800 rounded-2xl">
            <a href="{{ route('pelanggan.history.index', ['category' => 'all']) }}"
               class="flex-1 text-center py-2 px-3 rounded-xl text-xs font-bold transition {{ $currentFilter === 'all' ? 'bg-amber-500 text-slate-950 shadow' : 'text-slate-400 hover:text-white' }}">
                Semua
            </a>
            <a href="{{ route('pelanggan.history.index', ['category' => 'barbershop']) }}"
               class="flex-1 text-center py-2 px-3 rounded-xl text-xs font-bold transition {{ $currentFilter === 'barbershop' ? 'bg-amber-500 text-slate-950 shadow' : 'text-slate-400 hover:text-white' }}">
                Barbershop
            </a>
            <a href="{{ route('pelanggan.history.index', ['category' => 'mua']) }}"
               class="flex-1 text-center py-2 px-3 rounded-xl text-xs font-bold transition {{ $currentFilter === 'mua' ? 'bg-amber-500 text-slate-950 shadow' : 'text-slate-400 hover:text-white' }}">
                MUA Studio
            </a>
        </div>

        <!-- History List (FR-CUST-07) -->
        <div class="space-y-3">
            @forelse ($bookings as $booking)
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 sm:p-5 hover:border-slate-700 transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-mono font-bold text-slate-300">#{{ $booking->booking_code }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $booking->service_category === 'barbershop' ? 'bg-amber-500/10 text-amber-400' : 'bg-pink-500/10 text-pink-400' }}">
                                    {{ ucfirst($booking->service_category) }}
                                </span>
                            </div>
                            <h3 class="font-bold text-white text-sm mt-1">
                                {{ $booking->items->map(fn($item) => $item->service->name)->join(', ') }}
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">
                                {{ $booking->booking_date->translatedFormat('d M Y') }} &bull; Jam {{ $booking->booking_time }} WIB
                            </p>
                        </div>

                        <div class="text-right">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $booking->status_color }}">
                                {{ $booking->status_label }}
                            </span>
                            <div class="mt-2 text-sm font-extrabold text-white">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    @if ($booking->notes)
                        <div class="mt-3 pt-2.5 border-t border-slate-800/80 text-[11px] text-slate-400">
                            <span class="text-slate-500">Catatan:</span> {{ $booking->notes }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 text-center space-y-2">
                    <p class="text-sm font-semibold text-slate-300">Belum Ada Riwayat Transaksi</p>
                    <p class="text-xs text-slate-500">Pesanan yang telah selesai atau dibatalkan akan muncul di sini.</p>
                </div>
            @endforelse
        </div>

        <div>
            {{ $bookings->links() }}
        </div>
    </div>
</x-pelanggan-layout>
