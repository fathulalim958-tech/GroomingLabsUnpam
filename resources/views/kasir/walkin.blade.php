<x-kasir-layout title="Order Walk-In - Kasir Grooming Lab & MUA">
    <div class="max-w-2xl mx-auto py-2 sm:py-4" x-data="walkinForm()">
        <div class="mb-4 sm:mb-6 flex items-center justify-between gap-2">
            <div>
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Kasir Front-Desk</span>
                <h1 class="text-lg sm:text-2xl font-extrabold text-slate-900 dark:text-white mt-0.5">Input Pesanan Walk-In (Offline)</h1>
                <p class="text-[11px] sm:text-xs text-slate-600 dark:text-slate-400 mt-0.5">Tambahkan antrian untuk pelanggan yang datang langsung</p>
            </div>
            <a href="{{ route('kasir.dashboard') }}" class="text-xs text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition flex-shrink-0">
                &larr; Dashboard
            </a>
        </div>

        <form x-ref="walkinForm" method="POST" action="{{ route('kasir.walkin.store') }}" class="space-y-4 sm:space-y-6">
            @csrf

            <!-- 1. Customer Information -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl sm:rounded-2xl p-4 sm:p-5 space-y-3.5 shadow-sm">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    1. Data Pelanggan Walk-In
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="customer_name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Pelanggan *</label>
                        <input id="customer_name" type="text" name="customer_name" x-model="customerName" required
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Contoh: Rian Pratama" />
                        @error('customer_name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="customer_phone" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">No. WhatsApp / Telepon * <span class="text-[10px] text-slate-400 font-normal">(Hanya angka)</span></label>
                        <input id="customer_phone" type="tel" name="customer_phone" x-model="customerPhone"
                            inputmode="numeric" pattern="[0-9]*"
                            @input="customerPhone = $event.target.value = $event.target.value.replace(/[^0-9]/g, '')"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            required
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono"
                            placeholder="Contoh: 081299887766" />
                        @error('customer_phone')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 2. Category Selection -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 space-y-4 shadow-sm">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    2. Kategori Layanan
                </h2>

                <div class="grid grid-cols-2 gap-4">
                    <label class="p-4 rounded-xl border-2 transition cursor-pointer flex items-center gap-3"
                           :class="category === 'barbershop' ? 'bg-amber-500/10 border-amber-500 text-slate-900 dark:text-white ring-1 ring-amber-500/30' : 'bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400'">
                        <input type="radio" name="category" value="barbershop" x-model="category" @change="resetServices()" class="text-amber-500 focus:ring-amber-500">
                        <div>
                            <span class="font-bold text-sm block">Barbershop</span>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400">Pria & Haircare</span>
                        </div>
                    </label>

                    <label class="p-4 rounded-xl border-2 transition cursor-pointer flex items-center gap-3"
                           :class="category === 'mua' ? 'bg-pink-500/10 border-pink-500 text-slate-900 dark:text-white ring-1 ring-pink-500/30' : 'bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400'">
                        <input type="radio" name="category" value="mua" x-model="category" @change="resetServices()" class="text-pink-500 focus:ring-pink-500">
                        <div>
                            <span class="font-bold text-sm block">MUA Studio</span>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400">Tata Rias & Makeup</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- 3. Service Selection (1 Pilihan Layanan) -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 space-y-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        3. Pilih Layanan
                    </h2>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                        Pilih 1 Layanan Saja
                    </span>
                </div>

                <!-- Barbershop Services List -->
                <div x-show="category === 'barbershop'" class="space-y-2">
                    @foreach ($barbershopServices as $s)
                        <label class="flex items-center justify-between p-3 rounded-xl border transition cursor-pointer"
                               :class="selectedService && selectedService.id === {{ $s->id }} ? 'bg-amber-500/10 border-amber-500 shadow-sm ring-1 ring-amber-500/30' : 'bg-slate-50 dark:bg-slate-800/40 border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="services[]" value="{{ $s->id }}"
                                       @change="selectService({{ $s->id }}, '{{ addslashes($s->name) }}', {{ $s->price }}, '{{ $s->formatted_price }}')"
                                       :checked="selectedService && selectedService.id === {{ $s->id }}"
                                       :disabled="category !== 'barbershop'"
                                       class="w-4 h-4 rounded-full bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-700 text-amber-500 focus:ring-amber-500">
                                <div>
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $s->name }}</span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 block">{{ $s->duration_minutes }} Menit</span>
                                </div>
                            </div>
                            <span class="text-sm font-extrabold text-amber-600 dark:text-amber-400">{{ $s->formatted_price }}</span>
                        </label>
                    @endforeach
                </div>

                <!-- MUA Services List -->
                <div x-show="category === 'mua'" class="space-y-2" style="display: none;">
                    @foreach ($muaServices as $s)
                        <label class="flex items-center justify-between p-3 rounded-xl border transition cursor-pointer"
                               :class="selectedService && selectedService.id === {{ $s->id }} ? 'bg-pink-500/10 border-pink-500 shadow-sm ring-1 ring-pink-500/30' : 'bg-slate-50 dark:bg-slate-800/40 border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="services[]" value="{{ $s->id }}"
                                       @change="selectService({{ $s->id }}, '{{ addslashes($s->name) }}', {{ $s->price }}, '{{ $s->formatted_price }}')"
                                       :checked="selectedService && selectedService.id === {{ $s->id }}"
                                       :disabled="category !== 'mua'"
                                       class="w-4 h-4 rounded-full bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-700 text-pink-500 focus:ring-pink-500">
                                <div>
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $s->name }}</span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 block">{{ $s->duration_minutes }} Menit</span>
                                </div>
                            </div>
                            <span class="text-sm font-extrabold text-pink-600 dark:text-pink-400">{{ $s->formatted_price }}</span>
                        </label>
                    @endforeach
                </div>

                @error('services')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 4. Notes & Summary -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 space-y-4 shadow-sm">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    4. Catatan Tambahan (Opsional)
                </h2>

                <textarea name="notes" x-model="notes" rows="2"
                          class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Catatan khusus, request kapster/MUA, dll...">{{ old('notes') }}</textarea>

                <div class="pt-3 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <span class="text-xs text-slate-500 dark:text-slate-400 block">Total Biaya Walk-In</span>
                        <span class="text-2xl font-black text-amber-600 dark:text-amber-400" x-text="formatRupiah(selectedService ? selectedService.price : 0)">Rp 0</span>
                    </div>

                    <button type="button"
                            @click="openConfirmModal()"
                            :disabled="!selectedService || !customerName.trim() || !customerPhone.trim()"
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-500 hover:to-cyan-500 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 transition transform active:scale-95 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Cetak Antrian & Simpan</span>
                    </button>
                </div>
            </div>
        </form>

        <!-- Pop-up Modal Konfirmasi Walk-in Kasir -->
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
                
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-500/10 text-blue-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white">Konfirmasi Antrian Walk-In</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Verifikasi pesanan offline sebelum mencetak tiket antrian</p>
                    </div>
                </div>

                <div class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 divide-y divide-slate-200 dark:divide-slate-800 text-xs space-y-2.5">
                    <div class="flex justify-between items-center pt-1">
                        <span class="text-slate-500 dark:text-slate-400">Nama Pelanggan</span>
                        <span class="font-bold text-slate-900 dark:text-white text-sm" x-text="customerName"></span>
                    </div>

                    <div class="flex justify-between items-center pt-2.5">
                        <span class="text-slate-500 dark:text-slate-400">No. WhatsApp/Telp</span>
                        <span class="font-mono font-bold text-slate-800 dark:text-slate-200" x-text="customerPhone"></span>
                    </div>

                    <div class="flex justify-between items-center pt-2.5">
                        <span class="text-slate-500 dark:text-slate-400">Kategori Layanan</span>
                        <span class="font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400" x-text="category === 'barbershop' ? 'Barbershop' : 'MUA Studio'"></span>
                    </div>

                    <div class="flex justify-between items-center pt-2.5">
                        <span class="text-slate-500 dark:text-slate-400">Layanan Dipilih</span>
                        <span class="font-bold text-slate-900 dark:text-white text-right" x-text="selectedService ? selectedService.name : '-'"></span>
                    </div>

                    <div class="flex justify-between items-start pt-2.5" x-show="notes.trim().length > 0">
                        <span class="text-slate-500 dark:text-slate-400">Catatan</span>
                        <span class="font-medium text-slate-700 dark:text-slate-300 text-right max-w-[60%]" x-text="notes"></span>
                    </div>

                    <div class="flex justify-between items-center pt-3 text-sm">
                        <span class="font-bold text-slate-900 dark:text-white">Total Tagihan</span>
                        <span class="font-black text-base text-emerald-600 dark:text-emerald-400"
                              x-text="formatRupiah(selectedService ? selectedService.price : 0)"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button type="button"
                            @click="showConfirmModal = false"
                            class="w-full py-2.5 px-4 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-300 dark:border-slate-700 transition">
                        Periksa Kembali
                    </button>

                    <button type="button"
                            @click="submitWalkin()"
                            class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-blue-600/25 transition">
                        Konfirmasi & Ambil Antrian
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function walkinForm() {
            return {
                category: 'barbershop',
                customerName: '{{ old('customer_name', '') }}',
                customerPhone: '{{ old('customer_phone', '') }}',
                notes: '{{ old('notes', '') }}',
                selectedService: null,
                showConfirmModal: false,

                resetServices() {
                    this.selectedService = null;
                },

                selectService(id, name, price, formattedPrice) {
                    this.selectedService = { id, name, price, formattedPrice };
                },

                openConfirmModal() {
                    if (!this.customerName.trim() || !this.customerPhone.trim()) {
                        alert('Silakan lengkapi nama dan nomor telepon pelanggan.');
                        return;
                    }
                    if (!this.selectedService) {
                        alert('Silakan pilih 1 layanan yang dipesan.');
                        return;
                    }
                    this.showConfirmModal = true;
                },

                submitWalkin() {
                    this.$refs.walkinForm.submit();
                },

                formatRupiah(amount) {
                    return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }
            }
        }
    </script>
    @endpush
</x-kasir-layout>
