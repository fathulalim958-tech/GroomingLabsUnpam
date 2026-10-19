<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-white tracking-tight">Selamat Datang</h2>
        <p class="text-sm text-slate-400 mt-1">Masuk untuk memesan layanan atau mengelola antrian</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-500/10 border border-red-500/30 rounded-xl text-red-400 text-sm">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-1">Email / Username</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition"
                placeholder="nama@email.com" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block text-sm font-medium text-slate-300">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-amber-400 hover:text-amber-300 transition" href="{{ route('password.request') }}">
                        Lupa sandi?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition"
                placeholder="••••••••" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember"
                class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-amber-500 focus:ring-amber-500 focus:ring-offset-slate-900">
            <label for="remember_me" class="ms-2 text-sm text-slate-400">Ingat saya di perangkat ini</label>
        </div>

        <div>
            <button type="submit"
                class="w-full py-3 px-4 bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-slate-950 font-bold rounded-xl shadow-lg shadow-amber-500/20 transition transform active:scale-95">
                Masuk Sekarang
            </button>
        </div>

        <div class="text-center pt-2">
            <span class="text-sm text-slate-400">Belum punya akun?</span>
            <a href="{{ route('register') }}" class="text-sm font-semibold text-amber-400 hover:text-amber-300 ms-1 transition">
                Daftar Pelanggan
            </a>
        </div>
    </form>

    <!-- Quick Demo Accounts (Helper for quick testing) -->
    <div class="mt-8 pt-6 border-t border-slate-800/80">
        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 text-center mb-3">Login Cepat (Akun Demo)</p>
        <div class="grid grid-cols-3 gap-2">
            <button type="button" onclick="fillLogin('customer@example.com', 'password')"
                class="p-2 text-xs bg-slate-800/60 hover:bg-slate-800 border border-slate-700 rounded-lg text-slate-300 text-center transition">
                <span class="block font-bold text-amber-400">Pelanggan</span>
                <span class="text-[10px] text-slate-400">John Cust</span>
            </button>
            <button type="button" onclick="fillLogin('kasir@barbershop.com', 'password')"
                class="p-2 text-xs bg-slate-800/60 hover:bg-slate-800 border border-slate-700 rounded-lg text-slate-300 text-center transition">
                <span class="block font-bold text-blue-400">Kasir</span>
                <span class="text-[10px] text-slate-400">Kasir Utama</span>
            </button>
            <button type="button" onclick="fillLogin('admin@barbershop.com', 'password')"
                class="p-2 text-xs bg-slate-800/60 hover:bg-slate-800 border border-slate-700 rounded-lg text-slate-300 text-center transition">
                <span class="block font-bold text-emerald-400">Admin</span>
                <span class="text-[10px] text-slate-400">Owner</span>
            </button>
        </div>
    </div>

    <script>
        function fillLogin(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</x-guest-layout>
