<x-admin-layout title="Edit Kasir - Grooming Lab & MUA">
    <div class="max-w-xl mx-auto py-4 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Edit Data Kasir</h1>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Perbarui data akun kasir {{ $cashier->name }}</p>
            </div>
            <a href="{{ route('admin.cashiers.index') }}" class="text-xs text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">
                &larr; Kembali
            </a>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm dark:shadow-xl space-y-6">
            <!-- Header Profil Kasir -->
            <div class="flex items-center gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
                <div class="relative flex-shrink-0">
                    @if($cashier->avatar)
                        <img src="{{ asset('storage/' . $cashier->avatar) }}" alt="{{ $cashier->name }}" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-amber-500/50 shadow-md">
                    @else
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500/20 via-slate-100 dark:via-slate-800 to-amber-500/10 ring-1 ring-amber-500/30 flex items-center justify-center text-amber-500 font-black text-xl shadow-md">
                            {{ strtoupper(substr($cashier->name, 0, 2)) }}
                        </div>
                    @endif
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">{{ $cashier->name }}</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">ID: KSR-{{ str_pad($cashier->id, 3, '0', STR_PAD_LEFT) }} &bull; {{ $cashier->email }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.cashiers.update', $cashier) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap *</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $cashier->name) }}" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Alamat Email *</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $cashier->email) }}" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nomor WhatsApp / HP * <span class="text-[10px] text-slate-400 font-normal">(Hanya angka)</span></label>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone', $cashier->phone) }}" required
                        inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 font-mono" />
                    @error('phone')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Keaktifan Akun Kasir (Pindahan dari Aksi Tabel) -->
                <div x-data="{ activeStatus: '{{ old('is_active', $cashier->is_active ? '1' : '0') }}' }">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Status Keaktifan Akun Kasir *
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label @click="activeStatus = '1'"
                               :class="activeStatus === '1' ? 'bg-emerald-500/10 border-emerald-500/40 text-emerald-800 dark:text-emerald-300 ring-2 ring-emerald-500/20 shadow-sm' : 'bg-slate-50 dark:bg-slate-800/60 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400'"
                               class="relative flex items-center gap-3 p-3.5 rounded-2xl border cursor-pointer transition">
                            <input type="radio" name="is_active" value="1" x-model="activeStatus" class="text-emerald-600 focus:ring-emerald-500" />
                            <div>
                                <div class="text-xs font-bold flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span>Aktif</span>
                                </div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Dapat login &amp; melayani antrian</div>
                            </div>
                        </label>

                        <label @click="activeStatus = '0'"
                               :class="activeStatus === '0' ? 'bg-rose-500/10 border-rose-500/40 text-rose-800 dark:text-rose-300 ring-2 ring-rose-500/20 shadow-sm' : 'bg-slate-50 dark:bg-slate-800/60 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400'"
                               class="relative flex items-center gap-3 p-3.5 rounded-2xl border cursor-pointer transition">
                            <input type="radio" name="is_active" value="0" x-model="activeStatus" class="text-rose-600 focus:ring-rose-500" />
                            <div>
                                <div class="text-xs font-bold flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                    <span>Nonaktif</span>
                                </div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Akses masuk kasir dibekukan</div>
                            </div>
                        </label>
                    </div>
                    @error('is_active')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2 border-t border-slate-200 dark:border-slate-800">
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">Kosongkan password jika tidak ingin mengubahnya.</p>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Password Baru (Opsional)</label>
                    <input id="password" type="password" name="password"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        placeholder="Minimal 8 karakter" />
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        placeholder="Ulangi password baru" />
                </div>

                <div class="pt-4 flex justify-end gap-2">
                    <a href="{{ route('admin.cashiers.index') }}" class="px-4 py-2.5 text-xs text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-xl">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg transition">
                        Perbarui Kasir
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
