<x-guest-layout title="Verifikasi Kode - Grooming Lab & MUA">
    <div class="mb-6 text-center">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-500 mb-3 shadow-inner">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h1 class="text-xl font-black text-slate-900 dark:text-white">Masukkan Kode Verifikasi</h1>
        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1 max-w-sm mx-auto">
            Kami telah mengirimkan 6 digit kode ke email:
        </p>
        <span class="inline-block mt-1 font-mono font-bold text-xs text-amber-600 dark:text-amber-400 bg-amber-500/10 px-2.5 py-1 rounded-lg">
            {{ $email }}
        </span>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.verify') }}" class="space-y-5">
        @csrf

        <!-- 6-Digit Code Input -->
        <div>
            <label for="code" class="block text-center text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                6 Digit Kode OTP
            </label>
            <input id="code" type="text" name="code" value="{{ old('code') }}" required autofocus
                   maxlength="6" inputmode="numeric" pattern="[0-9]*" placeholder="000000"
                   class="w-full text-center text-2xl font-mono font-black tracking-[0.5em] px-4 py-3 bg-slate-50 dark:bg-slate-800/80 border-2 border-slate-300 dark:border-slate-700 rounded-2xl text-slate-900 dark:text-white focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/20 transition shadow-inner">
            <x-input-error :messages="$errors->get('code')" class="mt-2 text-center" />
            <p class="text-[11px] text-center text-slate-500 dark:text-slate-400 mt-1.5">
                Kode berlaku selama 15 menit.
            </p>
        </div>

        <div class="space-y-2.5 pt-1">
            <button type="submit"
                    class="w-full py-2.5 px-4 bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-amber-500/25 transition">
                Verifikasi & Lanjutkan
            </button>
        </div>
    </form>

    <div class="mt-6 pt-5 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between text-xs">
        <a href="{{ route('password.request') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white transition">
            &larr; Ganti Email
        </a>

        <form method="POST" action="{{ route('password.resend') }}">
            @csrf
            <button type="submit" class="font-bold text-amber-600 dark:text-amber-400 hover:underline">
                Kirim Ulang Kode
            </button>
        </form>
    </div>
</x-guest-layout>

