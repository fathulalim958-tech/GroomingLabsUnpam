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

        <form x-ref="bookingForm" method="POST" action="{{ route('pelanggan.booking.store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="category" value="{{ $category }}">

            <!-- 1. Pilih 1 Layanan Spesifik (Single Select) -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 space-y-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $category === 'barbershop' ? 'bg-amber-500' : 'bg-pink-500' }}"></span>
                        1. Pilih Layanan
                    </h2>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                        Pilih 1 Layanan
                    </span>
                </div>

                <div class="space-y-2.5">
                    @forelse ($services as $service)
                        <label class="relative flex items-center justify-between p-3.5 rounded-xl border transition cursor-pointer"
                               :class="selectedService && selectedService.id === {{ $service->id }}
                                   ? '{{ $category === 'barbershop' ? 'bg-amber-500/10 border-amber-500 shadow-md ring-1 ring-amber-500/30' : 'bg-pink-500/10 border-pink-500 shadow-md ring-1 ring-pink-500/30' }}'
                                   : 'bg-slate-50 dark:bg-slate-800/40 border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="services[]" value="{{ $service->id }}"
                                       @change="selectService({{ $service->id }}, '{{ addslashes($service->name) }}', {{ $service->price }}, {{ $service->duration_minutes }}, '{{ $service->formatted_price }}')"
                                       :checked="selectedService && selectedService.id === {{ $service->id }}"
                                       class="w-4 h-4 {{ $category === 'barbershop' ? 'text-amber-500 focus:ring-amber-500' : 'text-pink-500 focus:ring-pink-500' }} border-slate-300 dark:border-slate-700">
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
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 2. Pilih Tanggal & Jam Reservasi (Modern Card & Badge Grid) -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 space-y-5 shadow-sm">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full {{ $category === 'barbershop' ? 'bg-amber-500' : 'bg-pink-500' }}"></span>
                    2. Tanggal & Jam Reservasi
                </h2>

                <!-- Modern Date Picker Chips -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                        Pilih Hari & Tanggal
                    </label>
                    <input type="hidden" name="booking_date" :value="selectedDate">

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                        @foreach ($availableDates as $d)
                            @php
                                $cDate = \Carbon\Carbon::parse($d['date']);
                            @endphp
                            <button type="button"
                                    @click="setDate('{{ $d['date'] }}', '{{ $d['label'] }}', '{{ $d['open_time'] }}', '{{ $d['close_time'] }}')"
                                    class="p-3 rounded-xl border text-left transition flex flex-col justify-between"
                                    :class="selectedDate === '{{ $d['date'] }}'
                                        ? '{{ $category === 'barbershop' ? 'bg-amber-500/10 border-amber-500 text-slate-950 dark:text-white shadow-sm ring-1 ring-amber-500/40' : 'bg-pink-500/10 border-pink-500 text-slate-950 dark:text-white shadow-sm ring-1 ring-pink-500/40' }}'
                                        : 'bg-slate-50 dark:bg-slate-800/40 border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:border-slate-300 dark:hover:border-slate-700'">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-semibold uppercase">{{ $cDate->translatedFormat('l') }}</span>
                                    <span class="w-2 h-2 rounded-full"
                                          :class="selectedDate === '{{ $d['date'] }}' ? '{{ $category === 'barbershop' ? 'bg-amber-500' : 'bg-pink-500' }}' : 'bg-transparent'"></span>
                                </div>
                                <div class="mt-1">
                                    <span class="text-base font-extrabold text-slate-900 dark:text-white">{{ $cDate->format('d') }}</span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ $cDate->translatedFormat('M Y') }}</span>
                                </div>
                            </button>
                        @endforeach
                    </div>
                    @error('booking_date')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Modern Time Slot Chips with 1-hour interval labels -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            Pilih Sesi Jam (Rentang 1 Jam)
                        </label>
                        <span class="text-[11px] text-slate-500 dark:text-slate-400" x-show="selectedTime">
                            Dipilih: <strong class="{{ $category === 'barbershop' ? 'text-amber-600 dark:text-amber-400' : 'text-pink-600 dark:text-pink-400' }}" x-text="selectedTimeLabel"></strong>
                        </span>
                    </div>

                    <input type="hidden" name="booking_time" :value="selectedTime">

                    @php
                        $slots = [
                            ['value' => '09:00', 'label' => '09.00 - 10.00'],
                            ['value' => '10:00', 'label' => '10.00 - 11.00'],
                            ['value' => '11:00', 'label' => '11.00 - 12.00'],
                            ['value' => '12:00', 'label' => '12.00 - 13.00'],
                            ['value' => '13:00', 'label' => '13.00 - 14.00'],
                            ['value' => '14:00', 'label' => '14.00 - 15.00'],
                            ['value' => '15:00', 'label' => '15.00 - 16.00'],
                            ['value' => '16:00', 'label' => '16.00 - 17.00'],
                            ['value' => '17:00', 'label' => '17.00 - 18.00'],
                            ['value' => '18:00', 'label' => '18.00 - 19.00'],
                            ['value' => '19:00', 'label' => '19.00 - 20.00'],
                            ['value' => '20:00', 'label' => '20.00 - 21.00'],
                        ];
                    @endphp

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                        @foreach ($slots as $slot)
                            <button type="button"
                                    :disabled="!isSlotAvailable('{{ $slot['value'] }}')"
                                    @click="isSlotAvailable('{{ $slot['value'] }}') && setTime('{{ $slot['value'] }}', '{{ $slot['label'] }}')"
                                    class="py-2.5 px-3 rounded-xl border text-center transition font-mono text-xs font-bold flex items-center justify-center gap-1.5"
                                    :class="!isSlotAvailable('{{ $slot['value'] }}')
                                        ? 'opacity-40 cursor-not-allowed bg-slate-100 dark:bg-slate-900/60 border-dashed border-slate-300 dark:border-slate-800 text-slate-400'
                                        : (selectedTime === '{{ $slot['value'] }}'
                                            ? '{{ $category === 'barbershop' ? 'bg-amber-500 text-slate-950 font-black border-amber-500 shadow-md ring-2 ring-amber-500/20' : 'bg-pink-500 text-white font-black border-pink-500 shadow-md ring-2 ring-pink-500/20' }}'
                                            : 'bg-slate-50 dark:bg-slate-800/40 border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:border-slate-300 dark:hover:border-slate-700')">
                                <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $slot['label'] }}</span>
                            </button>
                        @endforeach
                    </div>
                    @error('booking_time')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- 3. Keterangan Tambahan (Opsional) -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 space-y-4 shadow-sm">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full {{ $category === 'barbershop' ? 'bg-amber-500' : 'bg-pink-500' }}"></span>
                    3. Catatan Khusus (Opsional)
                </h2>

                <div>
                    <textarea name="notes" x-model="notes" rows="3"
                              class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500"
                              placeholder="Contoh: Request Kapster Andi, request MUA Citra, kondisi kulit sensitif, atau referensi gaya potongan fade tipis...">{{ old('notes') }}</textarea>
                    <p class="text-[11px] text-slate-500 mt-1">Sampaikan preferensi kapster, MUA, atau catatan khusus lainnya.</p>
                </div>
            </div>

            <!-- 4. Ringkasan & Tombol Pembuka Modal Konfirmasi -->
            <div class="bg-slate-100 dark:bg-gradient-to-br dark:from-slate-900 dark:to-slate-800 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm dark:shadow-xl space-y-4">
                <h3 class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Ringkasan Estimasi Biaya</h3>

                <div class="flex items-center justify-between text-sm py-1 border-b border-slate-200 dark:border-slate-800">
                    <span class="text-slate-600 dark:text-slate-400">Total Durasi Estimasi</span>
                    <span class="font-semibold text-slate-900 dark:text-white" x-text="(selectedService ? selectedService.duration : 0) + ' Menit'">0 Menit</span>
                </div>

                <!-- Detail Biaya Layanan & Diskon Member -->
                <template x-if="hasMemberDiscount && selectedService">
                    <div class="space-y-1.5 py-1 border-b border-slate-200 dark:border-slate-800 text-xs">
                        <div class="flex items-center justify-between text-slate-600 dark:text-slate-400">
                            <span>Harga Normal</span>
                            <span class="font-mono line-through" x-text="formatRupiah(selectedService.price)"></span>
                        </div>
                        <div class="flex items-center justify-between font-semibold text-amber-600 dark:text-amber-400">
                            <span class="flex items-center gap-1">
                                <span>⭐ Potongan Member</span>
                                <span class="text-[10px] bg-amber-500/20 px-1.5 py-0.2 rounded" x-text="'(Sisa: ' + memberDiscountRemaining + 'x)'"></span>
                            </span>
                            <span class="font-mono" x-text="'- ' + formatRupiah(getDiscountAmount())"></span>
                        </div>
                    </div>
                </template>

                <div class="flex items-center justify-between text-base py-1">
                    <span class="font-bold text-slate-900 dark:text-white">Total Biaya Layanan</span>
                    <span class="text-xl font-extrabold {{ $category === 'barbershop' ? 'text-amber-600 dark:text-amber-400' : 'text-pink-600 dark:text-pink-400' }}"
                          x-text="formatRupiah(getFinalPrice())">Rp 0</span>
                </div>

                <button type="button"
                        @click="openConfirmModal()"
                        :disabled="!selectedService || !selectedDate || !selectedTime"
                        class="w-full py-3.5 px-4 {{ $category === 'barbershop' ? 'bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-slate-950 shadow-amber-500/20' : 'bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white shadow-pink-500/20' }} disabled:opacity-50 disabled:cursor-not-allowed font-extrabold rounded-xl shadow-lg transition transform active:scale-95 text-base flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Buat Pesanan & Ambil Antrian</span>
                </button>
            </div>
        </form>

        <!-- 5. Pop-up Modal Konfirmasi Hasil Booking (FR-CUST-CONFIRM) -->
        <div x-show="showConfirmModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4"
             style="display: none;">
            
            <div @click.away="showConfirmModal = false"
                 class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-5">
                
                <!-- Modal Header -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl {{ $category === 'barbershop' ? 'bg-amber-500/10 text-amber-500' : 'bg-pink-500/10 text-pink-500' }} flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white">Konfirmasi Pemesanan</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Pastikan rincian reservasi Anda sudah sesuai</p>
                    </div>
                </div>

                <!-- Summary Details Table -->
                <div class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 divide-y divide-slate-200 dark:divide-slate-800 text-xs space-y-2.5">
                    <div class="flex justify-between items-center pt-1">
                        <span class="text-slate-500 dark:text-slate-400">Kategori Layanan</span>
                        <span class="font-bold uppercase tracking-wider {{ $category === 'barbershop' ? 'text-amber-600 dark:text-amber-400' : 'text-pink-600 dark:text-pink-400' }}">
                            {{ ucfirst($category) }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center pt-2.5">
                        <span class="text-slate-500 dark:text-slate-400">Layanan Dipilih</span>
                        <span class="font-bold text-slate-900 dark:text-white text-right" x-text="selectedService ? selectedService.name : '-'"></span>
                    </div>

                    <div class="flex justify-between items-center pt-2.5">
                        <span class="text-slate-500 dark:text-slate-400">Estimasi Durasi</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-200" x-text="selectedService ? selectedService.duration + ' Menit' : '-'"></span>
                    </div>

                    <div class="flex justify-between items-center pt-2.5">
                        <span class="text-slate-500 dark:text-slate-400">Tanggal Reservasi</span>
                        <span class="font-bold text-slate-900 dark:text-white" x-text="selectedDateLabel"></span>
                    </div>

                    <div class="flex justify-between items-center pt-2.5">
                        <span class="text-slate-500 dark:text-slate-400">Sesi Waktu</span>
                        <span class="font-mono font-bold text-slate-900 dark:text-white" x-text="selectedTimeLabel + ' WIB'"></span>
                    </div>

                    <div class="flex justify-between items-start pt-2.5" x-show="notes.trim().length > 0">
                        <span class="text-slate-500 dark:text-slate-400">Catatan Khusus</span>
                        <span class="font-medium text-slate-700 dark:text-slate-300 text-right max-w-[60%]" x-text="notes"></span>
                    </div>

                    <!-- Detail Biaya Layanan & Diskon Member Modal -->
                    <template x-if="hasMemberDiscount && selectedService">
                        <div class="space-y-1.5 pt-2.5 border-t border-slate-200 dark:border-slate-800 text-xs">
                            <div class="flex justify-between items-center text-slate-500">
                                <span>Tarif Normal</span>
                                <span class="font-mono line-through" x-text="formatRupiah(selectedService.price)"></span>
                            </div>
                            <div class="flex justify-between items-center text-amber-600 dark:text-amber-400 font-semibold">
                                <span>⭐ Potongan Member</span>
                                <span class="font-mono" x-text="'- ' + formatRupiah(getDiscountAmount())"></span>
                            </div>
                        </div>
                    </template>

                    <div class="flex justify-between items-center pt-3 text-sm">
                        <span class="font-bold text-slate-900 dark:text-white">Total Tagihan</span>
                        <span class="font-black text-base {{ $category === 'barbershop' ? 'text-amber-600 dark:text-amber-400' : 'text-pink-600 dark:text-pink-400' }}"
                              x-text="formatRupiah(getFinalPrice())"></span>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button type="button"
                            @click="showConfirmModal = false"
                            class="w-full py-2.5 px-4 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-300 dark:border-slate-700 transition">
                        Periksa Kembali
                    </button>

                    <button type="button"
                            @click="submitBooking()"
                            class="w-full py-2.5 px-4 {{ $category === 'barbershop' ? 'bg-emerald-500 hover:bg-emerald-400 text-slate-950' : 'bg-pink-600 hover:bg-pink-500 text-white' }} font-bold text-xs rounded-xl shadow-lg transition">
                        Konfirmasi & Ambil Antrian
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function bookingForm() {
            return {
                selectedService: null,
                selectedDate: '{{ $availableDates[0]['date'] ?? '' }}',
                selectedDateLabel: '{{ $availableDates[0]['label'] ?? '' }}',
                currentOpenTime: '{{ $availableDates[0]['open_time'] ?? '09:00' }}',
                currentCloseTime: '{{ $availableDates[0]['close_time'] ?? '21:00' }}',
                selectedTime: '',
                selectedTimeLabel: '',
                notes: '{{ old('notes', '') }}',
                showConfirmModal: false,
                hasMemberDiscount: {{ auth()->user()->hasActiveMemberDiscount() ? 'true' : 'false' }},
                memberDiscount: {{ auth()->user()->member_discount ?: 0 }},
                memberDiscountType: '{{ auth()->user()->member_discount_type ?? 'percent' }}',
                memberDiscountRemaining: {{ auth()->user()->member_discount_remaining ?: 0 }},

                getDiscountAmount() {
                    if (!this.hasMemberDiscount || !this.selectedService) return 0;
                    const price = this.selectedService.price;
                    if (this.memberDiscountType === 'percent') {
                        return Math.min(price, Math.round((price * this.memberDiscount) / 100));
                    }
                    return Math.min(price, this.memberDiscount);
                },

                getFinalPrice() {
                    if (!this.selectedService) return 0;
                    return Math.max(0, this.selectedService.price - this.getDiscountAmount());
                },

                selectService(id, name, price, duration, formattedPrice) {
                    this.selectedService = { id, name, price, duration, formattedPrice };
                },

                setDate(date, label, openTime, closeTime) {
                    this.selectedDate = date;
                    this.selectedDateLabel = label;
                    if (openTime) this.currentOpenTime = openTime;
                    if (closeTime) this.currentCloseTime = closeTime;

                    if (this.selectedTime && !this.isSlotAvailable(this.selectedTime)) {
                        this.selectedTime = '';
                        this.selectedTimeLabel = '';
                    }
                },

                isSlotAvailable(slotValue) {
                    return slotValue >= this.currentOpenTime && slotValue < this.currentCloseTime;
                },

                setTime(time, label) {
                    this.selectedTime = time;
                    this.selectedTimeLabel = label;
                },

                openConfirmModal() {
                    if (!this.selectedService) {
                        alert('Silakan pilih 1 layanan terlebih dahulu.');
                        return;
                    }
                    if (!this.selectedDate) {
                        alert('Silakan pilih tanggal reservasi.');
                        return;
                    }
                    if (!this.selectedTime) {
                        alert('Silakan pilih sesi jam reservasi.');
                        return;
                    }
                    this.showConfirmModal = true;
                },

                submitBooking() {
                    this.$refs.bookingForm.submit();
                },

                formatRupiah(amount) {
                    return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }
            }
        }
    </script>
    @endpush
</x-pelanggan-layout>
