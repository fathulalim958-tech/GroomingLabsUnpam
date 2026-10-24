<x-admin-layout title="Jadwal Operasional - Grooming Lab & MUA">
    <div class="space-y-6 max-w-4xl mx-auto py-2">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Jadwal & Jam Operasional</h1>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Atur jam buka-tutup, kuota booking per slot waktu, dan hari libur toko</p>
            </div>
        </div>

        <!-- Tabs Pemisah Barbershop & MUA -->
        <div class="flex items-center gap-3 border-b border-slate-200 dark:border-slate-800 pb-3">
            <a href="{{ route('admin.operating-hours.index', ['category' => 'barbershop']) }}"
               class="flex items-center gap-2 px-5 py-2.5 rounded-2xl font-bold text-xs transition {{ $category === 'barbershop' ? 'bg-emerald-500 text-slate-950 shadow-md font-extrabold' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white border border-slate-200 dark:border-slate-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"/>
                </svg>
                <span>Layanan Barbershop</span>
                <span class="px-2 py-0.5 text-[10px] rounded-full {{ $category === 'barbershop' ? 'bg-slate-950/15 text-slate-950 font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-500' }}">
                    Kuota / Jam Lebih Banyak
                </span>
            </a>

            <a href="{{ route('admin.operating-hours.index', ['category' => 'mua']) }}"
               class="flex items-center gap-2 px-5 py-2.5 rounded-2xl font-bold text-xs transition {{ $category === 'mua' ? 'bg-emerald-500 text-slate-950 shadow-md font-extrabold' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white border border-slate-200 dark:border-slate-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                <span>Layanan MUA Studio</span>
                <span class="px-2 py-0.5 text-[10px] rounded-full {{ $category === 'mua' ? 'bg-slate-950/15 text-slate-950 font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-500' }}">
                    Slot Terbatas
                </span>
            </a>
        </div>

        <form method="POST" action="{{ route('admin.operating-hours.update') }}" class="space-y-6">
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
                                <th class="py-3.5 px-4 text-center">Tutup / Libur</th>
                                <th class="py-3.5 px-5 text-center">Batas Kuota / Jam</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                            @foreach ($operatingHours as $index => $hour)
                                <input type="hidden" name="hours[{{ $index }}][id]" value="{{ $hour->id }}">
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                    <td class="py-3.5 px-5 font-bold text-slate-900 dark:text-white">
                                        {{ $hour->getDayName() }}
                                    </td>

                                    <td class="py-3.5 px-4">
                                        <input type="time" name="hours[{{ $index }}][open_time]" value="{{ substr($hour->open_time, 0, 5) }}" required
                                            class="px-3 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs focus:ring-emerald-500">
                                    </td>

                                    <td class="py-3.5 px-4">
                                        <input type="time" name="hours[{{ $index }}][close_time]" value="{{ substr($hour->close_time, 0, 5) }}" required
                                            class="px-3 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs focus:ring-emerald-500">
                                    </td>

                                    <td class="py-3.5 px-4 text-center">
                                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                            <input type="checkbox" name="hours[{{ $index }}][is_closed]" value="1" {{ $hour->is_closed ? 'checked' : '' }}
                                                class="w-4 h-4 rounded bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-700 text-red-500 focus:ring-red-500">
                                            <span class="text-xs {{ $hour->is_closed ? 'text-red-500 font-bold' : 'text-slate-500 dark:text-slate-400' }}">Tutup</span>
                                        </label>
                                    </td>

                                    <td class="py-3.5 px-5 text-center">
                                        <input type="number" name="hours[{{ $index }}][max_bookings_per_slot]" value="{{ $hour->max_bookings_per_slot }}" min="1" max="50" required
                                            class="w-20 text-center px-3 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs font-mono font-bold focus:ring-emerald-500">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-5 bg-slate-50 dark:bg-slate-950/40 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        * Slot booking pada hari yang ditandai Tutup tidak akan dapat dipilih oleh pelanggan saat reservasi {{ $category === 'mua' ? 'layanan MUA' : 'layanan Barbershop' }}.
                    </p>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg transition">
                        Simpan Perubahan Jadwal {{ $category === 'mua' ? 'MUA' : 'Barbershop' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>
