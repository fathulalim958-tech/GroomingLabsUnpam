<x-kasir-layout title="Kelola Pelanggan - Grooming Lab">
    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-5 shadow-sm">
            <div>
                <h1 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                    KELOLA DATA PELANGGAN
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Database pelanggan yang telah membuat akun maupun pelanggan walk-in salon.
                </p>
            </div>

            <!-- Tab Switcher -->
            <div class="w-full sm:w-auto grid grid-cols-2 gap-1.5 bg-slate-100 dark:bg-slate-900 p-1 sm:p-1.5 rounded-xl border border-slate-200 dark:border-slate-700">
                <a href="{{ route('kasir.customers.index', ['tab' => 'registered']) }}"
                   class="px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-bold transition flex items-center justify-center gap-1.5 {{ $tab === 'registered' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                    <span>Terdaftar</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'registered' ? 'bg-blue-800 text-white' : 'bg-blue-500/20 text-blue-600 dark:text-blue-300 font-bold' }}">
                        {{ $totalRegistered }}
                    </span>
                </a>

                <a href="{{ route('kasir.customers.index', ['tab' => 'walkin']) }}"
                   class="px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-bold transition flex items-center justify-center gap-1.5 {{ $tab === 'walkin' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                    <span>Walk-In</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'walkin' ? 'bg-blue-800 text-white' : 'bg-blue-500/20 text-blue-600 dark:text-blue-300 font-bold' }}">
                        {{ $totalWalkIns }}
                    </span>
                </a>
            </div>
        </div>

        <!-- Search Toolbar -->
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-4 sm:p-5 shadow-sm">
            <form method="GET" action="{{ route('kasir.customers.index') }}" class="flex flex-wrap items-center gap-3">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="flex-1 min-w-[240px]">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, atau no telepon pelanggan..."
                           class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs sm:text-sm rounded-xl shadow transition">
                    Cari Pelanggan
                </button>
                @if (!empty($search))
                    <a href="{{ route('kasir.customers.index', ['tab' => $tab]) }}" class="text-xs text-sky-500 dark:text-sky-400 font-bold hover:underline px-2">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table Customers -->
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl overflow-hidden shadow-lg">
            <!-- Mobile View: Kartu Pelanggan di HP -->
            <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-700/70">
                @forelse ($customers as $c)
                    @php
                        $lastDate = $tab === 'registered' ? $c->bookings_max_booking_date : $c->last_booking_date;
                    @endphp
                    <div class="p-3.5 space-y-2">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <div class="font-bold text-sm text-slate-900 dark:text-white">
                                    {{ $tab === 'registered' ? $c->name : $c->customer_name }}
                                </div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">
                                    {{ $tab === 'registered' ? ($c->phone ?? '-') : ($c->customer_phone ?? '-') }}
                                </div>
                                @if ($tab === 'registered' && $c->email)
                                    <div class="text-[11px] text-slate-400 truncate max-w-[200px]">{{ $c->email }}</div>
                                @endif
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20 whitespace-nowrap">
                                {{ $tab === 'registered' ? $c->bookings_count : $c->total_orders }} Pesanan
                            </span>
                        </div>

                        <div class="flex items-center justify-between pt-1 text-xs border-t border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500 dark:text-slate-400 text-[11px]">
                                Terakhir: {{ $lastDate ? \Carbon\Carbon::parse($lastDate)->translatedFormat('d M Y') : 'Belum Pernah' }}
                            </span>
                            @if ($tab === 'registered')
                                <a href="{{ route('kasir.customers.show', $c) }}"
                                   class="px-2.5 py-1 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-lg transition inline-flex items-center gap-1 active:scale-95">
                                    <span>Riwayat Order</span> &rarr;
                                </a>
                            @else
                                <span class="text-[10px] text-slate-400 font-semibold bg-slate-100 dark:bg-slate-750 px-2 py-0.5 rounded">
                                    Pelanggan Walk-In
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center text-slate-500 dark:text-slate-400 px-4">
                        <div class="text-3xl">👥</div>
                        <p class="font-bold text-sm text-slate-700 dark:text-slate-300 mt-2">Data pelanggan tidak ditemukan.</p>
                    </div>
                @endforelse
            </div>

            <!-- Desktop View: Tabel Lengkap -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-200 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-black text-xs uppercase tracking-wider border-b border-slate-300 dark:border-slate-800">
                        <tr>
                            <th class="py-4 px-4 font-black">Nama Pelanggan</th>
                            <th class="py-4 px-4 font-black">Kontak / No. HP</th>
                            @if ($tab === 'registered')
                                <th class="py-4 px-4 font-black">Email</th>
                            @endif
                            <th class="py-4 px-4 font-black">Total Transaksi / Order</th>
                            <th class="py-4 px-4 font-black">Kunjungan Terakhir</th>
                            <th class="py-4 px-4 font-black text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/70">
                        @forelse ($customers as $c)
                            <tr class="hover:bg-slate-100/70 dark:hover:bg-slate-700/60 transition">
                                <td class="py-4 px-4 font-bold text-base text-slate-900 dark:text-white">
                                    {{ $tab === 'registered' ? $c->name : $c->customer_name }}
                                </td>

                                <td class="py-4 px-4 font-mono text-sm text-slate-700 dark:text-slate-300 font-medium">
                                    {{ $tab === 'registered' ? ($c->phone ?? '-') : ($c->customer_phone ?? '-') }}
                                </td>

                                @if ($tab === 'registered')
                                    <td class="py-4 px-4 text-xs text-slate-600 dark:text-slate-300">
                                        {{ $c->email }}
                                    </td>
                                @endif

                                <td class="py-4 px-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                                        {{ $tab === 'registered' ? $c->bookings_count : $c->total_orders }} Pesanan
                                    </span>
                                </td>

                                <td class="py-4 px-4 text-xs font-mono text-slate-700 dark:text-slate-300 font-semibold">
                                    @php
                                        $lastDate = $tab === 'registered' ? $c->bookings_max_booking_date : $c->last_booking_date;
                                    @endphp
                                    {{ $lastDate ? \Carbon\Carbon::parse($lastDate)->translatedFormat('d M Y') : 'Belum Pernah' }}
                                </td>

                                <td class="py-4 px-4 text-center">
                                    @if ($tab === 'registered')
                                        <a href="{{ route('kasir.customers.show', $c) }}"
                                           class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl shadow transition inline-flex items-center gap-1">
                                            <span>Riwayat Order</span> &rarr;
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 font-medium">Walk-in</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $tab === 'registered' ? 6 : 5 }}" class="py-12 text-center text-slate-500 dark:text-slate-400">
                                    <div class="max-w-xs mx-auto space-y-2">
                                        <div class="text-3xl">👥</div>
                                        <p class="font-bold text-sm text-slate-700 dark:text-slate-300">Data pelanggan tidak ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($customers->hasPages())
                <div class="px-5 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </div>
</x-kasir-layout>

