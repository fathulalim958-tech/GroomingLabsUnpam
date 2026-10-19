<x-pelanggan-layout title="Order Aktif & Antrian - Grooming Lab & MUA">
    <div class="max-w-xl mx-auto py-4 space-y-6" x-data="queueMonitor()">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-white">Tiket Antrian Aktif</h1>
                <p class="text-xs text-slate-400 mt-0.5">Pantau giliran Anda secara real-time</p>
            </div>
            <!-- Live Indicator Badge -->
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Live Sync Aktif</span>
            </div>
        </div>

        <!-- Real-Time Current Serving Queue Status (FR-CUST-06) -->
        <div class="grid grid-cols-2 gap-3">
            <!-- Barbershop Current Serving -->
            <div class="bg-gradient-to-br from-slate-900 to-amber-950/30 border border-amber-500/30 rounded-2xl p-4 text-center shadow-lg">
                <span class="text-[11px] font-bold uppercase tracking-wider text-amber-400">Sedang Dilayani</span>
                <p class="text-xs text-slate-400">Barbershop</p>
                <div class="mt-2 text-3xl font-black text-amber-300 font-mono tracking-wider" x-text="currentServing.barbershop || '—'">
                    {{ $currentServing['barbershop']?->queue_number ?? '—' }}
                </div>
                <span class="text-[10px] text-slate-400 block mt-1">Antrian Di Kursi</span>
            </div>

            <!-- MUA Current Serving -->
            <div class="bg-gradient-to-br from-slate-900 to-pink-950/30 border border-pink-500/30 rounded-2xl p-4 text-center shadow-lg">
                <span class="text-[11px] font-bold uppercase tracking-wider text-pink-400">Sedang Dilayani</span>
                <p class="text-xs text-slate-400">MUA Studio</p>
                <div class="mt-2 text-3xl font-black text-pink-300 font-mono tracking-wider" x-text="currentServing.mua || '—'">
                    {{ $currentServing['mua']?->queue_number ?? '—' }}
                </div>
                <span class="text-[10px] text-slate-400 block mt-1">Antrian Di Studio</span>
            </div>
        </div>

        <!-- Customer Active Tickets -->
        <div class="space-y-4">
            @forelse ($activeBookings as $booking)
                <div class="bg-slate-900 border-2 {{ $booking->status === 'in_progress' ? 'border-emerald-500 shadow-emerald-500/10' : ($booking->status === 'skipped' ? 'border-amber-500 shadow-amber-500/10' : 'border-slate-800') }} rounded-3xl p-5 sm:p-6 shadow-2xl relative overflow-hidden transition-all duration-300">
                    <!-- Category & Status Badge -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $booking->service_category === 'barbershop' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/30' : 'bg-pink-500/10 text-pink-400 border border-pink-500/30' }}">
                            {{ ucfirst($booking->service_category) }}
                        </span>

                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $booking->status_color }}">
                            {{ $booking->status_label }}
                        </span>
                    </div>

                    <!-- Skipped Warning Notification if applicable -->
                    @if ($booking->status === 'skipped')
                        <div class="mb-4 p-3 bg-amber-500/10 border border-amber-500/40 rounded-xl text-xs text-amber-300 flex items-start gap-2">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <span class="font-bold">Antrian Terlewat!</span>
                                <p class="text-amber-400/90 mt-0.5">Tiket Anda tetap aktif dan tidak hangus. Harap segera melapor ke meja kasir agar nomor Anda dipanggil kembali.</p>
                            </div>
                        </div>
                    @endif

                    @if ($booking->status === 'in_progress')
                        <div class="mb-4 p-3 bg-emerald-500/10 border border-emerald-500/40 rounded-xl text-xs text-emerald-300 flex items-center gap-2 animate-pulse">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-bold">Giliran Anda sedang dilayani sekarang! Silakan menuju tempat layanan.</span>
                        </div>
                    @endif

                    <!-- Large Queue Number Display (FR-CUST-06) -->
                    <div class="text-center py-4 my-2 bg-slate-950/60 rounded-2xl border border-slate-800/80">
                        <span class="text-xs text-slate-400 uppercase tracking-widest font-semibold block">Nomor Antrian Anda</span>
                        <div class="text-5xl sm:text-6xl font-black text-amber-400 font-mono tracking-widest my-1">
                            {{ $booking->queue_number }}
                        </div>
                        <span class="text-xs text-slate-400 font-mono">Kode: {{ $booking->booking_code }}</span>
                    </div>

                    <!-- Details Table -->
                    <div class="space-y-2 mt-4 pt-4 border-t border-slate-800 text-xs">
                        <div class="flex justify-between py-1">
                            <span class="text-slate-400">Tanggal & Jam:</span>
                            <span class="font-semibold text-white">{{ $booking->booking_date->translatedFormat('d M Y') }} &bull; {{ $booking->booking_time }} WIB</span>
                        </div>

                        <div class="flex justify-between py-1">
                            <span class="text-slate-400">Layanan:</span>
                            <span class="font-semibold text-white text-right max-w-[60%]">
                                {{ $booking->items->map(fn($item) => $item->service->name)->join(', ') }}
                            </span>
                        </div>

                        @if ($booking->notes)
                            <div class="flex justify-between py-1">
                                <span class="text-slate-400">Catatan Khusus:</span>
                                <span class="text-slate-300 text-right italic max-w-[60%]">{{ $booking->notes }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between py-1 border-t border-slate-800/60 pt-2 text-sm">
                            <span class="font-bold text-white">Total Biaya:</span>
                            <span class="font-extrabold text-amber-400">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Cancel Button (Only if waiting or skipped) -->
                    @if (in_array($booking->status, ['waiting', 'skipped']))
                        <div class="mt-5 pt-4 border-t border-slate-800 flex justify-end">
                            <form method="POST" action="{{ route('pelanggan.order.cancel', $booking) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg border border-red-500/30 transition">
                                    Batalkan Booking
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-8 text-center space-y-4">
                    <div class="w-16 h-16 rounded-2xl bg-slate-800 text-slate-500 flex items-center justify-center mx-auto">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white">Tidak Ada Antrian Aktif</h3>
                        <p class="text-xs text-slate-400 mt-1">Anda belum memiliki pesanan antrian hari ini.</p>
                    </div>
                    <div>
                        <a href="{{ route('pelanggan.booking.category') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs rounded-xl transition shadow">
                            Ambil Nomor Antrian Sekarang &rarr;
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    @push('scripts')
    <script>
        function queueMonitor() {
            return {
                currentServing: {
                    barbershop: '{{ $currentServing['barbershop']?->queue_number ?? '' }}',
                    mua: '{{ $currentServing['mua']?->queue_number ?? '' }}'
                },
                init() {
                    // Real-time polling every 5 seconds (FR non-fungsional)
                    setInterval(() => {
                        this.fetchQueueStatus();
                    }, 5000);
                },
                fetchQueueStatus() {
                    fetch('{{ route('pelanggan.order.check-queue') }}', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.current_serving) {
                            this.currentServing.barbershop = data.current_serving.barbershop || '—';
                            this.currentServing.mua = data.current_serving.mua || '—';
                        }
                    })
                    .catch(err => console.error('Error fetching queue status:', err));
                }
            }
        }
    </script>
    @endpush
</x-pelanggan-layout>

