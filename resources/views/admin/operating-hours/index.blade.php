<x-admin-layout title="Jadwal Operasional - Grooming Lab & MUA">
    <div class="space-y-6 max-w-4xl mx-auto py-2">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-white">Jadwal & Jam Operasional</h1>
                <p class="text-xs text-slate-400 mt-1">Atur jam buka-tutup, kuota booking per slot waktu, dan hari libur toko</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.operating-hours.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="bg-slate-950/60 text-xs uppercase tracking-wider text-slate-400 border-b border-slate-800">
                            <tr>
                                <th class="py-3.5 px-5">Hari</th>
                                <th class="py-3.5 px-4">Jam Buka</th>
                                <th class="py-3.5 px-4">Jam Tutup</th>
                                <th class="py-3.5 px-4 text-center">Tutup / Libur</th>
                                <th class="py-3.5 px-5 text-center">Batas Kuota / Jam</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80">
                            @foreach ($operatingHours as $index => $hour)
                                <input type="hidden" name="hours[{{ $index }}][id]" value="{{ $hour->id }}">
                                <tr class="hover:bg-slate-800/40 transition">
                                    <td class="py-3.5 px-5 font-bold text-white">
                                        {{ $hour->getDayName() }}
                                    </td>

                                    <td class="py-3.5 px-4">
                                        <input type="time" name="hours[{{ $index }}][open_time]" value="{{ substr($hour->open_time, 0, 5) }}" required
                                            class="px-3 py-1.5 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs focus:ring-emerald-500">
                                    </td>

                                    <td class="py-3.5 px-4">
                                        <input type="time" name="hours[{{ $index }}][close_time]" value="{{ substr($hour->close_time, 0, 5) }}" required
                                            class="px-3 py-1.5 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs focus:ring-emerald-500">
                                    </td>

                                    <td class="py-3.5 px-4 text-center">
                                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                            <input type="checkbox" name="hours[{{ $index }}][is_closed]" value="1" {{ $hour->is_closed ? 'checked' : '' }}
                                                class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-red-500 focus:ring-red-500">
                                            <span class="text-xs {{ $hour->is_closed ? 'text-red-400 font-bold' : 'text-slate-400' }}">Tutup</span>
                                        </label>
                                    </td>

                                    <td class="py-3.5 px-5 text-center">
                                        <input type="number" name="hours[{{ $index }}][max_bookings_per_slot]" value="{{ $hour->max_bookings_per_slot }}" min="1" max="50" required
                                            class="w-20 text-center px-3 py-1.5 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs font-mono font-bold focus:ring-emerald-500">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-5 bg-slate-950/40 border-t border-slate-800 flex items-center justify-between">
                    <p class="text-xs text-slate-400">
                        * Slot booking pada hari yang ditandai Tutup tidak akan dapat dipilih oleh pelanggan saat reservasi.
                    </p>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg transition">
                        Simpan Perubahan Jadwal
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>

