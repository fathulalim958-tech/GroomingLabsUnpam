<x-admin-layout title="Tambah Kasir - Grooming Lab & MUA">
    <div class="max-w-5xl mx-auto py-4 pb-16 space-y-6" x-data="createCashierForm()">
        <!-- Header Page -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Tambah Kasir Baru</h1>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Buat akun untuk staf kasir operasional, atur kategori spesialisasi, layanan, &amp; jadwal tugasnya</p>
            </div>
            <a href="{{ route('admin.cashiers.index') }}" class="text-xs text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition flex items-center gap-1 font-semibold px-3 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                &larr; <span>Kembali</span>
            </a>
        </div>

        <form method="POST" action="{{ route('admin.cashiers.store') }}">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <!-- Kolom Kiri: Informasi Akun, Kategori, & Password (lg:col-span-6) -->
                <div class="lg:col-span-6 space-y-5">
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm dark:shadow-xl space-y-5">
                        <div class="pb-3 border-b border-slate-100 dark:border-slate-800">
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                Informasi Akun Kasir
                            </h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Lengkapi identitas staf dan akses login</p>
                        </div>

                        <!-- 1. Nama Lengkap (Pilih dari Data Staf) -->
                        <div class="space-y-1.5">
                            <label for="staff_select" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                Nama Lengkap (Pilih dari Data Staf) *
                            </label>

                            @if($staffs->isEmpty())
                                <div class="p-3.5 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-900 dark:text-amber-200 text-xs space-y-2">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <div class="flex-1">
                                            <p class="font-bold">Belum ada staf yang dapat dipilih</p>
                                            <p class="text-[11px] text-amber-700/90 dark:text-amber-300/90 mt-0.5 leading-relaxed">
                                                Semua staf sudah dibuatkan akun kasir, atau belum ada staf terdaftar. Silakan buat data staf karyawan terlebih dahulu di menu Data Staf.
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.staffs.create') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-700 dark:text-amber-300 hover:underline">
                                        <span>+ Tambah Data Staf Baru</span> &rarr;
                                    </a>
                                </div>
                            @endif

                            <!-- Dropdown Select Staf -->
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <select id="staff_select"
                                        name="name"
                                        @change="onSelectStaff($event)"
                                        required
                                        class="w-full pl-10 pr-9 py-2.5 bg-slate-50/70 hover:bg-white focus:bg-white dark:bg-slate-800/80 dark:focus:bg-slate-800 border border-slate-300/80 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition shadow-sm appearance-none">
                                    <option value="">-- Pilih Nama dari Data Staf yang Ada --</option>
                                    @foreach ($staffs as $staff)
                                        <option value="{{ $staff->name }}"
                                                data-phone="{{ $staff->phone }}"
                                                data-email="{{ $staff->email }}"
                                                data-role="{{ $staff->role }}"
                                                {{ old('name') === $staff->name ? 'selected' : '' }}>
                                            {{ $staff->name }} ({{ $staff->role === 'kapster' ? '💈 Kapster / Barber' : '✨ MUA Penata Rias' }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>

                            @error('name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- 2. Alamat Email -->
                        <div class="space-y-1.5">
                            <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                Alamat Email *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input id="email" type="email" name="email" x-model="emailValue" required autocomplete="off"
                                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50/70 hover:bg-white focus:bg-white dark:bg-slate-800/80 dark:focus:bg-slate-800 border border-slate-300/80 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition shadow-sm font-sans"
                                    placeholder="nama.kasir@barbershop.com" />
                            </div>
                            @error('email')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- 3. Nomor WhatsApp / HP -->
                        <div class="space-y-1.5">
                            <label for="phone" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                Nomor WhatsApp / HP * <span class="text-[10px] text-slate-400 font-normal">(Hanya angka)</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <input id="phone" type="tel" name="phone" x-model="phoneValue" required
                                    inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50/70 hover:bg-white focus:bg-white dark:bg-slate-800/80 dark:focus:bg-slate-800 border border-slate-300/80 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition shadow-sm font-mono"
                                    placeholder="Contoh: 081234567890" />
                            </div>
                            @error('phone')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- 4. Kategori Akun Kasir / Staf (Barberman vs MUA) -->
                        <div class="space-y-2 pt-1">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                Kategori Akun Kasir / Staf *
                            </label>
                            <input type="hidden" name="service_category" :value="serviceCategory" />
                            <div class="grid grid-cols-2 gap-3">
                                <div @click="setCategory('barbershop')"
                                     :class="serviceCategory === 'barbershop'
                                         ? 'bg-amber-500/10 border-amber-500/40 text-amber-900 dark:text-amber-200 ring-2 ring-amber-500/20 shadow-sm'
                                         : 'bg-slate-50 dark:bg-slate-800/60 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:border-slate-300'"
                                     class="relative flex items-center gap-3 p-3 rounded-2xl border cursor-pointer transition select-none">
                                    <div class="w-8 h-8 rounded-xl bg-amber-500/15 text-amber-600 dark:text-amber-400 flex items-center justify-center text-sm font-bold flex-shrink-0">
                                        💈
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs font-bold">Barberman</div>
                                        <div class="text-[10px] text-slate-500 dark:text-slate-400 truncate">Layanan potong pria</div>
                                    </div>
                                </div>

                                <div @click="setCategory('mua')"
                                     :class="serviceCategory === 'mua'
                                         ? 'bg-pink-500/10 border-pink-500/40 text-pink-900 dark:text-pink-200 ring-2 ring-pink-500/20 shadow-sm'
                                         : 'bg-slate-50 dark:bg-slate-800/60 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:border-slate-300'"
                                     class="relative flex items-center gap-3 p-3 rounded-2xl border cursor-pointer transition select-none">
                                    <div class="w-8 h-8 rounded-xl bg-pink-500/15 text-pink-600 dark:text-pink-400 flex items-center justify-center text-sm font-bold flex-shrink-0">
                                        ✨
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs font-bold">MUA Studio</div>
                                        <div class="text-[10px] text-slate-500 dark:text-slate-400 truncate">Layanan rias wajah</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Password & Konfirmasi -->
                        <div class="pt-3 border-t border-slate-100 dark:border-slate-800 space-y-3">
                            <div>
                                <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">Password Akun *</p>
                                <p class="text-[11px] text-slate-500">Digunakan kasir untuk login masuk ke sistem kasir.</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label for="password" class="block text-[11px] font-semibold text-slate-700 dark:text-slate-300">Password *</label>
                                    <input id="password" type="password" name="password" required autocomplete="new-password"
                                        class="w-full px-3.5 py-2 bg-slate-50/70 hover:bg-white focus:bg-white dark:bg-slate-800/80 dark:focus:bg-slate-800 border border-slate-300/80 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder:text-slate-400 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow-sm"
                                        placeholder="Min. 8 karakter..." />
                                    @error('password')
                                        <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-1">
                                    <label for="password_confirmation" class="block text-[11px] font-semibold text-slate-700 dark:text-slate-300">Ulangi Password *</label>
                                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                        class="w-full px-3.5 py-2 bg-slate-50/70 hover:bg-white focus:bg-white dark:bg-slate-800/80 dark:focus:bg-slate-800 border border-slate-300/80 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder:text-slate-400 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow-sm"
                                        placeholder="Ketik ulang password..." />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Checklist Layanan & Jadwal Ketersediaan (lg:col-span-6) -->
                <div class="lg:col-span-6 space-y-5">
                    <!-- Card Checklist Layanan -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm dark:shadow-xl space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                            <div>
                                <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                                    <span>Layanan yang Dapat Dilayani</span>
                                    <span class="text-[10px] font-mono px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-bold"
                                          x-text="selectedServices.length + ' Dipilih'"></span>
                                </h3>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                    <span x-show="serviceCategory === 'barbershop'">Khusus katalog <strong>Barberman</strong>.</span>
                                    <span x-show="serviceCategory === 'mua'" x-cloak>Khusus katalog <strong>MUA Studio</strong>.</span>
                                </p>
                            </div>

                            <div class="flex items-center gap-2">
                                <button type="button" @click="selectAllCurrentCategory()" class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                                    Pilih Semua
                                </button>
                                <span class="text-slate-300 dark:text-slate-600 text-xs">&bull;</span>
                                <button type="button" @click="deselectAllCurrentCategory()" class="text-[11px] font-bold text-rose-500 hover:underline">
                                    Kosongkan
                                </button>
                            </div>
                        </div>

                        <!-- Scrollable list of services -->
                        <div class="max-h-64 overflow-y-auto pr-1 space-y-2">
                            <!-- 1. Layanan Barbershop -->
                            <div x-show="serviceCategory === 'barbershop'" class="space-y-2">
                                @forelse ($barberServices as $service)
                                    <label class="flex items-center justify-between p-2.5 rounded-xl border cursor-pointer transition select-none text-xs"
                                           :class="selectedServices.includes({{ $service->id }})
                                               ? 'bg-amber-500/10 border-amber-500/40 text-slate-900 dark:text-white font-semibold shadow-sm'
                                               : 'bg-slate-50/80 dark:bg-slate-800/60 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-slate-300'">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <input type="checkbox" name="services[]" value="{{ $service->id }}"
                                                   :checked="selectedServices.includes({{ $service->id }})"
                                                   @change="toggleService({{ $service->id }})"
                                                   class="w-4 h-4 rounded text-amber-600 focus:ring-amber-500 border-slate-300 dark:border-slate-600 flex-shrink-0" />
                                            <span class="truncate">{{ $service->name }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-[11px] flex-shrink-0">
                                            <span class="text-slate-400 font-mono">{{ $service->duration_minutes }} mnt</span>
                                            <span class="font-bold text-emerald-600 dark:text-emerald-400 font-mono">{{ $service->formatted_price }}</span>
                                        </div>
                                    </label>
                                @empty
                                    <p class="text-xs text-slate-500 italic py-2">Belum ada katalog layanan barbershop aktif.</p>
                                @endforelse
                            </div>

                            <!-- 2. Layanan MUA -->
                            <div x-show="serviceCategory === 'mua'" x-cloak class="space-y-2">
                                @forelse ($muaServices as $service)
                                    <label class="flex items-center justify-between p-2.5 rounded-xl border cursor-pointer transition select-none text-xs"
                                           :class="selectedServices.includes({{ $service->id }})
                                               ? 'bg-pink-500/10 border-pink-500/40 text-slate-900 dark:text-white font-semibold shadow-sm'
                                               : 'bg-slate-50/80 dark:bg-slate-800/60 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-slate-300'">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <input type="checkbox" name="services[]" value="{{ $service->id }}"
                                                   :checked="selectedServices.includes({{ $service->id }})"
                                                   @change="toggleService({{ $service->id }})"
                                                   class="w-4 h-4 rounded text-pink-600 focus:ring-pink-500 border-slate-300 dark:border-slate-600 flex-shrink-0" />
                                            <span class="truncate">{{ $service->name }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-[11px] flex-shrink-0">
                                            <span class="text-slate-400 font-mono">{{ $service->duration_minutes }} mnt</span>
                                            <span class="font-bold text-pink-600 dark:text-pink-400 font-mono">{{ $service->formatted_price }}</span>
                                        </div>
                                    </label>
                                @empty
                                    <p class="text-xs text-slate-500 italic py-2">Belum ada katalog layanan MUA aktif.</p>
                                @endforelse
                            </div>
                        </div>
                        @error('services')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Card Jadwal Ketersediaan Bertugas -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm dark:shadow-xl space-y-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="block text-xs font-bold text-slate-800 dark:text-slate-200">
                                    Jadwal Ketersediaan Bertugas *
                                </label>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                    Klik tombol hari di bawah untuk memilih manual
                                </p>
                            </div>
                            <span class="text-[10px] font-mono px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-bold"
                                  x-text="days.length + ' Hari Aktif'"></span>
                        </div>

                        <!-- 7-Day Buttons Grid -->
                        <div class="grid grid-cols-4 sm:grid-cols-7 gap-2 pt-1">
                            @php
                                $daysMap = [
                                    1 => 'Sen',
                                    2 => 'Sel',
                                    3 => 'Rab',
                                    4 => 'Kam',
                                    5 => 'Jum',
                                    6 => 'Sab',
                                    0 => 'Min',
                                ];
                            @endphp
                            @foreach ($daysMap as $dayVal => $dayLabel)
                                <label class="flex flex-col items-center justify-center py-2.5 px-1.5 rounded-xl border text-center cursor-pointer transition select-none shadow-sm"
                                       :class="days.includes({{ $dayVal }})
                                           ? 'bg-emerald-500 text-slate-950 font-bold border-emerald-500 ring-2 ring-emerald-500/20 shadow-md'
                                           : 'bg-slate-50/80 dark:bg-slate-800/60 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:border-slate-300 dark:hover:border-slate-600'">
                                    <input type="checkbox" name="work_days[]" value="{{ $dayVal }}"
                                           :checked="days.includes({{ $dayVal }})"
                                           @change="toggleDay({{ $dayVal }})"
                                           class="sr-only" />
                                    <span class="text-xs font-bold">{{ $dayLabel }}</span>
                                    <span class="text-[9px] mt-0.5" :class="days.includes({{ $dayVal }}) ? 'text-slate-950 font-semibold' : 'text-slate-400'" x-text="days.includes({{ $dayVal }}) ? '✓ Aktif' : 'Off'"></span>
                                </label>
                            @endforeach
                        </div>
                        @error('work_days')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-4 shadow-sm dark:shadow-xl flex items-center justify-end gap-3">
                        <a href="{{ route('admin.cashiers.index') }}" class="px-5 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-xl transition hover:bg-slate-100 dark:hover:bg-slate-800">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition transform active:scale-95 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Simpan Data Kasir</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function createCashierForm() {
            return {
                emailValue: '{{ old('email', '') }}',
                phoneValue: '{{ old('phone', '') }}',
                serviceCategory: '{{ old('service_category', 'barbershop') }}',
                selectedServices: {{ json_encode(old('services', [])) }},
                barberServiceIds: {{ json_encode($barberServices->pluck('id')->toArray()) }},
                muaServiceIds: {{ json_encode($muaServices->pluck('id')->toArray()) }},
                days: {{ json_encode(old('work_days', [1, 2, 3, 4, 5, 6, 0])) }},

                onSelectStaff(e) {
                    const select = e.target;
                    const opt = select.options[select.selectedIndex];
                    if (!opt || !opt.value) return;

                    const email = opt.getAttribute('data-email');
                    if (email) {
                        this.emailValue = email;
                    }
                    const phone = opt.getAttribute('data-phone');
                    if (phone) {
                        this.phoneValue = phone;
                    }
                    const role = opt.getAttribute('data-role');
                    if (role) {
                        const newCat = role === 'mua' ? 'mua' : 'barbershop';
                        if (this.serviceCategory !== newCat) {
                            this.setCategory(newCat);
                        }
                    }
                },

                setCategory(cat) {
                    this.serviceCategory = cat;
                    const validIds = cat === 'barbershop' ? this.barberServiceIds : this.muaServiceIds;
                    this.selectedServices = this.selectedServices.filter(id => validIds.includes(id));
                },

                toggleService(id) {
                    const idx = this.selectedServices.indexOf(id);
                    if (idx > -1) {
                        this.selectedServices.splice(idx, 1);
                    } else {
                        this.selectedServices.push(id);
                    }
                },

                selectAllCurrentCategory() {
                    const validIds = this.serviceCategory === 'barbershop' ? this.barberServiceIds : this.muaServiceIds;
                    this.selectedServices = [...validIds];
                },

                deselectAllCurrentCategory() {
                    this.selectedServices = [];
                },

                toggleDay(d) {
                    const idx = this.days.indexOf(d);
                    if (idx > -1) {
                        this.days.splice(idx, 1);
                    } else {
                        this.days.push(d);
                    }
                }
            };
        }
    </script>
    @endpush
</x-admin-layout>
