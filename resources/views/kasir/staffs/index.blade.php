<x-kasir-layout title="Informasi Pekerja & Kapster - Grooming Lab">
    <div class="space-y-6">
        <!-- Top Banner Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-5 shadow-sm">
            <div>
                <h1 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                    INFORMASI PEKERJA / KAPSTER & MUA
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Pantau status keaktifan, ketersediaan, kontak WhatsApp, dan beban kerja kapster & beautician MUA hari ini.
                </p>
            </div>

            <!-- Role Quick Filter Tabs -->
            <div class="w-full sm:w-auto grid grid-cols-3 gap-1 bg-slate-100 dark:bg-slate-900 p-1 sm:p-1.5 rounded-xl border border-slate-200 dark:border-slate-700">
                <a href="{{ route('kasir.staffs.index', ['role' => 'all', 'status' => $statusFilter, 'search' => $search]) }}"
                   class="px-3 py-2 rounded-lg text-xs sm:text-sm font-bold transition flex items-center justify-center {{ $roleFilter === 'all' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                    Semua
                </a>
                <a href="{{ route('kasir.staffs.index', ['role' => 'kapster', 'status' => $statusFilter, 'search' => $search]) }}"
                   class="px-3 py-2 rounded-lg text-xs sm:text-sm font-bold transition flex items-center justify-center gap-1.5 {{ $roleFilter === 'kapster' ? 'bg-amber-500 text-slate-950 font-black shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                    <span>💈 Kapster</span>
                </a>
                <a href="{{ route('kasir.staffs.index', ['role' => 'mua', 'status' => $statusFilter, 'search' => $search]) }}"
                   class="px-3 py-2 rounded-lg text-xs sm:text-sm font-bold transition flex items-center justify-center gap-1.5 {{ $roleFilter === 'mua' ? 'bg-pink-500 text-white font-black shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                    <span>💄 MUA</span>
                </a>
            </div>
        </div>

        <!-- 4 KPI Metrics: Grid Minimalis -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4">
            <!-- 1. Total Pekerja -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl p-4 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Tim</span>
                    <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-0.5">{{ $totalStaff }}</div>
                    <span class="text-[10px] text-slate-500 dark:text-slate-400">Pekerja terdaftar</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-700/60 flex items-center justify-center text-lg">
                    👥
                </div>
            </div>

            <!-- 2. Kapster Barbershop -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl p-4 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-amber-500 dark:text-amber-400">Kapster Aktif</span>
                    <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-0.5">
                        {{ $activeKapsters }} <span class="text-xs font-semibold text-slate-400">/ {{ $totalKapsters }}</span>
                    </div>
                    <span class="text-[10px] text-amber-600 dark:text-amber-400/90 font-medium">Barbershop</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-lg">
                    💈
                </div>
            </div>

            <!-- 3. MUA Beautician -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl p-4 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-pink-500 dark:text-pink-400">MUA Aktif</span>
                    <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-0.5">
                        {{ $activeMuas }} <span class="text-xs font-semibold text-slate-400">/ {{ $totalMuas }}</span>
                    </div>
                    <span class="text-[10px] text-pink-600 dark:text-pink-400/90 font-medium">Studio Tata Rias</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-pink-500/10 text-pink-500 flex items-center justify-center text-lg">
                    💄
                </div>
            </div>

            <!-- 4. Sedang Bertugas -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl p-4 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-sky-500 dark:text-sky-400">Sedang Melayani</span>
                    <div class="text-2xl sm:text-3xl font-black text-sky-600 dark:text-sky-400 mt-0.5">{{ $onDutyCount }}</div>
                    <span class="text-[10px] text-slate-500 dark:text-slate-400">Ada sesi berlangsung</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-500 flex items-center justify-center text-lg">
                    ✂️
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-4 sm:p-5 shadow-sm space-y-4">
            <form method="GET" action="{{ route('kasir.staffs.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <input type="hidden" name="role" value="{{ $roleFilter }}">

                <!-- Status Filter -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase mb-1">Status Keaktifan:</label>
                    <select name="status" onchange="this.form.submit()"
                            class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        <option value="all">Semua Status</option>
                        <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Hanya Aktif</option>
                        <option value="inactive" {{ $statusFilter === 'inactive' ? 'selected' : '' }}>Hanya Nonaktif</option>
                    </select>
                </div>

                <!-- Search Input -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase mb-1">Cari Pekerja:</label>
                    <div class="flex gap-2">
                        <input type="text" name="search" value="{{ $search }}"
                               placeholder="Ketik nama atau nomor HP/WA..."
                               class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500">
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-xs sm:text-sm font-bold rounded-xl transition flex-shrink-0">
                            Cari
                        </button>
                    </div>
                </div>

                <!-- Reset Filter Button -->
                <div class="flex items-end">
                    @if ($roleFilter !== 'all' || $statusFilter !== 'all' || !empty($search))
                        <a href="{{ route('kasir.staffs.index') }}"
                           class="w-full text-center px-3.5 py-2 text-xs sm:text-sm font-bold text-rose-600 dark:text-rose-400 bg-rose-500/10 hover:bg-rose-500 hover:text-white border border-rose-500/30 rounded-xl transition">
                            Reset Filter
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Worker List Container -->
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-2xl overflow-hidden shadow-lg">
            <!-- Mobile Card View (Khusus Layar HP) -->
            <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-700/70">
                @forelse ($staffs as $staff)
                    @php
                        $inProgressBooking = $staff->bookings->first();
                        $isServing = !is_null($inProgressBooking);
                    @endphp
                    <div class="p-4 space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center font-black text-base {{ $staff->role === 'kapster' ? 'bg-amber-500/10 text-amber-500 border border-amber-500/30' : 'bg-pink-500/10 text-pink-500 border border-pink-500/30' }}">
                                    {{ strtoupper(substr($staff->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-sm text-slate-900 dark:text-white">{{ $staff->name }}</div>
                                    <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $staff->role === 'kapster' ? 'bg-amber-500/15 text-amber-600 dark:text-amber-400' : 'bg-pink-500/15 text-pink-600 dark:text-pink-400' }}">
                                        {{ $staff->role === 'kapster' ? '💈 Kapster Barbershop' : '💄 MUA Beautician' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div>
                                @if (!$staff->is_active)
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-300">
                                        Libur / Nonaktif
                                    </span>
                                @elseif ($isServing)
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-sky-500 text-slate-950 animate-pulse">
                                        Sedang Bertugas
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500 text-slate-950">
                                        ✓ Aktif & Siap
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Contact & Serving Details -->
                        <div class="bg-slate-50 dark:bg-slate-900/60 p-2.5 rounded-xl border border-slate-100 dark:border-slate-800 text-xs space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500 dark:text-slate-400">Kontak WhatsApp:</span>
                                @if ($staff->phone)
                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $staff->phone)) }}"
                                       target="_blank"
                                       class="font-mono font-bold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1">
                                        <span>💬 {{ $staff->phone }}</span>
                                    </a>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-slate-500 dark:text-slate-400">Pelayanan Hari Ini:</span>
                                <span class="font-bold text-slate-900 dark:text-white">
                                    {{ $staff->bookings_today_completed }} selesai <span class="text-slate-400">({{ $staff->bookings_today_total }} total)</span>
                                </span>
                            </div>

                            @if ($isServing)
                                <div class="pt-1 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between text-sky-600 dark:text-sky-300 font-semibold">
                                    <span>Sedang Layani:</span>
                                    <span class="font-mono font-bold">{{ $inProgressBooking->queue_number }} ({{ $inProgressBooking->customer_name }})</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-500 dark:text-slate-400 px-4">
                        <div class="text-3xl">👥</div>
                        <p class="font-bold text-sm text-slate-700 dark:text-slate-300 mt-2">Tidak ada data pekerja ditemukan.</p>
                        <p class="text-xs text-slate-500 mt-0.5">Silakan sesuaikan filter atau tambahkan data staf di menu admin.</p>
                    </div>
                @endforelse
            </div>

            <!-- Desktop View: Tabel Lengkap -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-200 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-black text-xs uppercase tracking-wider border-b border-slate-300 dark:border-slate-800">
                        <tr>
                            <th class="py-4 px-4 font-black">Nama Pekerja</th>
                            <th class="py-4 px-4 font-black">Peran / Kategori</th>
                            <th class="py-4 px-4 font-black">No. WhatsApp / HP</th>
                            <th class="py-4 px-4 font-black">Status Keaktifan</th>
                            <th class="py-4 px-4 font-black">Tugas Hari Ini</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/70">
                        @forelse ($staffs as $staff)
                            @php
                                $inProgressBooking = $staff->bookings->first();
                                $isServing = !is_null($inProgressBooking);
                            @endphp
                            <tr class="hover:bg-slate-100/70 dark:hover:bg-slate-700/60 transition {{ $isServing ? 'bg-sky-50/50 dark:bg-sky-950/20' : '' }}">
                                <!-- Staff Name & Avatar -->
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm {{ $staff->role === 'kapster' ? 'bg-amber-500/10 text-amber-500 border border-amber-500/30' : 'bg-pink-500/10 text-pink-500 border border-pink-500/30' }}">
                                            {{ strtoupper(substr($staff->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-base text-slate-900 dark:text-white">{{ $staff->name }}</div>
                                            <div class="text-xs text-slate-400 font-mono">ID: #{{ $staff->id }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Role -->
                                <td class="py-4 px-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $staff->role === 'kapster' ? 'bg-amber-500/20 text-amber-600 dark:text-amber-400' : 'bg-pink-500/20 text-pink-600 dark:text-pink-400' }}">
                                        {{ $staff->role === 'kapster' ? '💈 Kapster Barbershop' : '💄 MUA Beautician' }}
                                    </span>
                                </td>

                                <!-- WhatsApp Contact -->
                                <td class="py-4 px-4 font-mono text-sm">
                                    @if ($staff->phone)
                                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $staff->phone)) }}"
                                           target="_blank"
                                           class="font-bold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1.5"
                                           title="Kirim pesan WhatsApp">
                                            <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.101.005.24-.038.376.289.144.346.491 1.2.534 1.287.043.087.072.188.014.303-.058.116-.087.188-.173.289l-.26.303c-.087.087-.178.182-.077.355.101.173.449.741.964 1.201.662.591 1.221.774 1.394.861.173.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z" />
                                            </svg>
                                            <span>{{ $staff->phone }}</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400 italic">Tidak ada nomor</span>
                                    @endif
                                </td>

                                <!-- Status Keaktifan -->
                                <td class="py-4 px-4">
                                    @if (!$staff->is_active)
                                        <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-slate-200 text-slate-700 dark:bg-slate-750 dark:text-slate-400">
                                            Libur / Nonaktif
                                        </span>
                                    @elseif ($isServing)
                                        <div class="space-y-1">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-sky-500 text-slate-950 inline-block animate-pulse">
                                                Sedang Bertugas
                                            </span>
                                            <div class="text-xs text-sky-600 dark:text-sky-300 font-semibold font-mono">
                                                Antrian: {{ $inProgressBooking->queue_number }}
                                            </div>
                                        </div>
                                    @else
                                        <span class="px-3.5 py-1 rounded-full text-xs font-black bg-emerald-500 text-slate-950 shadow-sm">
                                            ✓ Aktif & Siap
                                        </span>
                                    @endif
                                </td>

                                <!-- Tasks Today -->
                                <td class="py-4 px-4">
                                    <div class="font-bold text-slate-900 dark:text-white">
                                        {{ $staff->bookings_today_completed }} <span class="text-xs font-normal text-slate-500 dark:text-slate-400">Selesai</span>
                                    </div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                                        {{ $staff->bookings_today_total }} total order hari ini
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-500 dark:text-slate-400">
                                    <div class="max-w-xs mx-auto space-y-2">
                                        <div class="text-3xl">👥</div>
                                        <p class="font-bold text-sm text-slate-700 dark:text-slate-300">Tidak ada data pekerja ditemukan.</p>
                                        <p class="text-xs">Silakan sesuaikan filter atau tambahkan data staf di menu admin.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-kasir-layout>

