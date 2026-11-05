<x-admin-layout title="Pengaturan Akun Admin - Grooming Lab & MUA">
    <div class="space-y-6 max-w-6xl mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Pengaturan Akun Administrator</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Kelola alamat Gmail resmi, username login admin, dan kata sandi keamanan akun Anda.
                </p>
            </div>
        </div>

        <!-- 2-Column Grid Form Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
            <!-- Card 1: Ganti Gmail & Username -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 sm:p-6 shadow-sm">
                <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100 dark:border-slate-800/80">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">Informasi Akun & Gmail</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Ubah email Google (Gmail) dan username akses login</p>
                    </div>
                </div>

                <!-- Info Box: Username Login -->
                <div class="mb-5 p-3.5 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-xs text-slate-700 dark:text-slate-300 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <strong class="text-emerald-700 dark:text-emerald-400">Login dengan Username:</strong>
                        Saat masuk, Anda cukup mengetikkan <strong>Username</strong> pada kolom login tanpa perlu memasukkan alamat email.
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Username Admin -->
                    <div>
                        <label for="username" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                            Username Login Admin <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                @
                            </span>
                            <input id="username"
                                   type="text"
                                   name="username"
                                   value="{{ old('username', $user->username ?? 'admin') }}"
                                   required
                                   class="w-full pl-8 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-sm font-mono font-semibold text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                   placeholder="admin" />
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Username unik yang Anda ketik saat masuk ke aplikasi.</p>
                        @error('username')
                            <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gmail / Email Admin -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                            Gmail / Email Resmi <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <input id="email"
                                   type="email"
                                   name="email"
                                   value="{{ old('email', $user->email) }}"
                                   required
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                   placeholder="admin@gmail.com" />
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Alamat Gmail aktif untuk identitas resmi dan penerimaan informasi sistem.</p>
                        @error('email')
                            <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                            Nama Lengkap Administrator <span class="text-red-500">*</span>
                        </label>
                        <input id="name"
                               type="text"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               required
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition" />
                        @error('name')
                            <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No. Telepon / WhatsApp -->
                    <div>
                        <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                            Nomor WhatsApp / Telepon
                        </label>
                        <input id="phone"
                               type="text"
                               name="phone"
                               value="{{ old('phone', $user->phone) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                               placeholder="081234567890" />
                        @error('phone')
                            <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="w-full sm:w-auto px-6 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition transform active:scale-95">
                            Simpan Perubahan Akun & Gmail
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card 2: Ganti Password -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 sm:p-6 shadow-sm">
                <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100 dark:border-slate-800/80">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">Keamanan & Password</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Perbarui kata sandi untuk melindungi akses panel admin</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.profile.password') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Password Lama -->
                    <div>
                        <label for="current_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                            Kata Sandi Saat Ini <span class="text-red-500">*</span>
                        </label>
                        <input id="current_password"
                               type="password"
                               name="current_password"
                               required
                               autocomplete="current-password"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition"
                               placeholder="••••••••" />
                        @error('current_password')
                            <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Baru -->
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                            Kata Sandi Baru <span class="text-red-500">*</span>
                        </label>
                        <input id="password"
                               type="password"
                               name="password"
                               required
                               autocomplete="new-password"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition"
                               placeholder="Minimal 8 karakter" />
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Gunakan kombinasi huruf, angka, atau simbol untuk keamanan maksimal.</p>
                        @error('password')
                            <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password Baru -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                            Ulangi Kata Sandi Baru <span class="text-red-500">*</span>
                        </label>
                        <input id="password_confirmation"
                               type="password"
                               name="password_confirmation"
                               required
                               autocomplete="new-password"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition"
                               placeholder="••••••••" />
                        @error('password_confirmation')
                            <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="w-full sm:w-auto px-6 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition transform active:scale-95">
                            Perbarui Kata Sandi Admin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>

