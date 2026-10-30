<x-pelanggan-layout title="Profil Saya - Grooming Lab & MUA">
    <div class="max-w-xl mx-auto py-3 sm:py-6 space-y-5" x-data="{
        activeAccordion: '{{ $errors->updatePassword->isNotEmpty() ? 'password' : ($errors->any() ? 'profile' : '') }}'
    }">
        <!-- Page Title Header -->
        <div class="flex items-center justify-between">
            <div>
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-amber-500">Akun Pelanggan</span>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">Profil Saya</h1>
            </div>
            <a href="{{ route('pelanggan.home') }}" class="text-xs text-slate-500 dark:text-slate-400 hover:text-amber-500 transition flex items-center gap-1 font-semibold">
                <span>&larr; Beranda</span>
            </a>
        </div>

        <!-- Flash Status Alerts -->
        @if (session('status') === 'profile-updated')
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-600 dark:text-emerald-400 text-xs font-bold flex items-center gap-2.5 shadow-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Informasi profil Anda berhasil diperbarui.</span>
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-600 dark:text-emerald-400 text-xs font-bold flex items-center gap-2.5 shadow-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Kata sandi akun Anda berhasil diubah.</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 bg-rose-500/10 border border-rose-500/30 rounded-2xl text-rose-600 dark:text-rose-400 text-xs font-medium space-y-1">
                <div class="font-bold flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Terdapat kendala pada input formulir:</span>
                </div>
                <ul class="list-disc list-inside pl-1 space-y-0.5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- 1. KARTU UTAMA IDENTITAS PELANGGAN (Hero Card GlowCut) -->
        <div class="bg-white dark:bg-[#141414] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl p-5 sm:p-7 shadow-sm dark:shadow-2xl relative overflow-hidden">
            <!-- Subtle Gold Ambient Glow in Top Right -->
            <div class="absolute -top-16 -right-16 w-36 h-36 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-5 relative z-10">
                <!-- Avatar Profil dengan Cincin Emas -->
                <div class="relative flex-shrink-0 cursor-pointer group" @click="activeAccordion = 'profile'" title="Klik untuk ubah foto profil">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl object-cover ring-2 ring-amber-500/60 ring-offset-2 ring-offset-white dark:ring-offset-[#141414] shadow-xl group-hover:opacity-90 transition">
                    @else
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-gradient-to-br from-amber-500/30 via-[#1A1A1A] to-amber-500/10 ring-2 ring-amber-500/60 ring-offset-2 ring-offset-white dark:ring-offset-[#141414] flex items-center justify-center text-amber-500 font-black text-2xl sm:text-3xl shadow-xl group-hover:scale-105 transition">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-black/40 rounded-2xl opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white">
                        <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        </svg>
                    </div>
                    <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-emerald-500 border-2 border-white dark:border-[#141414] flex items-center justify-center text-white text-[9px] font-bold" title="Akun Aktif">
                        ✓
                    </span>
                </div>

                <!-- Info Pelanggan -->
                <div class="text-center sm:text-left flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5">
                        <h2 class="text-lg sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight truncate">{{ $user->name }}</h2>
                        <div>
                            @if ($user->is_member)
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-black bg-gradient-to-r from-amber-500 to-yellow-400 text-slate-950 shadow-md shadow-amber-500/20">
                                    <span>⭐</span> VIP MEMBER
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-[#1E1E1E] text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-[#2E2E2E]">
                                    Pelanggan Terdaftar
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Kontak Ringkas -->
                    <div class="mt-3 space-y-1.5 text-xs text-slate-600 dark:text-slate-300">
                        <div class="flex items-center justify-center sm:justify-start gap-2">
                            <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span class="font-mono font-bold">{{ $user->phone ?? 'Belum ada nomor telepon' }}</span>
                        </div>
                        <div class="flex items-center justify-center sm:justify-start gap-2">
                            <svg class="w-3.5 h-3.5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="font-mono truncate max-w-[220px]">{{ $user->email }}</span>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-[#222222] flex items-center justify-between text-[11px] text-slate-400">
                        <span>Bergabung sejak: <strong class="text-slate-700 dark:text-slate-300">{{ $user->created_at->format('d M Y') }}</strong></span>
                        <span class="text-amber-500 font-semibold font-mono">#ID-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if ($user->is_member)
            <!-- 2. KARTU VIP MEMBER EKSKLUSIF (Black & Gold Card) -->
            <div class="bg-gradient-to-br from-[#1c1811] via-[#141414] to-[#241d10] border border-amber-500/40 rounded-3xl p-5 sm:p-6 shadow-xl relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-amber-500/10 rounded-full blur-xl pointer-events-none"></div>

                <div class="flex items-start justify-between gap-4 relative z-10">
                    <div class="space-y-1">
                        <span class="text-[10px] uppercase font-bold tracking-widest text-amber-500">Privilege Card</span>
                        <h3 class="text-base sm:text-lg font-black text-white flex items-center gap-2">
                            <span>⭐</span> VIP MEMBER GLOWCUT
                        </h3>
                        <p class="text-xs text-slate-300 pt-1">
                            Nikmati potongan harga spesial otomatis setiap kali melakukan reservasi layanan.
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/20 border border-amber-500/40 flex items-center justify-center text-amber-500 font-black text-lg shadow-inner flex-shrink-0">
                        GL
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 mt-4 pt-4 border-t border-amber-500/20 text-xs relative z-10">
                    <div class="bg-[#0e0e0e]/70 p-3 rounded-2xl border border-amber-500/20">
                        <span class="text-[10px] text-slate-400 uppercase font-semibold block">Diskon Tiap Booking</span>
                        <span class="text-base sm:text-lg font-black text-amber-400 font-mono">{{ $user->member_discount_label }}</span>
                    </div>
                    <div class="bg-[#0e0e0e]/70 p-3 rounded-2xl border border-amber-500/20">
                        <span class="text-[10px] text-slate-400 uppercase font-semibold block">Sisa Kuota Diskon</span>
                        <span class="text-base sm:text-lg font-black font-mono {{ $user->member_discount_remaining > 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                            {{ $user->member_discount_remaining }}x Sesi
                        </span>
                    </div>
                </div>
            </div>
        @endif

        <!-- 3. KARTU MENU PENGATURAN & AKSI INTERAKTIF -->
        <div class="space-y-3">
            <!-- Card Action 1: Ubah Data Diri (Expandable Card) -->
            <div class="bg-white dark:bg-[#141414] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl overflow-hidden shadow-sm transition hover:border-amber-500/40">
                <button type="button"
                        @click="activeAccordion = activeAccordion === 'profile' ? '' : 'profile'"
                        class="w-full p-4 sm:p-5 flex items-center justify-between text-left transition focus:outline-none">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center flex-shrink-0 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Ubah Data Diri</h3>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Nama lengkap, nomor WhatsApp, dan email</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 transition transform duration-200"
                         :class="{ 'rotate-180': activeAccordion === 'profile' }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Form Ubah Data Diri -->
                <div x-show="activeAccordion === 'profile'"
                     x-collapse
                     x-cloak
                     class="px-4 pb-5 sm:px-5 sm:pb-6 pt-2 border-t border-slate-100 dark:border-[#222222]">
                    <form method="post" action="{{ route('pelanggan.profile.update') }}" enctype="multipart/form-data" class="space-y-4 pt-1" x-data="{
                        photoPreview: null,
                        updatePhotoPreview() {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.photoPreview = e.target.result;
                            };
                            reader.readAsDataURL(this.$refs.photo.files[0]);
                        }
                    }">
                        @csrf
                        @method('patch')

                        <!-- Upload Foto Profil Pelanggan -->
                        <div class="pb-2 border-b border-slate-100 dark:border-[#222222]">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Foto Profil</label>
                            <div class="flex items-center gap-4">
                                <div class="relative flex-shrink-0">
                                    <template x-if="photoPreview">
                                        <img :src="photoPreview" class="w-16 h-16 rounded-2xl object-cover ring-2 ring-amber-500 shadow-md">
                                    </template>
                                    <template x-if="!photoPreview">
                                        <div>
                                            @if($user->avatar)
                                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-2xl object-cover ring-2 ring-amber-500/60 shadow-md">
                                            @else
                                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-500/20 via-[#1A1A1A] to-amber-500/10 ring-1 ring-amber-500/40 flex items-center justify-center text-amber-500 font-black text-lg">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                            @endif
                                        </div>
                                    </template>
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="avatar" id="avatar" x-ref="photo" @change="updatePhotoPreview" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <button type="button" @click="$refs.photo.click()" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-[#1E1E1E] dark:hover:bg-[#2A2A2A] text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-[#333] rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span>Pilih Foto Baru</span>
                                        </button>
                                        @if($user->avatar)
                                            <label class="text-[11px] text-rose-500 dark:text-rose-400 font-semibold cursor-pointer flex items-center gap-1 hover:underline">
                                                <input type="checkbox" name="delete_avatar" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                                <span>Hapus Foto</span>
                                            </label>
                                        @endif
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1">Format: JPG, JPEG, PNG, WEBP (Maksimal 2 MB)</p>
                                </div>
                            </div>
                            @error('avatar')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap *</label>
                            <input id="name" name="name" type="text"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#0E0E0E] border border-slate-300 dark:border-[#2A2A2A] rounded-xl text-slate-900 dark:text-white text-xs sm:text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                   value="{{ old('name', $user->name) }}" required autocomplete="name" />
                            @error('name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Nomor Telepon / WhatsApp * <span class="text-[10px] text-slate-400 font-normal">(Hanya angka)</span>
                            </label>
                            <input id="phone" name="phone" type="tel" inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#0E0E0E] border border-slate-300 dark:border-[#2A2A2A] rounded-xl text-slate-900 dark:text-white text-xs sm:text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 font-mono"
                                   value="{{ old('phone', $user->phone) }}" required autocomplete="tel" placeholder="Contoh: 081234567890" />
                            @error('phone')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Alamat Email *</label>
                            <input id="email" name="email" type="email"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#0E0E0E] border border-slate-300 dark:border-[#2A2A2A] rounded-xl text-slate-900 dark:text-white text-xs sm:text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                   value="{{ old('email', $user->email) }}" required autocomplete="username" />
                            @error('email')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs rounded-xl shadow-md transition flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Card Action 2: Ubah Kata Sandi (Expandable Card) -->
            <div class="bg-white dark:bg-[#141414] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl overflow-hidden shadow-sm transition hover:border-amber-500/40">
                <button type="button"
                        @click="activeAccordion = activeAccordion === 'password' ? '' : 'password'"
                        class="w-full p-4 sm:p-5 flex items-center justify-between text-left transition focus:outline-none">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center flex-shrink-0 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Ubah Kata Sandi</h3>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Ganti password akun untuk keamanan</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 transition transform duration-200"
                         :class="{ 'rotate-180': activeAccordion === 'password' }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Form Ubah Password -->
                <div x-show="activeAccordion === 'password'"
                     x-collapse
                     x-cloak
                     class="px-4 pb-5 sm:px-5 sm:pb-6 pt-2 border-t border-slate-100 dark:border-[#222222]">
                    <form method="post" action="{{ route('password.update') }}" class="space-y-4 pt-1">
                        @csrf
                        @method('put')

                        <div>
                            <label for="update_password_current_password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kata Sandi Saat Ini *</label>
                            <input id="update_password_current_password" name="current_password" type="password"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#0E0E0E] border border-slate-300 dark:border-[#2A2A2A] rounded-xl text-slate-900 dark:text-white text-xs sm:text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                   autocomplete="current-password" placeholder="Masukkan kata sandi lama..." />
                            @error('current_password', 'updatePassword')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="update_password_password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kata Sandi Baru *</label>
                            <input id="update_password_password" name="password" type="password"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#0E0E0E] border border-slate-300 dark:border-[#2A2A2A] rounded-xl text-slate-900 dark:text-white text-xs sm:text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                   autocomplete="new-password" placeholder="Minimal 8 karakter..." />
                            @error('password', 'updatePassword')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="update_password_password_confirmation" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Konfirmasi Kata Sandi Baru *</label>
                            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#0E0E0E] border border-slate-300 dark:border-[#2A2A2A] rounded-xl text-slate-900 dark:text-white text-xs sm:text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                   autocomplete="new-password" placeholder="Ketik ulang kata sandi baru..." />
                            @error('password_confirmation', 'updatePassword')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs rounded-xl shadow-md transition flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <span>Perbarui Kata Sandi</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Card Action 3: Navigasi Cepat (Riwayat Transaksi & Antrean) -->
            <div class="grid grid-cols-2 gap-3 pt-1">
                <a href="{{ route('pelanggan.history.index') }}"
                   class="bg-white dark:bg-[#141414] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-4 shadow-sm hover:border-amber-500/50 transition flex flex-col justify-between group">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-slate-400 group-hover:text-amber-500 transition text-sm">&rarr;</span>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 dark:text-white">Riwayat Booking</h4>
                        <p class="text-[10px] text-slate-400 mt-0.5">Semua pesanan Anda</p>
                    </div>
                </a>

                <a href="{{ route('pelanggan.order.index') }}"
                   class="bg-white dark:bg-[#141414] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-4 shadow-sm hover:border-amber-500/50 transition flex flex-col justify-between group">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <span class="text-slate-400 group-hover:text-amber-500 transition text-sm">&rarr;</span>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 dark:text-white">Antrean Saya</h4>
                        <p class="text-[10px] text-slate-400 mt-0.5">Pantau giliran antrean</p>
                    </div>
                </a>
            </div>

            <!-- Card Action 4: Hubungi Customer Service Salon -->
            <a href="https://wa.me/6281234567890?text=Halo%20Grooming%20Lab%2C%20saya%20ingin%20bertanya%20mengenai%20layanan" target="_blank"
               class="bg-white dark:bg-[#141414] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-4 sm:p-4.5 shadow-sm hover:border-emerald-500/50 transition flex items-center justify-between group">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Pusat Bantuan & WhatsApp</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Konsultasi jadwal & layanan bersama admin</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-emerald-500 group-hover:translate-x-1 transition transform flex items-center gap-1">
                    <span>Chat WA</span>
                    <span>&rarr;</span>
                </span>
            </a>
        </div>

        <!-- 4. KARTU TOMBOL KELUAR (Logout) -->
        <div class="pt-2">
            <button type="button"
                    @click="showLogoutModal = true"
                    class="w-full py-3.5 px-4 bg-rose-500/10 hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 font-bold text-xs sm:text-sm rounded-2xl border border-rose-500/30 transition flex items-center justify-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Keluar dari Akun</span>
            </button>
        </div>
    </div>
</x-pelanggan-layout>
