<x-admin-layout title="Kelola Pelanggan - Grooming Lab & MUA">
    <div class="space-y-6" x-data="customerAdminManager()">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Data Pelanggan & Member</h1>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Daftar pelanggan terdaftar, keanggotaan member, diskon potongan, dan kontrol akun</p>
            </div>

            <!-- Search Bar (FR-ADM-02) -->
            <form method="GET" action="{{ route('admin.customers.index') }}" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="px-4 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 w-64 shadow-sm"
                       placeholder="Cari nama, email, nomor HP..." />
                <button type="submit" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow transition">
                    Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('admin.customers.index') }}" class="text-xs text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white underline">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm dark:shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-950/60 text-xs uppercase tracking-wider text-slate-600 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="py-3 px-4">Nama Pelanggan</th>
                            <th class="py-3 px-4">Kontak</th>
                            <th class="py-3 px-4 text-center">Total Booking</th>
                            <th class="py-3 px-4">Keanggotaan Member</th>
                            <th class="py-3 px-4">Status Akun</th>
                            <th class="py-3 px-4">Bergabung</th>
                            <th class="py-3 px-4 text-center">Aksi / Kontrol</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        @forelse ($customers as $customer)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <!-- Foto Profil Pelanggan -->
                                        <div class="relative flex-shrink-0">
                                            @if($customer->avatar)
                                                <img src="{{ asset('storage/' . $customer->avatar) }}" alt="{{ $customer->name }}" class="w-10 h-10 rounded-xl object-cover ring-1 ring-amber-500/50 shadow-sm">
                                            @else
                                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500/20 via-slate-100 dark:via-slate-800 to-amber-500/10 ring-1 ring-amber-500/30 flex items-center justify-center text-amber-500 font-black text-xs shadow-sm">
                                                    {{ strtoupper(substr($customer->name, 0, 2)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                                                <span>{{ $customer->name }}</span>
                                                @if ($customer->is_member)
                                                    <span class="text-amber-500 text-xs" title="Member Aktif">⭐</span>
                                                @endif
                                            </div>
                                            <div class="text-[10px] text-slate-500 font-mono">ID: #{{ $customer->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-xs">
                                    <div class="text-slate-900 dark:text-slate-200">{{ $customer->email }}</div>
                                    <div class="text-slate-500 dark:text-slate-400 font-mono">{{ $customer->phone ?? '-' }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200">
                                        {{ $customer->bookings_count }}x
                                    </span>
                                </td>
                                <!-- Kolom Status Member & Kuota Diskon -->
                                <td class="py-3.5 px-4 text-xs">
                                    @if ($customer->is_member)
                                        <div class="space-y-0.5">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/30">
                                                ⭐ Member Aktif
                                            </span>
                                            <div class="text-[11px] text-slate-600 dark:text-slate-300 font-medium">
                                                Diskon: <strong class="text-amber-600 dark:text-amber-400 font-mono">{{ $customer->member_discount_label }}</strong>
                                            </div>
                                            <div class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">
                                                Sisa: <strong class="{{ $customer->member_discount_remaining > 0 ? 'text-emerald-500' : 'text-rose-500' }}">{{ $customer->member_discount_remaining }}</strong> / {{ $customer->member_discount_quota }}x
                                            </div>
                                        </div>
                                    @else
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">
                                            Reguler (Bukan Member)
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $customer->is_active ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30' : 'bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/30' }}">
                                        {{ $customer->is_active ? 'Aktif' : 'Diblokir' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-xs text-slate-500 dark:text-slate-400">{{ $customer->created_at->format('d M Y') }}</td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                        <!-- Tombol Atur Member -->
                                        <button type="button"
                                                @click="openMemberModal({{ json_encode($customer) }}, '{{ route('admin.customers.membership', $customer) }}')"
                                                class="px-2.5 py-1 text-xs rounded-lg font-bold border transition flex items-center gap-1 {{ $customer->is_member ? 'bg-amber-500/15 hover:bg-amber-500/25 text-amber-600 dark:text-amber-400 border-amber-500/30' : 'bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700' }}"
                                                title="Daftarkan / Kelola Diskon Member">
                                            <span>⭐</span>
                                            <span>{{ $customer->is_member ? 'Atur Member' : 'Daftar Member' }}</span>
                                        </button>

                                        <!-- Detail & History -->
                                        <a href="{{ route('admin.customers.show', $customer) }}"
                                           class="px-2.5 py-1 text-xs rounded-lg bg-blue-500/10 hover:bg-blue-500/20 text-blue-600 dark:text-blue-400 border border-blue-500/30 transition">
                                            Riwayat
                                        </a>

                                        <!-- Tombol Blokir / Buka Blokir dengan Pop-up Konfirmasi -->
                                        <button type="button"
                                                @click="openBlockModal({{ json_encode($customer) }}, '{{ route('admin.customers.toggle-block', $customer) }}')"
                                                class="px-2.5 py-1 text-xs font-bold rounded-lg border transition flex items-center gap-1 {{ $customer->is_active ? 'border-amber-500/30 text-amber-600 dark:text-amber-400 hover:bg-amber-500/10' : 'border-emerald-500/30 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500/10' }}"
                                                title="{{ $customer->is_active ? 'Blokir akun pelanggan ini' : 'Buka blokir akun pelanggan ini' }}">
                                            <span>{{ $customer->is_active ? '🔒 Blokir' : '🔓 Buka Blokir' }}</span>
                                        </button>

                                        <!-- Tombol Reset Password dengan Modal Input Password Baru -->
                                        <button type="button"
                                                @click="openResetPasswordModal({{ json_encode($customer) }}, '{{ route('admin.customers.reset-password', $customer) }}')"
                                                class="px-2.5 py-1 text-xs font-bold rounded-lg border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition flex items-center gap-1"
                                                title="Setel password baru pelanggan">
                                            <span>🔑 Reset Pass</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 text-center text-slate-500 text-sm">Tidak ada data pelanggan ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                {{ $customers->links() }}
            </div>
        </div>

        <!-- Modal Pendaftaran / Pengaturan Member Pelanggan -->
        <div x-show="memberModal.open"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             role="dialog"
             aria-modal="true"
             style="display: none;">
            <div x-show="memberModal.open"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="memberModal.open = false"
                 class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm transition-opacity"></div>

            <div class="min-h-full flex items-center justify-center p-4">
                <div x-show="memberModal.open"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.away="memberModal.open = false"
                     class="relative bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-lg my-8 p-6 space-y-5">

                    <!-- Modal Header -->
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-amber-500/10 text-amber-500 border border-amber-500/30 flex items-center justify-center text-lg">
                                ⭐
                            </div>
                            <div>
                                <h3 class="text-base font-black text-slate-900 dark:text-white">Pendaftaran & Atur Member</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400" x-text="'Pelanggan: ' + memberModal.name"></p>
                            </div>
                        </div>
                        <button type="button" @click="memberModal.open = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Form -->
                    <form method="POST" :action="memberModal.actionUrl" class="space-y-4">
                        @csrf

                        <!-- Toggle Member Aktif -->
                        <div class="bg-slate-50 dark:bg-slate-800/60 p-4 rounded-2xl border border-slate-200 dark:border-slate-700/80 space-y-3">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_member" value="1"
                                       x-model="memberModal.is_member"
                                       class="w-5 h-5 rounded text-amber-500 focus:ring-amber-500 border-slate-300 dark:border-slate-700">
                                <span class="font-bold text-sm text-slate-900 dark:text-white">Aktifkan Status Member pada Akun Ini</span>
                            </label>
                            <p class="text-xs text-slate-500 dark:text-slate-400 pl-8">
                                Jika diaktifkan, akun pelanggan ini otomatis mendapatkan potongan harga diskon saat membuat reservasi/booking.
                            </p>
                        </div>

                        <!-- Fields when member is active -->
                        <div x-show="memberModal.is_member" class="space-y-4">
                            <!-- Besar Diskon & Tipe Diskon -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                        Besar Diskon (Angka) *
                                    </label>
                                    <input type="number" name="member_discount" x-model="memberModal.discount" min="0" step="any" required
                                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500 font-mono"
                                           placeholder="Contoh: 20 atau 15000" />
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                        Tipe Diskon *
                                    </label>
                                    <select name="member_discount_type" x-model="memberModal.discount_type"
                                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500">
                                        <option value="percent">Persen (%) - Potongan Persentase</option>
                                        <option value="fixed">Nominal (Rp) - Potongan Tetap Rupiah</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Kuota Diskon (Berapa kali dapat diskon) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                        Berapa Kali Dapat Diskon (Kuota) *
                                    </label>
                                    <input type="number" name="member_discount_quota" x-model="memberModal.quota" min="1" required
                                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500 font-mono"
                                           placeholder="Contoh: 5 kali transaksi" />
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                        Sisa Kuota Saat Ini
                                    </label>
                                    <input type="number" name="member_discount_remaining" x-model="memberModal.remaining" min="0"
                                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500 font-mono"
                                           placeholder="Sisa pemakaian diskon" />
                                </div>
                            </div>

                            <!-- Preview Ringkasan Diskon -->
                            <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-3 text-xs text-amber-800 dark:text-amber-300">
                                <span class="font-bold">Ringkasan:</span> Pelanggan ini akan mendapatkan potongan
                                <strong x-text="memberModal.discount_type === 'percent' ? memberModal.discount + '%' : 'Rp ' + Number(memberModal.discount || 0).toLocaleString('id-ID')"></strong>
                                sebanyak <strong x-text="memberModal.remaining"></strong> kali reservasi berikutnya.
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="memberModal.open = false"
                                    class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition">
                                Batal
                            </button>
                            <button type="submit"
                                    class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs rounded-xl shadow transition active:scale-95">
                                Simpan Status Member
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- 2. MODAL POP-UP KONFIRMASI BLOKIR / BUKA BLOKIR PELANGGAN     -->
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
                           ? 'Apakah Anda yakin ingin memblokir akun ini? Pelanggan tidak akan dapat login ke sistem dan tidak dapat membuat reservasi baru selama akun diblokir.'
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
        <!-- 3. MODAL INPUT PASSWORD BARU (RESET PASSWORD PELANGGAN)       -->
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
                            <div class="w-10 h-10 rounded-2xl bg-emerald-500/10 text-emerald-500 border border-emerald-500/30 flex items-center justify-center text-lg shadow-inner">
                                🔑
                            </div>
                            <div>
                                <h3 class="text-base font-black text-slate-900 dark:text-white">Reset Password Pelanggan</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Buat kata sandi baru untuk pelanggan ini secara langsung</p>
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
                                        class="text-[11px] text-emerald-600 dark:text-emerald-400 hover:underline font-semibold flex items-center gap-1">
                                    <span x-text="resetPasswordModal.showPassword ? 'Sembunyikan' : 'Tampilkan'"></span>
                                </button>
                            </div>
                            <input :type="resetPasswordModal.showPassword ? 'text' : 'password'"
                                   name="password"
                                   x-model="resetPasswordModal.password"
                                   required
                                   minlength="6"
                                   placeholder="Masukkan password baru (min. 6 karakter)"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500 font-mono" />
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
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500 font-mono" />
                        </div>

                        <div class="p-3 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900/50 rounded-xl text-[11px] text-emerald-800 dark:text-emerald-300 leading-relaxed">
                            💡 <strong>Catatan:</strong> Password ini akan langsung aktif. Anda dapat menginformasikan password baru ini kepada pelanggan setelah berhasil disimpan.
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="resetPasswordModal.open = false"
                                    class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition">
                                Batal
                            </button>
                            <button type="submit"
                                    class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs rounded-xl shadow-lg shadow-emerald-600/30 transition active:scale-95 flex items-center gap-1.5">
                                <span>Simpan Password Baru</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- 4. POP-UP NOTIFIKASI BERHASIL / GAGAL                         -->
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
        function customerAdminManager() {
            return {
                memberModal: {
                    open: false,
                    actionUrl: '',
                    name: '',
                    is_member: 1,
                    discount: 10,
                    discount_type: 'percent',
                    quota: 5,
                    remaining: 5,
                },
                openMemberModal(customer, actionUrl) {
                    this.memberModal = {
                        open: true,
                        actionUrl: actionUrl,
                        name: customer.name,
                        is_member: customer.is_member ? true : true,
                        discount: customer.member_discount > 0 ? customer.member_discount : 10,
                        discount_type: customer.member_discount_type || 'percent',
                        quota: customer.member_discount_quota > 0 ? customer.member_discount_quota : 5,
                        remaining: customer.is_member ? customer.member_discount_remaining : 5,
                    };
                },
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
                        name: customer.name,
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
                        name: customer.name,
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
</x-admin-layout>
