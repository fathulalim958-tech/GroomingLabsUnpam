<x-admin-layout title="Detail Pelanggan - Grooming Lab & MUA">
    <div class="space-y-6 max-w-4xl mx-auto py-2">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-white">Detail & Riwayat Pelanggan</h1>
                <p class="text-xs text-slate-400 mt-1">Informasi akun dan seluruh riwayat pemesanan pelanggan</p>
            </div>
            <a href="{{ route('admin.customers.index') }}" class="text-xs text-slate-400 hover:text-white transition">
                &larr; Kembali ke Daftar Pelanggan
            </a>
        </div>

        <!-- Customer Profile Card -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-slate-950 font-black text-xl">
                    {{ strtoupper(substr($customer->name, 0, 2)) }}
                </div>
                <div>
                    <h2 class="text-lg font-bold text-white">{{ $customer->name }}</h2>
                    <p class="text-xs text-slate-400">{{ $customer->email }} &bull; {{ $customer->phone ?? 'Tidak ada telepon' }}</p>
                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $customer->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-red-500/10 text-red-400 border border-red-500/30' }}">
                        {{ $customer->is_active ? 'Status Akun: Aktif' : 'Status Akun: Diblokir' }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <form method="POST" action="{{ route('admin.customers.toggle-block', $customer) }}">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 text-xs rounded-xl border transition {{ $customer->is_active ? 'border-amber-500/40 text-amber-400 hover:bg-amber-500/10' : 'border-emerald-500/40 text-emerald-400 hover:bg-emerald-500/10' }}">
                        {{ $customer->is_active ? 'Blokir Akun' : 'Buka Blokir' }}
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.customers.reset-password', $customer) }}" onsubmit="return confirm('Reset password akun pelanggan ini ke password123?');">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 text-xs rounded-xl border border-slate-700 text-slate-300 hover:bg-slate-800 transition">
                        Reset Password
                    </button>
                </form>
            </div>
        </div>

        <!-- Booking History of Customer -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl">
            <h3 class="text-base font-bold text-white mb-4">Riwayat Seluruh Pemesanan ({{ $customer->bookings->count() }})</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-950/60 text-xs uppercase tracking-wider text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="py-3 px-4">No. Antrian</th>
                            <th class="py-3 px-4">Kode</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4">Layanan</th>
                            <th class="py-3 px-4">Tanggal & Jam</th>
                            <th class="py-3 px-4">Total</th>
                            <th class="py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80">
                        @forelse ($customer->bookings as $b)
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="py-3 px-4 font-mono font-bold text-amber-400">{{ $b->queue_number }}</td>
                                <td class="py-3 px-4 text-xs font-mono text-slate-400">{{ $b->booking_code }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $b->service_category === 'barbershop' ? 'bg-amber-500/10 text-amber-400' : 'bg-pink-500/10 text-pink-400' }}">
                                        {{ ucfirst($b->service_category) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-xs">{{ $b->items->map(fn($i) => $i->service->name)->join(', ') }}</td>
                                <td class="py-3 px-4 text-xs">{{ $b->booking_date->format('d/m/Y') }} &bull; {{ $b->booking_time }}</td>
                                <td class="py-3 px-4 font-bold text-white">Rp {{ number_format($b->total_price, 0, ',', '.') }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $b->status_color }}">
                                        {{ $b->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 text-center text-slate-500 text-sm">Pelanggan belum pernah melakukan pemesanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
