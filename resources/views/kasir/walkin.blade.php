<x-kasir-layout title="Order Walk-In - Kasir Grooming Lab & MUA">
    <div class="max-w-2xl mx-auto py-4" x-data="walkinForm()">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-blue-400">Kasir Front-Desk</span>
                <h1 class="text-2xl font-extrabold text-white mt-0.5">Input Pesanan Walk-In (Offline)</h1>
                <p class="text-xs text-slate-400 mt-1">Tambahkan antrian untuk pelanggan yang datang langsung ke outlet</p>
            </div>
            <a href="{{ route('kasir.dashboard') }}" class="text-xs text-slate-400 hover:text-white transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>

        <form method="POST" action="{{ route('kasir.walkin.store') }}" class="space-y-6">
            @csrf

            <!-- 1. Customer Information -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 space-y-4">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                    1. Data Pelanggan Walk-In
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="customer_name" class="block text-xs font-semibold text-slate-300 mb-1">Nama Pelanggan *</label>
                        <input id="customer_name" type="text" name="customer_name" value="{{ old('customer_name') }}" required
                            class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Contoh: Rian Pratama" />
                        @error('customer_name')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="customer_phone" class="block text-xs font-semibold text-slate-300 mb-1">No. WhatsApp / Telepon *</label>
                        <input id="customer_phone" type="tel" name="customer_phone" value="{{ old('customer_phone') }}" required
                            class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Contoh: 081299887766" />
                        @error('customer_phone')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 2. Category Selection -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 space-y-4">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                    2. Kategori Layanan
                </h2>

                <div class="grid grid-cols-2 gap-4">
                    <label class="p-4 rounded-xl border-2 transition cursor-pointer flex items-center gap-3"
                           :class="category === 'barbershop' ? 'bg-amber-500/10 border-amber-500 text-white' : 'bg-slate-800/50 border-slate-800 text-slate-400'">
                        <input type="radio" name="category" value="barbershop" x-model="category" @change="resetServices()" class="text-amber-500 focus:ring-amber-500">
                        <div>
                            <span class="font-bold text-sm block">Barbershop</span>
                            <span class="text-[11px] text-slate-400">Pria & Haircare</span>
                        </div>
                    </label>

                    <label class="p-4 rounded-xl border-2 transition cursor-pointer flex items-center gap-3"
                           :class="category === 'mua' ? 'bg-pink-500/10 border-pink-500 text-white' : 'bg-slate-800/50 border-slate-800 text-slate-400'">
                        <input type="radio" name="category" value="mua" x-model="category" @change="resetServices()" class="text-pink-500 focus:ring-pink-500">
                        <div>
                            <span class="font-bold text-sm block">MUA Studio</span>
                            <span class="text-[11px] text-slate-400">Tata Rias & Makeup</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- 3. Service Selection -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                        3. Pilih Layanan
                    </h2>
                    <span class="text-xs text-slate-400">Centang layanan yang dipesan</span>
                </div>

                <!-- Barbershop Services List -->
                <div x-show="category === 'barbershop'" class="space-y-2">
                    @foreach ($barbershopServices as $s)
                        <label class="flex items-center justify-between p-3 rounded-xl border transition cursor-pointer"
                               :class="selectedServices.includes({{ $s->id }}) ? 'bg-amber-500/10 border-amber-500/60' : 'bg-slate-800/40 border-slate-800 hover:border-slate-700'">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="services[]" value="{{ $s->id }}"
                                       @change="toggleService({{ $s->id }}, {{ $s->price }})"
                                       :disabled="category !== 'barbershop'"
                                       class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-amber-500 focus:ring-amber-500">
                                <div>
                                    <span class="text-sm font-bold text-white">{{ $s->name }}</span>
                                    <span class="text-xs text-slate-400 block">{{ $s->duration_minutes }} Menit</span>
                                </div>
                            </div>
                            <span class="text-sm font-extrabold text-amber-400">{{ $s->formatted_price }}</span>
                        </label>
                    @endforeach
                </div>

                <!-- MUA Services List -->
                <div x-show="category === 'mua'" class="space-y-2" style="display: none;">
                    @foreach ($muaServices as $s)
                        <label class="flex items-center justify-between p-3 rounded-xl border transition cursor-pointer"
                               :class="selectedServices.includes({{ $s->id }}) ? 'bg-pink-500/10 border-pink-500/60' : 'bg-slate-800/40 border-slate-800 hover:border-slate-700'">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="services[]" value="{{ $s->id }}"
                                       @change="toggleService({{ $s->id }}, {{ $s->price }})"
                                       :disabled="category !== 'mua'"
                                       class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-pink-500 focus:ring-pink-500">
                                <div>
                                    <span class="text-sm font-bold text-white">{{ $s->name }}</span>
                                    <span class="text-xs text-slate-400 block">{{ $s->duration_minutes }} Menit</span>
                                </div>
                            </div>
                            <span class="text-sm font-extrabold text-pink-400">{{ $s->formatted_price }}</span>
                        </label>
                    @endforeach
                </div>

                @error('services')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 4. Notes & Summary -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 space-y-4">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                    4. Catatan Tambahan
                </h2>

                <textarea name="notes" rows="2"
                          class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Catatan khusus, request kapster/MUA, dll...">{{ old('notes') }}</textarea>

                <div class="pt-3 border-t border-slate-800 flex items-center justify-between">
                    <div>
                        <span class="text-xs text-slate-400 block">Total Biaya Walk-In</span>
                        <span class="text-2xl font-black text-amber-400" x-text="formatRupiah(totalPrice)">Rp 0</span>
                    </div>

                    <button type="submit"
                            :disabled="selectedServices.length === 0"
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-500 hover:to-cyan-500 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 transition transform active:scale-95 text-sm">
                        Cetak Antrian & Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function walkinForm() {
            return {
                category: 'barbershop',
                selectedServices: [],
                totalPrice: 0,
                toggleService(id, price) {
                    const idx = this.selectedServices.indexOf(id);
                    if (idx > -1) {
                        this.selectedServices.splice(idx, 1);
                        this.totalPrice -= price;
                    } else {
                        this.selectedServices.push(id);
                        this.totalPrice += price;
                    }
                },
                resetServices() {
                    this.selectedServices = [];
                    this.totalPrice = 0;
                    document.querySelectorAll('input[type="checkbox"]').forEach(c => c.checked = false);
                },
                formatRupiah(amount) {
                    return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }
            }
        }
    </script>
    @endpush
</x-kasir-layout>
