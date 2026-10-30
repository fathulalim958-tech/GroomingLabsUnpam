<x-kasir-layout title="Profil Akun Kasir - Grooming Lab">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header Banner -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-4 sm:p-5 shadow-sm">
            <div>
                <h1 class="text-sm sm:text-base lg:text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight flex items-center gap-2">
                    <span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-amber-500 animate-pulse"></span>
                    PROFIL AKUN KASIR & DATA DIRI KARYAWAN
                </h1>
                <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Kelola data diri karyawan, nomor kontak WhatsApp, dan keamanan kata sandi akun operasional kasir.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-[11px] sm:text-xs font-bold flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Status: Kasir Aktif</span>
                </span>
            </div>
        </div>

        <!-- Kartu Identitas & Biodata Karyawan Kasir -->
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-2xl sm:rounded-3xl p-5 sm:p-8 shadow-sm">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-6 pb-5 sm:pb-6 border-b border-slate-100 dark:border-slate-700/60">
                <!-- Avatar Profil -->
                <div class="relative flex-shrink-0">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-16 h-16 sm:w-24 sm:h-24 rounded-2xl sm:rounded-3xl object-cover border-2 border-amber-500 shadow-xl">
                    @else
                        <div class="w-16 h-16 sm:w-24 sm:h-24 rounded-2xl sm:rounded-3xl bg-gradient-to-br from-amber-500/30 to-amber-500/10 border-2 border-amber-500/40 flex items-center justify-center text-amber-500 font-black text-xl sm:text-3xl shadow-xl">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    @endif
                    <span class="absolute -bottom-1 -right-1 w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-emerald-500 border-2 border-white dark:border-slate-800 flex items-center justify-center text-white text-[8px] sm:text-[9px]" title="Aktif">
                        ✓
                    </span>
                </div>

                <!-- Info Header -->
                <div class="text-center sm:text-left flex-1">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h2 class="text-base sm:text-xl lg:text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ $user->name }}</h2>
                            <p class="text-[10px] sm:text-xs text-amber-500 font-bold uppercase tracking-wider mt-0.5">Staf Kasir & Front-Desk</p>
                        </div>
                        <div class="flex items-center justify-center sm:justify-end gap-2">
                            <span class="text-[11px] sm:text-xs font-mono px-2.5 sm:px-3 py-1 rounded-xl bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 font-bold">
                                ID: KSR-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                    </div>

                    <!-- Informasi Rinci Biodata -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-5 pt-4 border-t border-slate-100 dark:border-slate-700/60 text-xs">
                        <div class="flex items-center gap-3 text-slate-600 dark:text-slate-300">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-500 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase font-semibold block">WhatsApp / HP</span>
                                <span class="font-mono font-bold text-slate-900 dark:text-white">{{ $user->phone ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 text-slate-600 dark:text-slate-300">
                            <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-500 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase font-semibold block">Email Akun</span>
                                <span class="font-mono font-bold text-slate-900 dark:text-white truncate block max-w-[200px]" title="{{ $user->email }}">{{ $user->email }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 text-slate-600 dark:text-slate-300">
                            <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase font-semibold block">Terdaftar Sejak</span>
                                <span class="font-bold text-slate-900 dark:text-white">{{ $user->created_at->format('d F Y') }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 text-slate-600 dark:text-slate-300">
                            <div class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-500 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase font-semibold block">Hak Akses Sistem</span>
                                <span class="font-bold text-emerald-600 dark:text-emerald-400">Kasir Front-Desk Operasional</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Pembaruan Kontak & Data Diri (Tanpa Akses Ganti Password) -->
            <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-700/60">
                <div class="mb-5">
                    <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tight flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Ubah Kontak & Data Diri</span>
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Perbarui nama lengkap dan nomor kontak WhatsApp aktif Anda.</p>
                </div>

                <form method="POST" action="{{ route('kasir.reports.update-profile') }}" enctype="multipart/form-data" class="space-y-4" x-data="{
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
                    @method('PUT')

                    <!-- Upload Foto Profil Kasir -->
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700/80 rounded-2xl">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Foto Profil Karyawan</label>
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
                                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-500/20 to-amber-500/10 ring-1 ring-amber-500/40 flex items-center justify-center text-amber-500 font-black text-lg">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                        @endif
                                    </div>
                                </template>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="avatar" id="kasir_avatar" x-ref="photo" @change="updatePhotoPreview" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <button type="button" @click="$refs.photo.click()" class="px-3.5 py-1.5 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-600 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
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
                            <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap Karyawan *</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs sm:text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            @error('name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nomor WhatsApp / HP *</label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" required
                                   inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs sm:text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 font-mono">
                            @error('phone')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Keterangan Password Terkunci -->
                        <div class="sm:col-span-2 p-3.5 bg-slate-50 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-700/80 rounded-xl flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <div class="text-xs text-slate-600 dark:text-slate-400">
                                <span class="font-bold text-slate-800 dark:text-slate-200 block">Akses Penggantian Password Terkunci</span>
                                Kata sandi dan email akun kasir dikelola secara terpusat oleh Administrator demi keamanan sistem. Hubungi Administrator jika membutuhkan perubahan kata sandi.
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-2">
                        <button type="submit"
                                class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs sm:text-sm rounded-xl shadow-md transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Simpan Perubahan Data Diri</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Pedoman Kerahasiaan & Keamanan Akun -->
        <div class="bg-amber-500/10 border border-amber-500/20 rounded-2xl p-4 sm:p-5 flex items-start gap-3.5">
            <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-xs text-slate-700 dark:text-slate-300">
                <span class="font-bold text-amber-500 block mb-0.5">Pedoman Akun Karyawan Kasir</span>
                Akun kasir ini digunakan untuk melayani operasional antrean dan pesanan salon. Jagalah kerahasiaan kata sandi Anda dan pastikan selalu melakukan *Logout* setelah shift pelayanan selesai.
            </div>
        </div>
    </div>
</x-kasir-layout>
