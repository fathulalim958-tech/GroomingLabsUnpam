<x-admin-layout title="Tambah Staf - Grooming Lab & MUA">
    <div class="max-w-xl mx-auto py-4 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Tambah Staf Baru</h1>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Tambahkan tenaga kapster (barber) atau penata rias (MUA)</p>
            </div>
            <a href="{{ route('admin.staffs.index') }}" class="text-xs text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition flex items-center gap-1 font-semibold px-3 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                &larr; <span>Kembali</span>
            </a>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-7 shadow-sm dark:shadow-xl">
            <form method="POST" action="{{ route('admin.staffs.store') }}" class="space-y-4">
                @csrf

                <!-- 1. Nama Staf -->
                <div class="space-y-1.5">
                    <label for="name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Nama Staf *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50/70 hover:bg-white focus:bg-white dark:bg-slate-800/80 dark:focus:bg-slate-800 border border-slate-300/80 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder:text-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition shadow-sm font-sans"
                            placeholder="Contoh: Aldi Pratama" />
                    </div>
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 2. Peran / Profesi -->
                <div class="space-y-1.5">
                    <label for="role" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Peran / Profesi *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <select id="role" name="role" required
                            class="w-full pl-10 pr-9 py-2.5 bg-slate-50/70 hover:bg-white focus:bg-white dark:bg-slate-800/80 dark:focus:bg-slate-800 border border-slate-300/80 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition shadow-sm appearance-none">
                            <option value="kapster" {{ old('role') === 'kapster' ? 'selected' : '' }}>💈 Kapster / Barber (Perawatan Pria)</option>
                            <option value="mua" {{ old('role') === 'mua' ? 'selected' : '' }}>✨ MUA (Penata Rias &amp; Makeup)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    @error('role')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 3. Alamat Email -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                        Alamat Email <span class="text-[10px] text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="off"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50/70 hover:bg-white focus:bg-white dark:bg-slate-800/80 dark:focus:bg-slate-800 border border-slate-300/80 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition shadow-sm font-sans"
                            placeholder="nama.staf@barbershop.com" />
                    </div>
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 4. Nomor WhatsApp / HP -->
                <div class="space-y-1.5">
                    <label for="phone" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                        Nomor WhatsApp / HP <span class="text-[10px] text-slate-400 font-normal">(Hanya angka)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                            inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50/70 hover:bg-white focus:bg-white dark:bg-slate-800/80 dark:focus:bg-slate-800 border border-slate-300/80 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition shadow-sm font-mono"
                            placeholder="081234567890" />
                    </div>
                    @error('phone')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 5. Status Keaktifan Staf (Radio Button Cards) -->
                <div x-data="{ activeStatus: '{{ old('is_active', '1') }}' }" class="space-y-2 pt-2">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                        Status Keaktifan Staf *
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Radio Aktif -->
                        <label @click="activeStatus = '1'"
                               :class="activeStatus === '1'
                                   ? 'bg-emerald-500/10 border-emerald-500/60 text-emerald-950 dark:text-emerald-200 ring-2 ring-emerald-500/25 shadow-sm'
                                   : 'bg-slate-50/70 hover:bg-slate-100 dark:bg-slate-800/60 dark:hover:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400'"
                               class="relative flex items-center gap-3 p-3.5 rounded-2xl border cursor-pointer transition select-none">
                            <input type="radio" name="is_active" value="1" x-model="activeStatus" class="sr-only" />
                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition flex-shrink-0"
                                 :class="activeStatus === '1' ? 'border-emerald-500 bg-emerald-500' : 'border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800'">
                                <div class="w-2 h-2 rounded-full bg-white" x-show="activeStatus === '1'"></div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="text-xs font-bold flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>Aktif Bekerja</span>
                                </div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Staf aktif melayani antrian</div>
                            </div>
                        </label>

                        <!-- Radio Nonaktif -->
                        <label @click="activeStatus = '0'"
                               :class="activeStatus === '0'
                                   ? 'bg-rose-500/10 border-rose-500/60 text-rose-950 dark:text-rose-200 ring-2 ring-rose-500/25 shadow-sm'
                                   : 'bg-slate-50/70 hover:bg-slate-100 dark:bg-slate-800/60 dark:hover:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400'"
                               class="relative flex items-center gap-3 p-3.5 rounded-2xl border cursor-pointer transition select-none">
                            <input type="radio" name="is_active" value="0" x-model="activeStatus" class="sr-only" />
                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition flex-shrink-0"
                                 :class="activeStatus === '0' ? 'border-rose-500 bg-rose-500' : 'border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800'">
                                <div class="w-2 h-2 rounded-full bg-white" x-show="activeStatus === '0'"></div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="text-xs font-bold flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                    <span>Cuti / Nonaktif</span>
                                </div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Sedang cuti / tidak bertugas</div>
                            </div>
                        </label>
                    </div>
                    @error('is_active')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol Aksi -->
                <div class="pt-5 flex justify-end items-center gap-3 border-t border-slate-100 dark:border-slate-800">
                    <a href="{{ route('admin.staffs.index') }}" class="px-5 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-xl transition hover:bg-slate-100 dark:hover:bg-slate-800">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition transform active:scale-95 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Simpan Data Staf</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
