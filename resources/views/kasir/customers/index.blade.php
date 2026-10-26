<x-kasir-layout title="Kelola Pelanggan - Grooming Lab">
    <div class="space-y-6" x-data="customerKasirManager()">
        <!-- Header Banner -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-5 shadow-sm">
            <div>
                <h1 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                    KELOLA DATA PELANGGAN
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Database pelanggan yang telah membuat akun maupun pelanggan walk-in salon.
                </p>
            </div>

            <!-- Tab Switcher -->
            <div class="w-full sm:w-auto grid grid-cols-2 gap-1.5 bg-slate-100 dark:bg-slate-900 p-1 sm:p-1.5 rounded-xl border border-slate-200 dark:border-slate-700">
                <a href="{{ route('kasir.customers.index', ['tab' => 'registered']) }}"
                   class="px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-bold transition flex items-center justify-center gap-1.5 {{ $tab === 'registered' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                    <span>Terdaftar</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'registered' ? 'bg-blue-800 text-white' : 'bg-blue-500/20 text-blue-600 dark:text-blue-300 font-bold' }}">
                        {{ $totalRegistered }}
                    </span>
                </a>

                <a href="{{ route('kasir.customers.index', ['tab' => 'walkin']) }}"
                   class="px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-bold transition flex items-center justify-center gap-1.5 {{ $tab === 'walkin' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                    <span>Walk-In</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'walkin' ? 'bg-blue-800 text-white' : 'bg-blue-500/20 text-blue-600 dark:text-blue-300 font-bold' }}">
                        {{ $totalWalkIns }}
                    </span>
                </a>
            </div>
        </div>

        <!-- Search Toolbar -->
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-4 sm:p-5 shadow-sm">
            <form method="GET" action="{{ route('kasir.customers.index') }}" class="flex flex-wrap items-center gap-3">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="flex-1 min-w-[240px]">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, atau no telepon pelanggan..."
                           class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs sm:text-sm rounded-xl shadow transition">
                    Cari Pelanggan
                </button>
                @if (!empty($search))
                    <a href="{{ route('kasir.customers.index', ['tab' => $tab]) }}" class="text-xs text-sky-500 dark:text-sky-400 font-bold hover:underline px-2">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table Customers -->
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-xl sm:rounded-2xl overflow-hidden shadow-lg">
            <!-- Mobile View: Kartu Pelanggan di HP -->
            <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-700/70">
                @forelse ($customers as $c)
                    @php
                        $lastDate = $tab === 'registered' ? $c->bookings_max_booking_date : $c->last_booking_date;
                    @endphp
                    <div class="p-3.5 space-y-2.5">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <div class="font-bold text-sm text-slate-900 dark:text-white">
                                    {{ $tab === 'registered' ? $c->name : $c->customer_name }}
                                </div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">
                                    {{ $tab === 'registered' ? ($c->phone ?? '-') : ($c->customer_phone ?? '-') }}
                                </div>
                                @if ($tab === 'registered' && $c->email)
                                    <div class="text-[11px] text-slate-400 truncate max-w-[200px]">{{ $c->email }}</div>
                                @endif
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20 whitespace-nowrap">
                                    {{ $tab === 'registered' ? $c->bookings_count : $c->total_orders }} Pesanan
                                </span>
                                @if ($tab === 'registered')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $c->is_active ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/15 text-rose-600 dark:text-rose-400 border border-rose-500/30' }}">
                                        {{ $c->is_active ? 'Aktif' : 'Diblokir' }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-1 text-xs border-t border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500 dark:text-slate-400 text-[11px]">
                                Terakhir: {{ $lastDate ? \Carbon\Carbon::parse($lastDate)->translatedFormat('d M Y') : 'Belum Pernah' }}
                            </span>
                            @if ($tab === 'registered')
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <a href="{{ route('kasir.customers.show', $c) }}"
                                       class="px-2.5 py-1 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-lg transition inline-flex items-center gap-1 active:scale-95">
                                        <span>Riwayat</span> &rarr;
                                    </a>
                                    <button type="button"
                                            @click="openBlockModal({{ json_encode($c) }}, '{{ route('kasir.customers.toggle-block', $c) }}')"
                                            class="px-2 py-1 text-xs font-bold rounded-lg border transition {{ $c->is_active ? 'border-amber-500/40 text-amber-600 dark:text-amber-400' : 'border-emerald-500/40 text-emerald-600 dark:text-emerald-400' }}">
                                        {{ $c->is_active ? 'Blokir' : 'Buka' }}
                                    </button>
                                    <button type="button"
                                            @click="openResetPasswordModal({{ json_encode($c) }}, '{{ route('kasir.customers.reset-password', $c) }}')"
                                            class="px-2 py-1 text-xs font-bold rounded-lg border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300">
                                        Reset Pass
                                    </button>
                                </div>
                            @else
                                <span class="text-[10px] text-slate-400 font-semibold bg-slate-100 dark:bg-slate-750 px-2 py-0.5 rounded">
                                    Pelanggan Walk-In
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center text-slate-500 dark:text-slate-400 px-4">
                        <div class="text-3xl">👥</div>
                        <p class="font-bold text-sm text-slate-700 dark:text-slate-300 mt-2">Data pelanggan tidak ditemukan.</p>
                    </div>
                @endforelse
            </div>

            <!-- Desktop View: Tabel Lengkap -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-200 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-black text-xs uppercase tracking-wider border-b border-slate-300 dark:border-slate-800">
                        <tr>
                            <th class="py-4 px-4 font-black">Nama Pelanggan</th>
                            <th class="py-4 px-4 font-black">Kontak / No. HP</th>
                            @if ($tab === 'registered')
                                <th class="py-4 px-4 font-black">Email</th>
                                <th class="py-4 px-4 font-black text-center">Status Akun</th>
                            @endif
                            <th class="py-4 px-4 font-black text-center">Total Order</th>
                            <th class="py-4 px-4 font-black">Kunjungan Terakhir</th>
                            <th class="py-4 px-4 font-black text-center">Aksi / Kontrol</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/70">
                        @forelse ($customers as $c)
                            <tr class="hover:bg-slate-100/70 dark:hover:bg-slate-700/60 transition">
                                <td class="py-4 px-4 font-bold text-base text-slate-900 dark:text-white">
                                    {{ $tab === 'registered' ? $c->name : $c->customer_name }}
                                </td>

                                <td class="py-4 px-4 font-mono text-sm text-slate-700 dark:text-slate-300 font-medium">
                                    {{ $tab === 'registered' ? ($c->phone ?? '-') : ($c->customer_phone ?? '-') }}
                                </td>

                                @if ($tab === 'registered')
                                    <td class="py-4 px-4 text-xs text-slate-600 dark:text-slate-300">
                                        {{ $c->email }}
                                    </td>
                                    <td class="py-4 px-4 text-center whitespace-nowrap">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold whitespace-nowrap inline-block {{ $c->is_active ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/30' }}">
                                            {{ $c->is_active ? 'Aktif' : 'Diblokir' }}
                                        </span>
                                    </td>
                                @endif

                                <td class="py-4 px-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20 whitespace-nowrap">
                                        {{ $tab === 'registered' ? $c->bookings_count : $c->total_orders }} Pesanan
                                    </span>
                                </td>

                                <td class="py-4 px-4 text-xs font-mono text-slate-700 dark:text-slate-300 font-semibold whitespace-nowrap">
                                    @php
                                        $lastDate = $tab === 'registered' ? $c->bookings_max_booking_date : $c->last_booking_date;
                                    @endphp
                                    {{ $lastDate ? \Carbon\Carbon::parse($lastDate)->translatedFormat('d M Y') : 'Belum Pernah' }}
                                </td>

                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    @if ($tab === 'registered')
                                        <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                            <!-- Riwayat Order -->
                                            <a href="{{ route('kasir.customers.show', $c) }}"
                                               class="px-2.5 py-1 text-xs rounded-lg bg-blue-500/10 hover:bg-blue-500/20 text-blue-600 dark:text-blue-400 border border-blue-500/30 font-bold transition flex items-center gap-1"
                                               title="Lihat riwayat booking pelanggan">
                                                <span>Riwayat</span> &rarr;
                                            </a>

                                            <!-- Tombol Blokir / Buka Blokir dengan Pop-up Konfirmasi -->
                                            <button type="button"
                                                    @click="openBlockModal({{ json_encode($c) }}, '{{ route('kasir.customers.toggle-block', $c) }}')"
                                                    class="px-2.5 py-1 text-xs font-bold rounded-lg border transition flex items-center gap-1 {{ $c->is_active ? 'border-amber-500/30 text-amber-600 dark:text-amber-400 hover:bg-amber-500/10' : 'border-emerald-500/30 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500/10' }}"
                                                    title="{{ $c->is_active ? 'Blokir akun pelanggan ini' : 'Buka blokir akun pelanggan ini' }}">
                                                <span>{{ $c->is_active ? '🔒 Blokir' : '🔓 Buka Blokir' }}</span>
                                            </button>

                                            <!-- Tombol Reset Password dengan Modal Input Password Baru -->
                                            <button type="button"
                                                    @click="openResetPasswordModal({{ json_encode($c) }}, '{{ route('kasir.customers.reset-password', $c) }}')"
                                                    class="px-2.5 py-1 text-xs font-bold rounded-lg border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition flex items-center gap-1"
                                                    title="Setel password baru pelanggan">
                                                <span>🔑 Reset Pass</span>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 font-medium">Walk-in</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $tab === 'registered' ? 7 : 5 }}" class="py-12 text-center text-slate-500 dark:text-slate-400">
                                    <div class="max-w-xs mx-auto space-y-2">
                                        <div class="text-3xl">👥</div>
                                        <p class="font-bold text-sm text-slate-700 dark:text-slate-300">Data pelanggan tidak ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($customers->hasPages())
                <div class="px-5 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>

        <!-- ============================================================ -->
        <!-- 1. MODAL POP-UP KONFIRMASI BLOKIR / BUKA BLOKIR PELANGGAN     -->
        <!-- ============================================================ -->
        <div x-show="blockModal.open"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             role="dialog"
             aria-modal="true"
             style="display: none;">
            <!-- Backdrop Blur -->
            <div x-show="blockModal.open"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="blockModal.open = false"
                 class="fixed inset-0 bg-slate-950/75 backdrop-blur-sm transition-opacity"></div>

            <div class="min-h-full flex items-center justify-center p-4">
                <div x-show="blockModal.open"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.away="blockModal.open = false"
                     class="relative bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-md p-6 space-y-5">

                    <!-- Header Modal -->
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0 shadow-inner"
                             :class="blockModal.isActive ? 'bg-rose-500/10 text-rose-500 border border-rose-500/30' : 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/30'">
                            <span x-text="blockModal.isActive ? '🔒' : '🔓'"></span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-base font-black text-slate-900 dark:text-white"
                                x-text="blockModal.isActive ? 'Konfirmasi Blokir Pelanggan' : 'Konfirmasi Buka Blokir Akun'">
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Verifikasi tindakan kontrol akun pelanggan
                            </p>
                        </div>
                    </div>

                    <!-- Detail Pelanggan Box -->
                    <div class="p-3.5 bg-slate-50 dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700/80 space-y-1.5">
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Akun Pelanggan:</div>
                        <div class="font-black text-slate-900 dark:text-white text-sm" x-text="blockModal.name"></div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 font-mono" x-text="blockModal.email"></div>
                    </div>

                    <!-- Pesan Peringatan -->
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed"
                       x-text="blockModal.isActive
                           ? 'Apakah Anda yakin ingin memblokir akun ini? Pelanggan tidak akan dapat masuk (login) ke sistem dan tidak dapat membuat reservasi baru selama akun diblokir.'
                           : 'Apakah Anda yakin ingin membuka blokir akun ini? Akun pelanggan akan dipulihkan dan dapat kembali masuk ke sistem serta melakukan reservasi secara normal.'">
                    </p>

                    <!-- Tombol Aksi -->
                    <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="blockModal.open = false"
                                class="w-full py-2.5 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl transition">
                            Batal
                        </button>
                        <form method="POST" :action="blockModal.actionUrl" class="w-full">
                            @csrf
                            <button type="submit"
                                    class="w-full py-2.5 px-4 text-white font-black text-xs rounded-xl shadow-lg transition active:scale-95 flex items-center justify-center gap-1.5"
                                    :class="blockModal.isActive ? 'bg-rose-600 hover:bg-rose-500 shadow-rose-600/30' : 'bg-emerald-600 hover:bg-emerald-500 shadow-emerald-600/30'">
                                <span x-text="blockModal.isActive ? 'Ya, Blokir Akun' : 'Ya, Buka Blokir'"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- 2. MODAL INPUT PASSWORD BARU (RESET PASSWORD PELANGGAN)       -->
        <!-- ============================================================ -->
        <div x-show="resetPasswordModal.open"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             role="dialog"
             aria-modal="true"
             style="display: none;">
            <!-- Backdrop Blur -->
            <div x-show="resetPasswordModal.open"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="resetPasswordModal.open = false"
                 class="fixed inset-0 bg-slate-950/75 backdrop-blur-sm transition-opacity"></div>

            <div class="min-h-full flex items-center justify-center p-4">
                <div x-show="resetPasswordModal.open"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.away="resetPasswordModal.open = false"
                     class="relative bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-lg p-6 space-y-5">

                    <!-- Header Modal -->
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-blue-500/10 text-blue-500 border border-blue-500/30 flex items-center justify-center text-lg shadow-inner">
                                🔑
                            </div>
                            <div>
                                <h3 class="text-base font-black text-slate-900 dark:text-white">Reset Password Pelanggan</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Buat password baru secara langsung melalui inputan</p>
                            </div>
                        </div>
                        <button type="button" @click="resetPasswordModal.open = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <!-- Target Customer Info -->
                    <div class="p-3 bg-slate-50 dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700/80 flex items-center justify-between">
                        <div>
                            <div class="text-[10px] uppercase font-bold text-slate-500 dark:text-slate-400 tracking-wider">Pelanggan Dituju:</div>
                            <div class="font-extrabold text-sm text-slate-900 dark:text-white" x-text="resetPasswordModal.name"></div>
                            <div class="text-xs text-slate-500 dark:text-slate-400 font-mono" x-text="resetPasswordModal.email"></div>
                        </div>
                        <button type="button"
                                @click="generateRandomPassword()"
                                class="px-3 py-1.5 bg-amber-500/10 hover:bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/30 rounded-xl text-xs font-bold transition flex items-center gap-1 active:scale-95 shadow-sm"
                                title="Buat password acak otomatis">
                            <span>⚡ Acak Password</span>
                        </button>
                    </div>

                    <!-- Form Reset Password -->
                    <form method="POST" :action="resetPasswordModal.actionUrl" class="space-y-4">
                        @csrf
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                    Password Baru <span class="text-rose-500">*</span>
                                </label>
                                <button type="button"
                                        @click="resetPasswordModal.showPassword = !resetPasswordModal.showPassword"
                                        class="text-[11px] text-blue-600 dark:text-blue-400 hover:underline font-semibold flex items-center gap-1">
                                    <span x-text="resetPasswordModal.showPassword ? 'Sembunyikan' : 'Tampilkan'"></span>
                                </button>
                            </div>
                            <input :type="resetPasswordModal.showPassword ? 'text' : 'password'"
                                   name="password"
                                   x-model="resetPasswordModal.password"
                                   required
                                   minlength="6"
                                   placeholder="Masukkan password baru (min. 6 karakter)"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 font-mono" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                Konfirmasi Password Baru <span class="text-rose-500">*</span>
                            </label>
                            <input :type="resetPasswordModal.showPassword ? 'text' : 'password'"
                                   name="password_confirmation"
                                   x-model="resetPasswordModal.password_confirmation"
                                   required
                                   minlength="6"
                                   placeholder="Ulangi password baru yang sama"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 font-mono" />
                        </div>

                        <div class="p-3 bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-900/50 rounded-xl text-[11px] text-blue-800 dark:text-blue-300 leading-relaxed">
                            💡 <strong>Catatan:</strong> Password ini akan langsung aktif. Anda dapat menginformasikan password baru ini kepada pelanggan setelah berhasil di-reset.
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="resetPasswordModal.open = false"
                                    class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition">
                                Batal
                            </button>
                            <button type="submit"
                                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-black text-xs rounded-xl shadow-lg shadow-blue-600/30 transition active:scale-95 flex items-center gap-1.5">
                                <span>Simpan Password Baru</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- 3. POP-UP NOTIFIKASI BERHASIL / GAGAL                         -->
        <!-- ============================================================ -->
        @if (session('password_reset_success') || session('block_status_updated') || $errors->any())
            <div x-show="notificationModal.open"
                 x-cloak
                 class="fixed inset-0 z-50 overflow-y-auto"
                 role="dialog"
                 aria-modal="true"
                 style="display: none;">
                <!-- Backdrop Blur -->
                <div x-show="notificationModal.open"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="notificationModal.open = false"
                     class="fixed inset-0 bg-slate-950/75 backdrop-blur-sm transition-opacity"></div>

                <div class="min-h-full flex items-center justify-center p-4">
                    <div x-show="notificationModal.open"
                         x-transition:enter="ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         @click.away="notificationModal.open = false"
                         class="relative bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 text-center overflow-hidden shadow-2xl transform transition-all w-full max-w-md p-6 space-y-5">

                        @if (session('password_reset_success'))
                            <!-- NOTIFIKASI SUKSES RESET PASSWORD -->
                            <div class="w-16 h-16 rounded-3xl bg-emerald-500/15 border-2 border-emerald-500/30 text-emerald-500 flex items-center justify-center text-3xl mx-auto shadow-inner">
                                ✓
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900 dark:text-white">Password Berhasil Diperbarui!</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    Kata sandi baru untuk akun pelanggan telah aktif dan siap digunakan.
                                </p>
                            </div>

                            <div class="bg-slate-50 dark:bg-slate-800/80 rounded-2xl p-4 border border-slate-200 dark:border-slate-700/80 text-left space-y-2">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-slate-500 dark:text-slate-400 font-medium">Pelanggan:</span>
                                    <strong class="text-slate-900 dark:text-white">{{ session('password_reset_success.customer_name') }}</strong>
                                </div>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-slate-500 dark:text-slate-400 font-medium">Email:</span>
                                    <span class="font-mono text-slate-600 dark:text-slate-300 text-[11px]">{{ session('password_reset_success.customer_email') }}</span>
                                </div>
                                <div class="pt-2 border-t border-slate-200 dark:border-slate-700">
                                    <div class="text-[10px] uppercase font-bold text-slate-500 dark:text-slate-400 mb-1">Password Baru:</div>
                                    <div class="flex items-center justify-between bg-white dark:bg-slate-900 px-3 py-2 rounded-xl border border-emerald-500/40">
                                        <span class="font-mono font-black text-base text-emerald-600 dark:text-emerald-400 tracking-wider">
                                            {{ session('password_reset_success.new_password') }}
                                        </span>
                                        <button type="button"
                                                @click="copyPassword('{{ session('password_reset_success.new_password') }}')"
                                                class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 transition flex items-center gap-1 active:scale-95">
                                            <span x-text="notificationModal.copied ? '✓ Tersalin!' : '📋 Salin'"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <button type="button" @click="notificationModal.open = false"
                                    class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs rounded-xl shadow-lg shadow-emerald-600/30 transition active:scale-95">
                                Selesai & Tutup
                            </button>

                        @elseif (session('block_status_updated'))
                            <!-- NOTIFIKASI SUKSES UPDATE STATUS BLOKIR -->
                            <div class="w-16 h-16 rounded-3xl bg-blue-500/15 border-2 border-blue-500/30 text-blue-500 flex items-center justify-center text-3xl mx-auto shadow-inner">
                                🛡️
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900 dark:text-white">Status Akun Diperbarui</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    {{ session('block_status_updated.message') }}
                                </p>
                            </div>

                            <button type="button" @click="notificationModal.open = false"
                                    class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-500 text-white font-black text-xs rounded-xl shadow-lg shadow-blue-600/30 transition active:scale-95">
                                Tutup
                            </button>

                        @elseif ($errors->any())
                            <!-- NOTIFIKASI GAGAL (VALIDASI / ERROR) -->
                            <div class="w-16 h-16 rounded-3xl bg-rose-500/15 border-2 border-rose-500/30 text-rose-500 flex items-center justify-center text-3xl mx-auto shadow-inner">
                                ⚠️
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900 dark:text-white">Gagal Memproses Permintaan</h3>
                                <p class="text-xs text-rose-600 dark:text-rose-400 mt-1">
                                    Silakan periksa kembali formulir input Anda:
                                </p>
                            </div>

                            <div class="bg-rose-50 dark:bg-rose-950/30 rounded-2xl p-4 border border-rose-200 dark:border-rose-900/50 text-left space-y-1.5 text-xs text-rose-700 dark:text-rose-300">
                                @foreach ($errors->all() as $err)
                                    <div class="flex items-start gap-1.5">
                                        <span class="text-rose-500">•</span>
                                        <span>{{ $err }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" @click="notificationModal.open = false"
                                    class="w-full py-2.5 px-4 bg-slate-800 hover:bg-slate-700 text-white font-black text-xs rounded-xl shadow transition active:scale-95">
                                Tutup & Coba Lagi
                            </button>
                        @endif

                    </div>
                </div>
            </div>
        @endif

    </div>

    @push('scripts')
    <script>
        function customerKasirManager() {
            return {
                blockModal: {
                    open: false,
                    actionUrl: '',
                    name: '',
                    email: '',
                    isActive: true,
                },
                openBlockModal(customer, actionUrl) {
                    this.blockModal = {
                        open: true,
                        actionUrl: actionUrl,
                        name: customer.name || customer.customer_name,
                        email: customer.email || '-',
                        isActive: Boolean(customer.is_active),
                    };
                },
                resetPasswordModal: {
                    open: false,
                    actionUrl: '',
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    showPassword: false,
                },
                openResetPasswordModal(customer, actionUrl) {
                    this.resetPasswordModal = {
                        open: true,
                        actionUrl: actionUrl,
                        name: customer.name || customer.customer_name,
                        email: customer.email || '-',
                        password: '',
                        password_confirmation: '',
                        showPassword: false,
                    };
                },
                generateRandomPassword() {
                    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%';
                    let pass = 'GL#';
                    for (let i = 0; i < 5; i++) {
                        pass += chars.charAt(Math.floor(Math.random() * chars.length));
                    }
                    this.resetPasswordModal.password = pass;
                    this.resetPasswordModal.password_confirmation = pass;
                    this.resetPasswordModal.showPassword = true;
                },
                notificationModal: {
                    open: {{ (session('password_reset_success') || session('block_status_updated') || $errors->any()) ? 'true' : 'false' }},
                    copied: false,
                },
                copyPassword(text) {
                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(text).then(() => {
                            this.notificationModal.copied = true;
                            setTimeout(() => this.notificationModal.copied = false, 2500);
                        });
                    } else {
                        const textArea = document.createElement("textarea");
                        textArea.value = text;
                        document.body.appendChild(textArea);
                        textArea.select();
                        document.execCommand("copy");
                        textArea.remove();
                        this.notificationModal.copied = true;
                        setTimeout(() => this.notificationModal.copied = false, 2500);
                    }
                }
            }
        }
    </script>
    @endpush
</x-kasir-layout>
