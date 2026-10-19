<x-pelanggan-layout title="Profil Saya - Grooming Lab & MUA">
    <div class="max-w-xl mx-auto py-4 space-y-6">
        <div>
            <h1 class="text-2xl font-extrabold text-white">Profil Saya</h1>
            <p class="text-xs text-slate-400 mt-0.5">Kelola informasi kontak dan keamanan akun Anda</p>
        </div>

        @if (session('status') === 'profile-updated')
            <div class="p-3 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-400 text-xs">
                Informasi profil berhasil diperbarui.
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="p-3 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-400 text-xs">
                Kata sandi berhasil diubah.
            </div>
        @endif

        <!-- Profile Information Form (FR-CUST-09) -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-5 sm:p-6 shadow-xl">
            <h2 class="text-base font-bold text-white mb-1">Informasi Pribadi</h2>
            <p class="text-xs text-slate-400 mb-5">Perbarui nama lengkap, nomor WhatsApp, dan alamat email Anda.</p>

            <form method="post" action="{{ route('pelanggan.profile.update') }}" class="space-y-4">
                @csrf
                @method('patch')

                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-300 mb-1">Nama Lengkap</label>
                    <input id="name" name="name" type="text" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500"
                           value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                    @error('name')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-xs font-semibold text-slate-300 mb-1">Nomor Telepon / WhatsApp</label>
                    <input id="phone" name="phone" type="tel" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500"
                           value="{{ old('phone', $user->phone) }}" required autocomplete="tel" />
                    @error('phone')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 mb-1">Alamat Email</label>
                    <input id="email" name="email" type="email" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500"
                           value="{{ old('email', $user->email) }}" required autocomplete="username" />
                    @error('email')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs rounded-xl transition shadow">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Update Password Form -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-5 sm:p-6 shadow-xl">
            <h2 class="text-base font-bold text-white mb-1">Ubah Kata Sandi</h2>
            <p class="text-xs text-slate-400 mb-5">Pastikan akun Anda menggunakan kata sandi yang kuat dan aman.</p>

            <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                @method('put')

                <div>
                    <label for="update_password_current_password" class="block text-xs font-semibold text-slate-300 mb-1">Kata Sandi Saat Ini</label>
                    <input id="update_password_current_password" name="current_password" type="password" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500" autocomplete="current-password" />
                    @error('current_password', 'updatePassword')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="update_password_password" class="block text-xs font-semibold text-slate-300 mb-1">Kata Sandi Baru</label>
                    <input id="update_password_password" name="password" type="password" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500" autocomplete="new-password" />
                    @error('password', 'updatePassword')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="update_password_password_confirmation" class="block text-xs font-semibold text-slate-300 mb-1">Konfirmasi Kata Sandi Baru</label>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500" autocomplete="new-password" />
                    @error('password_confirmation', 'updatePassword')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs rounded-xl transition shadow">
                        Perbarui Kata Sandi
                    </button>
                </div>
            </form>
        </div>

        <!-- Logout Section -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-5 text-center">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-3 px-4 bg-red-500/10 hover:bg-red-500/20 text-red-400 font-bold text-xs rounded-xl border border-red-500/30 transition">
                    Keluar dari Akun
                </button>
            </form>
        </div>
    </div>
</x-pelanggan-layout>

