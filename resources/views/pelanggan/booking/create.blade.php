<x-pelanggan-layout title="Form Booking - {{ ucfirst($category) }}">
    <div class="max-w-xl mx-auto py-4" x-data="bookingForm()">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider {{ $category === 'barbershop' ? 'text-amber-600 dark:text-amber-400' : 'text-pink-600 dark:text-pink-400' }}">
                    Langkah 2 dari 2 &bull; Layanan {{ ucfirst($category) }}
                </span>
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white mt-0.5">Form Pemesanan</h1>
            </div>
            <a href="{{ route('pelanggan.booking.category') }}" class="text-xs text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">
                Ganti Kategori
            </a>
        </div>

        <form method="POST" action="{{ route('pelanggan.booking.store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="category" value="{{ $category }}">

            <!-- 1. Pilih Layanan Spesifik -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 space-y-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $category === 'barbershop' ? 'bg-amber-500' : 'bg-pink-500' }}"></span>
                        1. Pilih Layanan
                    </h2>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Bisa pilih lebih dari 1</span>
                </div>

                <div class="space-y-2.5">
                    @forelse ($services as $service)
                        <label class="relative flex items-center justify-between p-3.5 rounded-xl border transition cursor-pointer"
                               :class="selectedServices.includes({{ $service->id }})
                                   ? 'bg-amber-500/10 border-amber-500/60 shadow-sm'
                                   : 'bg-slate-50 dark:bg-slate-800/40 border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700'">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="services[]" value="{{ $service->id }}"
                                       @change="toggleService({{ $service->id }}, {{ $service->price }}, {{ $service->duration_minutes }})"
                                       class="w-4 h-4 rounded bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-700 text-amber-500 focus:ring-amber-500 focus:ring-offset-white dark:focus:ring-offset-slate-900">
                                <div>
                                    <p class="font-bold text-slate-900 dark:text-white text-sm">{{ $service->name }}</p>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ $service->description }}</p>
                                    <span class="inline-block text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                                        ⏱ {{ $service->duration_minutes }} menit
                                    </span>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0 ms-3">
                                <span class="text-sm font-extrabold {{ $category === 'barbershop' ? 'text-amber-600 dark:text-amber-400' : 'text-pink-600 dark:text-pink-400' }}">
                                    {{ $service->formatted_price }}
                                </span>
                            </div>
                        </label>
                    @empty
                        <p class="text-sm text-slate-500 text-center py-4">Belum ada layanan aktif pada kategori ini.</p>
                    @endforelse
                </div>
                @error('services')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- 2. Pilih Tanggal & Waktu -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 space-y-4 shadow-sm">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full {{ $category === 'barbershop' ? 'bg-amber-500' : 'bg-pink-500' }}"></span>
                    2. Tanggal & Jam Reservasi
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Date Picker -->
                    <div>
                        <label for="booking_date" class="block text-xs font-semibold text-slate-700 dark:text-slate-400 mb-1">Pilih Tanggal</label>
                        <select id="booking_date" name="booking_date" required
                                class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                            @foreach ($availableDates as $d)
                                <option value="{{ $d['date'] }}" {{ old('booking_date') == $d['date'] ? 'selected' : '' }}>
                                    {{ $d['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('booking_date')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Time Slot Picker -->
                    <div>
                        <label for="booking_time" class="block text-xs font-semibold text-slate-700 dark:text-slate-400 mb-1">Pilih Jam</label>
                        <select id="booking_time" name="booking_time" required
                                class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <option value="">-- Pilih Jam --</option>
                            @php
                                $slots = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                            @endphp
                            @foreach ($slots as $slot)
                                <option value="{{ $slot }}" {{ old('booking_time') == $slot ? 'selected' : '' }}>
                                    {{ $slot }} WIB
                                </option>
                            @endforeach
                        </select>
                        @error('booking_time')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 3. Keterangan Tambahan (Opsional) -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 space-y-4 shadow-sm">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full {{ $category === 'barbershop' ? 'bg-amber-500' : 'bg-pink-500' }}"></span>
                    3. Catatan Khusus (Opsional)
                </h2>

                <div>
                    <textarea name="notes" rows="3"
                              class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500"
                              placeholder="Contoh: Request Kapster Andi, request MUA Citra, kondisi kulit sensitif, atau referensi gaya potongan fade tipis...">{{ old('notes') }}</textarea>
                    <p class="text-[11px] text-slate-500 mt-1">Sampaikan preferensi kapster, MUA, atau catatan khusus lainnya.</p>
                </div>
            </div>

            <!-- 4. Ringkasan & Submit -->
            <div class="bg-slate-100 dark:bg-gradient-to-br dark:from-slate-900 dark:to-slate-800 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm dark:shadow-xl space-y-4">
                <h3 class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Ringkasan Estimasi Biaya</h3>

                <div class="flex items-center justify-between text-sm py-1 border-b border-slate-200 dark:border-slate-800">
                    <span class="text-slate-600 dark:text-slate-400">Total Durasi Estimasi</span>
                    <span class="font-semibold text-slate-900 dark:text-white" x-text="totalDuration + ' Menit'">0 Menit</span>
                </div>

                <div class="flex items-center justify-between text-base py-1">
                    <span class="font-bold text-slate-900 dark:text-white">Total Biaya Layanan</span>
                    <span class="text-xl font-extrabold text-amber-600 dark:text-amber-400" x-text="formatRupiah(totalPrice)">Rp 0</span>
                </div>

                <button type="submit"
                        :disabled="selectedServices.length === 0"
                        class="w-full py-3.5 px-4 bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 disabled:opacity-50 disabled:cursor-not-allowed text-slate-950 font-extrabold rounded-xl shadow-lg shadow-amber-500/20 transition transform active:scale-95 text-base flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Buat Pesanan & Ambil Antrian</span>
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function bookingForm() {
            return {
                selectedServices: [],
                totalPrice: 0,
                totalDuration: 0,
                toggleService(id, price, duration) {
                    const idx = this.selectedServices.indexOf(id);
                    if (idx > -1) {
                        this.selectedServices.splice(idx, 1);
                        this.totalPrice -= price;
                        this.totalDuration -= duration;
                    } else {
                        this.selectedServices.push(id);
                        this.totalPrice += price;
                        this.totalDuration += duration;
                    }
                },
                formatRupiah(amount) {
                    return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }
            }
        }
    </script>
    @endpush
</x-pelanggan-layout>
