<x-admin-layout title="Kelola Pelanggan - Grooming Lab & MUA">
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-white">Data Pelanggan</h1>
                <p class="text-xs text-slate-400 mt-1">Daftar pelanggan terdaftar, riwayat booking, dan kontrol akun</p>
            </div>

            <!-- Search Bar (FR-ADM-02) -->
            <form method="GET" action="{{ route('admin.customers.index') }}" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 w-64"
                       placeholder="Cari nama, email, nomor HP..." />
                <button type="submit" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow transition">
                    Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('admin.customers.index') }}" class="text-xs text-slate-400 hover:text-white underline">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-950/60 text-xs uppercase tracking-wider text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="py-3 px-4">Nama Pelanggan</th>
                            <th class="py-3 px-4">Kontak</th>
                            <th class="py-3 px-4 text-center">Total Booking</th>
                            <th class="py-3 px-4">Status Akun</th>
                            <th class="py-3 px-4">Bergabung</th>
                            <th class="py-3 px-4 text-center">Aksi / Kontrol</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80">
                        @forelse ($customers as $customer)
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-white">{{ $customer->name }}</div>
                                    <div class="text-[10px] text-slate-500 font-mono">ID: #{{ $customer->id }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-xs">
                                    <div>{{ $customer->email }}</div>
                                    <div class="text-slate-400 font-mono">{{ $customer->phone ?? '-' }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-800 text-slate-200">
                                        {{ $customer->bookings_count }}x
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $customer->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-red-500/10 text-red-400 border border-red-500/30' }}">
                                        {{ $customer->is_active ? 'Aktif' : 'Diblokir' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-xs text-slate-400">{{ $customer->created_at->format('d M Y') }}</td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Detail & History -->
                                        <a href="{{ route('admin.customers.show', $customer) }}"
                                           class="px-2.5 py-1 text-xs rounded-lg bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 border border-blue-500/30 transition">
                                            Riwayat
                                        </a>

                                        <!-- Toggle Block (FR-ADM-02) -->
                                        <form method="POST" action="{{ route('admin.customers.toggle-block', $customer) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="px-2.5 py-1 text-xs rounded-lg border transition {{ $customer->is_active ? 'border-amber-500/30 text-amber-400 hover:bg-amber-500/10' : 'border-emerald-500/30 text-emerald-400 hover:bg-emerald-500/10' }}">
                                                {{ $customer->is_active ? 'Blokir' : 'Buka Blokir' }}
                                            </button>
                                        </form>

                                        <!-- Reset Password (FR-ADM-02) -->
                                        <form method="POST" action="{{ route('admin.customers.reset-password', $customer) }}" onsubmit="return confirm('Reset password akun {{ $customer->name }} ke password123?');">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 text-xs rounded-lg border border-slate-700 text-slate-300 hover:text-white hover:bg-slate-800 transition" title="Reset password ke default">
                                                Reset Pass
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-slate-500 text-sm">Tidak ada data pelanggan ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-800">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>

