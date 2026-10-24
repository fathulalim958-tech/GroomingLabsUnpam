<x-guest-layout title="Buat Kata Sandi Baru - Grooming Lab & MUA">
    <div class="mb-6">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-xs font-bold mb-3">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>Verifikasi Berhasil</span>
        </div>
        <h1 class="text-xl font-black text-slate-900 dark:text-white">Buat Kata Sandi Baru</h1>
        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
            Silakan masukkan kata sandi baru untuk akun <strong class="text-slate-800 dark:text-slate-200">{{ $email ?? ($request->email ?? '') }}</strong>.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.reset.store') }}" class="space-y-4">
        @csrf

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                Kata Sandi Baru
            </label>
            <input id="password" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500"
                   type="password" name="password" required autofocus autocomplete="new-password"
                   placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                Konfirmasi Kata Sandi Baru
            </label>
            <input id="password_confirmation" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500"
                   type="password" name="password_confirmation" required autocomplete="new-password"
                   placeholder="Ulangi kata sandi baru" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="w-full py-2.5 px-4 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-emerald-500/25 transition">
                Simpan Kata Sandi Baru
            </button>
        </div>
    </form>
</x-guest-layout>
