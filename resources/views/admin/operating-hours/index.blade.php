<x-admin-layout title="Jadwal Operasional & Kalender - Grooming Lab & MUA">
    <div class="space-y-8 max-w-5xl mx-auto py-2" x-data="operatingCalendar()">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Jadwal Operasional &amp; Kalender</h1>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Atur jam buka-tutup mingguan serta kalender terintegrasi hari libur nasional (tanggal merah)</p>
            </div>
            <!-- Tanggal Merah Indicator Pill -->
            <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs font-bold">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                <span>Terintegrasi Tanggal Merah Otomatis</span>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- BAGIAN 1: JAM OPERASIONAL MINGGUAN & KUOTA PER SLOT -->
        <!-- ============================================================ -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>1. Jam Buka-Tutup &amp; Kuota Slot Mingguan</span>
                </h2>

                <!-- Tabs Pemisah Barbershop & MUA -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.operating-hours.index', ['category' => 'barbershop', 'month' => $selectedMonth, 'year' => $selectedYear]) }}"
                       class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl font-bold text-xs transition {{ $category === 'barbershop' ? 'bg-emerald-500 text-slate-950 shadow-md font-extrabold' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white border border-slate-200 dark:border-slate-800' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"/>
                        </svg>
                        <span>Layanan Barbershop</span>
                    </a>

                    <a href="{{ route('admin.operating-hours.index', ['category' => 'mua', 'month' => $selectedMonth, 'year' => $selectedYear]) }}"
                       class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl font-bold text-xs transition {{ $category === 'mua' ? 'bg-emerald-500 text-slate-950 shadow-md font-extrabold' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white border border-slate-200 dark:border-slate-800' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                        <span>Layanan MUA Studio</span>
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.operating-hours.update') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="category" value="{{ $category }}">

                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm dark:shadow-xl">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                            <thead class="bg-slate-50 dark:bg-slate-950/60 text-xs uppercase tracking-wider text-slate-600 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                                <tr>
                                    <th class="py-3.5 px-5">Hari</th>
                                    <th class="py-3.5 px-4">Jam Buka</th>
                                    <th class="py-3.5 px-4">Jam Tutup</th>
                                    <th class="py-3.5 px-4 text-center">Tutup Mingguan</th>
                                    <th class="py-3.5 px-5 text-center">Batas Kuota / Jam</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                                @foreach ($operatingHours as $index => $hour)
                                    <input type="hidden" name="hours[{{ $index }}][id]" value="{{ $hour->id }}">
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                        <td class="py-3 px-5 font-bold text-slate-900 dark:text-white">
                                            {{ $hour->getDayName() }}
                                        </td>

                                        <td class="py-3 px-4">
                                            <input type="time" name="hours[{{ $index }}][open_time]" value="{{ substr($hour->open_time, 0, 5) }}" required
                                                class="px-3 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs focus:ring-emerald-500">
                                        </td>

                                        <td class="py-3 px-4">
                                            <input type="time" name="hours[{{ $index }}][close_time]" value="{{ substr($hour->close_time, 0, 5) }}" required
                                                class="px-3 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs focus:ring-emerald-500">
                                        </td>

                                        <td class="py-3 px-4 text-center">
                                            <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                                <input type="checkbox" name="hours[{{ $index }}][is_closed]" value="1" {{ $hour->is_closed ? 'checked' : '' }}
                                                    class="w-4 h-4 rounded bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-700 text-red-500 focus:ring-red-500">
                                                <span class="text-xs {{ $hour->is_closed ? 'text-red-500 font-bold' : 'text-slate-500 dark:text-slate-400' }}">Tutup</span>
                                            </label>
                                        </td>

                                        <td class="py-3 px-5 text-center">
                                            <input type="number" name="hours[{{ $index }}][max_bookings_per_slot]" value="{{ $hour->max_bookings_per_slot }}" min="1" max="50" required
                                                class="w-20 text-center px-3 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs font-mono font-bold focus:ring-emerald-500">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 bg-slate-50 dark:bg-slate-950/40 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            * Pengaturan jam operasional mingguan berlaku otomatis untuk kategori <strong>{{ $category === 'mua' ? 'MUA Studio' : 'Barbershop' }}</strong>.
                        </p>
                        <button type="submit" class="px-5 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg transition">
                            Simpan Perubahan Jam Buka
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- ============================================================ -->
        <!-- BAGIAN 2: KALENDER OPERASIONAL TERINTEGRASI TANGGAL MERAH -->
        <!-- ============================================================ -->
        <div class="space-y-4 pt-4 border-t border-slate-200 dark:border-slate-800">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        <span>2. Kalender Terintegrasi Tanggal Merah &amp; Hari Libur</span>
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Setiap tanggal merah otomatis diliburkan pada sistem reservasi sehingga pelanggan tidak dapat booking di tanggal tersebut.
                    </p>
                </div>

                <!-- Navigation Bulan & Tambah Libur Khusus -->
                <div class="flex items-center gap-2">
                    <button type="button" @click="showAddHolidayModal = true"
                        class="px-3.5 py-1.5 bg-rose-500 hover:bg-rose-400 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>+ Tambah Libur Khusus</span>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Kalender Grid (8 Kolom) -->
                <div class="lg:col-span-8 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-sm dark:shadow-xl">
                    <!-- Calendar Header (Month Switcher) -->
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-black text-slate-900 dark:text-white">
                                {{ $calendarDate->translatedFormat('F Y') }}
                            </span>
                            @if (count($holidaysThisMonth) > 0)
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                    {{ count($holidaysThisMonth) }} Tanggal Merah
                                </span>
                            @endif
                        </div>

                        <!-- Prev / Next Month Buttons -->
                        <div class="flex items-center gap-1">
                            @php
                                $prevMonth = $selectedMonth - 1;
                                $prevYear = $selectedYear;
                                if ($prevMonth < 1) { $prevMonth = 12; $prevYear--; }

                                $nextMonth = $selectedMonth + 1;
                                $nextYear = $selectedYear;
                                if ($nextMonth > 12) { $nextMonth = 1; $nextYear++; }
                            @endphp
                            <a href="{{ route('admin.operating-hours.index', ['category' => $category, 'month' => $prevMonth, 'year' => $prevYear]) }}"
                               class="p-2 rounded-xl border border-slate-200 dark:border-slate-750 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                               title="Bulan Sebelumnya">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                            <a href="{{ route('admin.operating-hours.index', ['category' => $category, 'month' => Carbon\Carbon::now('Asia/Jakarta')->month, 'year' => Carbon\Carbon::now('Asia/Jakarta')->year]) }}"
                               class="px-2.5 py-1.5 rounded-xl border border-slate-200 dark:border-slate-750 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                                Hari Ini
                            </a>
                            <a href="{{ route('admin.operating-hours.index', ['category' => $category, 'month' => $nextMonth, 'year' => $nextYear]) }}"
                               class="p-2 rounded-xl border border-slate-200 dark:border-slate-750 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                               title="Bulan Selanjutnya">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Days of Week Header -->
                    <div class="grid grid-cols-7 gap-1.5 mb-2 text-center text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        <div class="py-1">Sen</div>
                        <div class="py-1">Sel</div>
                        <div class="py-1">Rab</div>
                        <div class="py-1">Kam</div>
                        <div class="py-1">Jum</div>
                        <div class="py-1">Sab</div>
                        <div class="py-1 text-rose-600 dark:text-rose-400">Min</div>
                    </div>

                    <!-- Calendar Days Grid -->
                    <div class="grid grid-cols-7 gap-1.5">
                        <!-- Empty slots for days before start of month -->
                        @for ($i = 0; $i < $startDayOffset; $i++)
                            <div class="h-16 rounded-xl border border-transparent bg-slate-50/40 dark:bg-slate-950/20 opacity-30"></div>
                        @endfor

                        <!-- Days of Month -->
                        @foreach ($days as $d)
                            <div class="h-16 p-1.5 rounded-xl border flex flex-col justify-between transition relative group {{ $d['is_red_date'] ? 'bg-rose-50/70 dark:bg-rose-950/30 border-rose-200 dark:border-rose-900/40 text-rose-700 dark:text-rose-400' : 'bg-slate-50/80 dark:bg-slate-800/50 border-slate-200 dark:border-slate-750 text-slate-700 dark:text-slate-300' }} {{ $d['is_today'] ? 'ring-2 ring-emerald-500 shadow-sm' : '' }}">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-extrabold {{ $d['is_red_date'] ? 'text-rose-700 dark:text-rose-300' : 'text-slate-900 dark:text-white' }}">
                                        {{ $d['day'] }}
                                    </span>
                                    @if ($d['is_today'])
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500" title="Hari Ini"></span>
                                    @elseif ($d['is_holiday'])
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500" title="Tanggal Merah"></span>
                                    @endif
                                </div>

                                <div class="mt-auto">
                                    @if ($d['is_holiday'])
                                        <span class="block text-[9px] font-bold leading-tight truncate text-rose-700 dark:text-rose-300" title="{{ $d['holiday_name'] }}">
                                            🔴 {{ $d['holiday_name'] }}
                                        </span>
                                    @elseif ($d['is_sunday'])
                                        <span class="block text-[9px] font-semibold text-rose-600/80 dark:text-rose-400">
                                            Libur Mingguan
                                        </span>
                                    @else
                                        <span class="block text-[9px] text-slate-500 dark:text-slate-400">
                                            Buka Operasional
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Calendar Legend -->
                    <div class="flex flex-wrap items-center gap-4 mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 text-xs text-slate-600 dark:text-slate-400">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded bg-rose-500/20 border border-rose-500/40"></span>
                            <span>Tanggal Merah / Libur (Otomatis Tutup)</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700"></span>
                            <span>Hari Kerja Normal (Buka)</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded border-2 border-emerald-500"></span>
                            <span>Hari Ini</span>
                        </div>
                    </div>
                </div>

                <!-- Daftar Tanggal Merah Bulan Ini & Info (4 Kolom) -->
                <div class="lg:col-span-4 space-y-4">
                    <!-- Card Daftar Libur Bulan Ini -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-sm dark:shadow-xl space-y-3">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                                <span>📅</span>
                                <span>Tanggal Merah {{ $calendarDate->translatedFormat('F Y') }}</span>
                            </h3>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400">
                                {{ count($holidaysThisMonth) }} Hari
                            </span>
                        </div>

                        <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
                            @forelse ($holidaysThisMonth as $dateStr => $h)
                                @php
                                    $hDate = Carbon\Carbon::parse($dateStr);
                                @endphp
                                <div class="p-2.5 rounded-xl bg-rose-50/70 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/30 flex items-start justify-between gap-2">
                                    <div>
                                        <div class="text-xs font-bold text-rose-700 dark:text-rose-300">
                                            {{ $hDate->translatedFormat('d F Y') }} ({{ $hDate->translatedFormat('l') }})
                                        </div>
                                        <div class="text-[11px] text-slate-700 dark:text-slate-300 font-medium mt-0.5">
                                            {{ $h['name'] }}
                                        </div>
                                        @if (!empty($h['description']))
                                            <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">
                                                {{ $h['description'] }}
                                            </div>
                                        @endif
                                    </div>
                                    @if (!empty($h['id']))
                                        <!-- Jika custom holiday, sediakan tombol hapus -->
                                        <form method="POST" action="{{ route('admin.operating-hours.holidays.destroy', $h['id']) }}" class="flex-shrink-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Hapus hari libur khusus ini dari kalender?')"
                                                class="text-rose-500 hover:text-rose-700 p-1" title="Hapus Libur Khusus">
                                                ✕
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @empty
                                <div class="py-6 text-center text-xs text-slate-500">
                                    Tidak ada tanggal merah nasional pada bulan {{ $calendarDate->translatedFormat('F Y') }}.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Info Box Otomasi Tanggal Merah -->
                    <div class="p-4 rounded-2xl bg-blue-50/70 dark:bg-sky-950/30 border border-blue-200 dark:border-sky-900/40 text-xs text-slate-700 dark:text-slate-300 space-y-1.5">
                        <div class="font-bold text-[#1769AA] dark:text-sky-400 flex items-center gap-1.5">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Otomasi Sistem Booking</span>
                        </div>
                        <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-relaxed">
                            Sistem secara otomatis mengunci tanggal merah sesuai SKB 3 Menteri. Pelanggan yang mengakses halaman booking tidak akan dapat memilih jam ataupun reservasi pada tanggal-tanggal tersebut.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- MODAL: TAMBAH LIBUR KHUSUS TOKO -->
        <!-- ============================================================ -->
        <div x-show="showAddHolidayModal"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-2xl border border-slate-200 dark:border-slate-800"
                 @click.outside="showAddHolidayModal = false">
                <form action="{{ route('admin.operating-hours.holidays.store') }}" method="POST">
                    @csrf
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Tambah Libur Khusus Toko</h3>
                        <button type="button" @click="showAddHolidayModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            ✕
                        </button>
                    </div>

                    <div class="py-4 space-y-3.5">
                        <div>
                            <label for="holiday_date" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Tanggal Libur *
                            </label>
                            <input id="holiday_date" type="date" name="date" required
                                value="{{ \Carbon\Carbon::now('Asia/Jakarta')->toDateString() }}"
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs focus:ring-rose-500">
                        </div>

                        <div>
                            <label for="holiday_name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Nama / Alasan Libur *
                            </label>
                            <input id="holiday_name" type="text" name="name" required
                                placeholder="Contoh: Renovasi Toko / Libur Bersama Karyawan"
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs focus:ring-rose-500">
                        </div>

                        <div>
                            <label for="holiday_description" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Keterangan Tambahan (Opsional)
                            </label>
                            <input id="holiday_description" type="text" name="description"
                                placeholder="Contoh: Buka kembali besok pukul 09:00 WIB"
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs focus:ring-rose-500">
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-2">
                        <button type="button" @click="showAddHolidayModal = false"
                            class="px-4 py-2 text-xs font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-xl">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs rounded-xl shadow-lg transition">
                            Simpan ke Kalender
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function operatingCalendar() {
            return {
                showAddHolidayModal: false,
            };
        }
    </script>
    @endpush
</x-admin-layout>
