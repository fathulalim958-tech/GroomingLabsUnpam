<x-guest-layout :wide="true">
    @php
        $resolvedLoginContent = $loginContent ?? null;
        if (! $resolvedLoginContent) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('category_contents')) {
                    $resolvedLoginContent = \App\Models\CategoryContent::where('category', 'login')->first();
                }
            } catch (\Throwable $e) {
                $resolvedLoginContent = null;
            }
        }
        $loginContent = $resolvedLoginContent;
        $loginBgImage = ($loginContent && $loginContent->banner_image)
            ? asset('storage/' . $loginContent->banner_image)
            : asset('images/hero-barber-chair.png');
    @endphp

    @if (!empty($previewMode))
        <!-- Floating Top Admin Navigation Pill -->
        <div class="fixed top-4 left-4 z-50 flex items-center gap-2.5 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md px-4 py-2.5 rounded-full shadow-[0_8px_25px_rgba(11,45,80,0.14)] dark:shadow-2xl border border-blue-200 dark:border-blue-900/60 text-xs">
            <span class="flex h-2.5 w-2.5 relative flex-shrink-0">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
            </span>
            <span class="font-bold text-slate-800 dark:text-slate-100 hidden sm:inline">Pratinjau Halaman Login</span>
            <span class="text-slate-300 dark:text-slate-700 hidden sm:inline">|</span>
            <a href="{{ route('admin.category-contents.index') }}" class="font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:underline flex items-center gap-1.5 transition flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali ke Kelola Konten</span>
            </a>
        </div>
    @endif

    <div class="w-full bg-white dark:bg-slate-900 rounded-[28px] sm:rounded-[32px] overflow-hidden shadow-[0_20px_50px_rgba(11,45,80,0.12)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-[#E2EAF4] dark:border-slate-800 grid grid-cols-1 md:grid-cols-12">

        <!-- Left Column: Branded Hero Showcase -->
        <div class="md:col-span-7 relative flex flex-col justify-between p-7 sm:p-8 lg:p-9 overflow-hidden bg-[#082747] text-white min-h-[460px] md:min-h-[520px]">
            <!-- Background Image (Barbershop Interior & Chair) -->
            <div class="absolute inset-0 bg-cover bg-no-repeat bg-right" style="background-image: url('{{ $loginBgImage }}');"></div>

            <!-- Gradient Overlays for Readability & Visual Depth -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#082747] via-[#082747]/90 to-[#0B2D50]/55"></div>

            <!-- Abstract Wave Glow Decorations (Matching Mockup Curves) -->
            <div class="absolute -bottom-14 -left-14 w-72 h-72 rounded-full bg-[#1769AA]/35 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-8 right-0 w-80 h-44 bg-gradient-to-t from-[#1769AA]/45 via-[#60A5FA]/20 to-transparent blur-2xl pointer-events-none"></div>

            <!-- Bottom Soft Wave SVG Curve Overlay -->
            <div class="absolute bottom-0 inset-x-0 h-20 pointer-events-none opacity-40">
                <svg class="w-full h-full" viewBox="0 0 500 100" preserveAspectRatio="none" fill="none">
                    <path d="M0,40 C150,90 350,10 500,60 L500,100 L0,100 Z" fill="url(#wave-gradient)"></path>
                    <defs>
                        <linearGradient id="wave-gradient" x1="0" y1="0" x2="1" y2="0">
                            <stop offset="0%" stop-color="#1769AA" stop-opacity="0.6"/>
                            <stop offset="100%" stop-color="#60A5FA" stop-opacity="0.3"/>
                        </linearGradient>
                    </defs>
                </svg>
            </div>

            <!-- Content Container -->
            <div class="relative z-10 flex flex-col justify-between h-full space-y-6">
                <!-- Top Header: Logo + App Name -->
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="LPKKS Grooming Lab" class="w-10 h-10 object-contain rounded-full bg-white/10 p-0.5 border border-white/25 shadow-sm" />
                    <div class="h-5 w-px bg-white/30"></div>
                    <span class="text-xs sm:text-sm font-bold tracking-wider text-white uppercase">LPKKS GROOMING LAB UNPAM</span>
                </div>

                <!-- Center Content: Title, Subtitle & Tagline -->
                <div class="my-auto py-3">
                    <h1 class="text-3xl sm:text-4xl lg:text-[40px] font-extrabold text-white tracking-tight leading-tight">
                        @if ($loginContent && $loginContent->title)
                            @php
                                $words = explode(' ', trim($loginContent->title));
                                $firstPart = count($words) > 1 ? implode(' ', array_slice($words, 0, -1)) : $words[0];
                                $lastWord = count($words) > 1 ? end($words) : '';
                            @endphp
                            @if ($lastWord)
                                {{ $firstPart }} <span class="text-[#60A5FA]">{{ $lastWord }}</span>
                            @else
                                {{ $firstPart }}
                            @endif
                        @else
                            Selamat <span class="text-[#60A5FA]">Datang</span>
                        @endif
                    </h1>
                    <p class="text-xs sm:text-sm text-blue-100/90 font-normal mt-2 max-w-sm">
                        {{ $loginContent?->description ?? 'Masuk untuk memesan layanan atau mengelola antrian' }}
                    </p>

                    <div class="mt-6 pt-3">
                        <div class="text-sm sm:text-base italic font-medium text-white/95">
                            {{ $loginContent?->tagline ?? 'Grooming Experience' }}
                        </div>
                        <div class="text-sm sm:text-base italic font-bold text-[#60A5FA]">
                            {{ $loginContent?->facilities ?? 'Better Together' }}
                        </div>
                    </div>
                </div>

                <!-- Bottom Features: Barbershop & MUA Studio Badges -->
                <div class="flex items-center gap-5 pt-3 border-t border-white/15">
                    <div class="flex flex-col items-center gap-1.5">
                        <div class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.828 2.828a3 3 0 11-4.243-4.243L8.757 8.757m0 0a3 3 0 114.243 4.243L12 14.121" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-white/90">Barbershop</span>
                    </div>
                    <div class="h-8 w-px bg-white/25"></div>
                    <div class="flex flex-col items-center gap-1.5">
                        <div class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-white/90">MUA Studio</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Login Card Form (Equal Margins / Padding on all 4 sides) -->
        <div class="md:col-span-5 p-6 sm:p-7 lg:p-8 flex flex-col justify-between bg-white dark:bg-slate-900 text-[#14243A] dark:text-slate-100">
            <div>
                <!-- User Icon & Title -->
                <div class="text-center mb-5">
                    <div class="w-12 h-12 rounded-full bg-[#EAF3FF] dark:bg-sky-950/60 border border-[#D7E7FA] dark:border-sky-800/40 flex items-center justify-center mx-auto mb-2 text-[#1769AA] dark:text-sky-400 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold text-[#0B2D50] dark:text-white tracking-tight">Login Account</h2>
                    <p class="text-xs text-[#64748B] dark:text-slate-400 mt-0.5">
                        {{ $loginContent?->terms ?? 'Akses akun Anda untuk melanjutkan' }}
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-3" :status="session('status')" />

                @if ($errors->any())
                    <div class="mb-3 p-2.5 bg-red-500/10 border border-red-500/30 rounded-xl text-red-500 dark:text-red-400 text-xs">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-3.5">
                    @csrf

                    <!-- Email Address / Username -->
                    <div class="space-y-1">
                        <label for="email" class="block text-xs font-semibold text-[#14243A] dark:text-slate-200">Username / Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#1769AA] dark:text-sky-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input id="email" type="text" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                class="w-full pl-9 pr-3 py-2 bg-[#F4F8FD] dark:bg-slate-800/80 border border-[#D7E7FA] dark:border-slate-700 rounded-xl text-xs sm:text-sm text-[#14243A] dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-[#1769AA] focus:border-transparent transition"
                                placeholder="admin@example.com" />
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-1" x-data="{ show: false }">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-xs font-semibold text-[#14243A] dark:text-slate-200">Kata Sandi</label>
                            @if (Route::has('password.request'))
                                <a class="text-xs font-semibold text-[#1769AA] hover:text-[#164A7A] dark:text-sky-400 transition" href="{{ route('password.request') }}">
                                    Lupa sandi?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#1769AA] dark:text-sky-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                                class="w-full pl-9 pr-9 py-2 bg-[#F4F8FD] dark:bg-slate-800/80 border border-[#D7E7FA] dark:border-slate-700 rounded-xl text-xs sm:text-sm text-[#14243A] dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-[#1769AA] focus:border-transparent transition"
                                placeholder="••••••••" />
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-[#1769AA] dark:hover:text-sky-400 transition focus:outline-none">
                                <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="w-4 h-4 rounded bg-[#F4F8FD] dark:bg-slate-800 border-[#D7E7FA] dark:border-slate-700 text-[#1769AA] focus:ring-[#1769AA] focus:ring-offset-white dark:focus:ring-offset-slate-900">
                        <label for="remember_me" class="ms-2 text-xs text-[#64748B] dark:text-slate-400 select-none cursor-pointer">Ingat saya di perangkat ini</label>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                            class="w-full py-2.5 px-4 bg-gradient-to-r from-[#0B2D50] to-[#1769AA] hover:from-[#164A7A] hover:to-[#1E88E5] text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-[#0B2D50]/20 hover:shadow-lg transition transform active:scale-[0.99] flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            <span>Masuk Sekarang</span>
                        </button>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center pt-0.5">
                        <span class="text-xs text-[#64748B] dark:text-slate-400">Belum punya akun?</span>
                        <a href="{{ route('register') }}" class="text-xs font-bold text-[#1769AA] hover:text-[#164A7A] dark:text-sky-400 ms-1 transition">
                            Daftar Pelanggan
                        </a>
                    </div>
                </form>
            </div>

            <!-- Quick Demo Accounts -->
            <div class="mt-4 pt-3.5 border-t border-[#E2EAF4] dark:border-slate-800">
                <p class="text-[10px] font-semibold uppercase tracking-wider text-[#64748B] dark:text-slate-400 text-center mb-2">Login Cepat (Akun Demo)</p>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="fillLogin('customer@example.com', 'password')"
                        class="p-2 bg-[#F4F8FD] hover:bg-[#EAF3FF] dark:bg-slate-800/80 dark:hover:bg-slate-700/80 border border-[#D7E7FA] dark:border-slate-700 rounded-xl text-center transition flex flex-col items-center justify-center group shadow-sm">
                        <svg class="w-3.5 h-3.5 text-[#1769AA] dark:text-sky-400 mb-1 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="block font-bold text-xs text-[#0B2D50] dark:text-white">Pelanggan</span>
                        <span class="text-[10px] text-[#64748B] dark:text-slate-400">John Cust</span>
                    </button>
                    <button type="button" onclick="fillLogin('kasir@barbershop.com', 'password')"
                        class="p-2 bg-[#F4F8FD] hover:bg-[#EAF3FF] dark:bg-slate-800/80 dark:hover:bg-slate-700/80 border border-[#D7E7FA] dark:border-slate-700 rounded-xl text-center transition flex flex-col items-center justify-center group shadow-sm">
                        <svg class="w-3.5 h-3.5 text-[#1769AA] dark:text-sky-400 mb-1 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.828 2.828a3 3 0 11-4.243-4.243L8.757 8.757m0 0a3 3 0 114.243 4.243L12 14.121" />
                        </svg>
                        <span class="block font-bold text-xs text-[#0B2D50] dark:text-white">Kasir</span>
                        <span class="text-[10px] text-[#64748B] dark:text-slate-400">Kasir Utama</span>
                    </button>
                    <button type="button" onclick="fillLogin('admin', 'password')"
                        class="p-2 bg-[#F4F8FD] hover:bg-[#EAF3FF] dark:bg-slate-800/80 dark:hover:bg-slate-700/80 border border-[#D7E7FA] dark:border-slate-700 rounded-xl text-center transition flex flex-col items-center justify-center group shadow-sm">
                        <svg class="w-3.5 h-3.5 text-[#1769AA] dark:text-sky-400 mb-1 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span class="block font-bold text-xs text-[#0B2D50] dark:text-white">Admin</span>
                        <span class="text-[10px] text-[#64748B] dark:text-slate-400">admin</span>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        function fillLogin(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</x-guest-layout>
