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
                <table class="w-full text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-950/60 text-xs uppercase tracking-wider text-slate-600 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="py-3 px-3 text-center">Nama Pelanggan</th>
                            <th class="py-3 px-3 text-center">Kontak</th>
                            <th class="py-3 px-2 text-center">Total Booking</th>
                            <th class="py-3 px-3 text-center">Keanggotaan Member</th>
                            <th class="py-3 px-2 text-center">Status Akun</th>
                            <th class="py-3 px-2 text-center">Bergabung</th>
                            <th class="py-3 px-3 text-center">Aksi / Kontrol</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        @forelse ($customers as $customer)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-3 px-3">
                                    <div class="flex items-center gap-2.5">
                                        <!-- Foto Profil Pelanggan -->
                                        <div class="relative flex-shrink-0">
                                            @if($customer->avatar)
                                                <img src="{{ asset('storage/' . $customer->avatar) }}" alt="{{ $customer->name }}" class="w-9 h-9 rounded-xl object-cover ring-1 ring-amber-500/50 shadow-sm">
                                            @else
                                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-amber-500/20 via-slate-100 dark:via-slate-800 to-amber-500/10 ring-1 ring-amber-500/30 flex items-center justify-center text-amber-500 font-black text-xs shadow-sm">
                                                    {{ strtoupper(substr($customer->name, 0, 2)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-bold text-slate-900 dark:text-white flex items-center gap-1 truncate">
                                                <span>{{ $customer->name }}</span>
                                                @if ($customer->is_member)
                                                    <span class="text-amber-500 text-xs flex-shrink-0" title="Member Aktif">⭐</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-3 text-xs text-center">
                                    <div class="text-slate-900 dark:text-slate-200 font-medium break-all">{{ $customer->email }}</div>
                                    <div class="text-slate-500 dark:text-slate-400 font-mono text-[11px]">{{ $customer->phone ?? '-' }}</div>
                                </td>
                                <td class="py-3 px-2 text-center whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200">
                                        {{ $customer->bookings_count }}x
                                    </span>
                                </td>
                                <!-- Kolom Status Member & Kuota Diskon -->
                                <td class="py-3 px-3 text-xs text-center">
                                    @if ($customer->is_member)
                                        <div class="flex flex-col items-center justify-center space-y-0.5">
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
                                <td class="py-3 px-2 text-center whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $customer->is_active ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30' : 'bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/30' }}">
                                        {{ $customer->is_active ? 'Aktif' : 'Diblokir' }}
                                    </span>
                                </td>
                                <td class="py-3 px-2 text-xs text-center text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $customer->created_at->format('d M Y') }}</td>
                                <td class="py-3 px-3 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1">
                                        <!-- 1. Aksi Detail -->
                                        <a href="{{ route('admin.customers.show', $customer) }}"
                                           class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-lg bg-blue-500/10 hover:bg-blue-500/20 text-blue-600 dark:text-blue-400 border border-blue-500/30 transition shadow-sm active:scale-95"
                                           title="Lihat Detail & Riwayat Pemesanan">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <span>Detail</span>
                                        </a>

                                        <!-- 2. Aksi Edit (Biodata & Reset Password) -->
                                        <button type="button"
                                                @click="openEditModal({{ json_encode($customer->only(['id', 'name', 'email', 'phone'])) }}, '{{ route('admin.customers.update', $customer) }}')"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-lg bg-amber-500/10 hover:bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/30 transition shadow-sm active:scale-95"
                                                title="Edit Biodata & Reset Password">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span>Edit</span>
                                        </button>

                                        <!-- 3. Aksi Hapus -->
                                        <button type="button"
                                                @click="openDeleteModal({{ json_encode($customer->only(['id', 'name', 'email'])) }}, '{{ route('admin.customers.destroy', $customer) }}')"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-500/30 transition shadow-sm active:scale-95"
                                                title="Hapus Akun Pelanggan">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span>Hapus</span>
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

        <!-- ============================================================ -->
        <!-- 1. MODAL EDIT (BIODATA PELANGGAN & RESET PASSWORD)            -->
        <!-- ============================================================ -->
        <div x-show="editModal.open"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             role="dialog"
             aria-modal="true"
             style="display: none;">
            <!-- Backdrop Blur -->
            <div x-show="editModal.open"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="editModal.open = false"
                 class="fixed inset-0 bg-slate-950/75 backdrop-blur-sm transition-opacity"></div>

            <div class="min-h-full flex items-center justify-center p-4">
                <div x-show="editModal.open"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.away="editModal.open = false"
                     class="relative bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-lg my-8 p-6 space-y-5">

                    <!-- Modal Header -->
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-amber-500/10 text-amber-500 border border-amber-500/30 flex items-center justify-center text-lg shadow-inner">
                                ✏️
                            </div>
                            <div>
                                <h3 class="text-base font-black text-slate-900 dark:text-white">Edit Biodata & Reset Password</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Perbarui identitas pelanggan dan setel kata sandi baru</p>
                            </div>
                        </div>
                        <button type="button" @click="editModal.open = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Form Edit -->
                    <form method="POST" :action="editModal.actionUrl" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <!-- Section: Biodata Pelanggan -->
                        <div class="space-y-3 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-2xl border border-slate-200 dark:border-slate-700/80">
                            <div class="text-[11px] uppercase tracking-wider font-bold text-slate-600 dark:text-slate-300 flex items-center gap-1.5">
                                <span>👤</span>
                                <span>Biodata Pelanggan</span>
                            </div>

                            <!-- Nama Lengkap -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Nama Lengkap <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="name" x-model="editModal.name" required
                                       class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500 transition"
                                       placeholder="Nama lengkap pelanggan" />
                            </div>

                            <!-- Alamat Email -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Alamat Email <span class="text-rose-500">*</span>
                                </label>
                                <input type="email" name="email" x-model="editModal.email" required
                                       class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500 font-mono transition"
                                       placeholder="contoh@gmail.com" />
                            </div>

                            <!-- Nomor WhatsApp / HP -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Nomor WhatsApp / HP
                                </label>
                                <input type="text" name="phone" x-model="editModal.phone"
                                       class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500 font-mono transition"
                                       placeholder="081234567890" />
                            </div>
                        </div>

                        <!-- Section: Reset Password Pelanggan -->
                        <div class="space-y-3 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-2xl border border-slate-200 dark:border-slate-700/80">
                            <div class="flex items-center justify-between">
                                <div class="text-[11px] uppercase tracking-wider font-bold text-slate-600 dark:text-slate-300 flex items-center gap-1.5">
                                    <span>🔑</span>
                                    <span>Reset Password (Opsional)</span>
                                </div>
                                <button type="button" @click="generateRandomPassword()"
                                        class="px-2.5 py-1 bg-amber-500/10 hover:bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/30 rounded-lg text-[11px] font-bold transition flex items-center gap-1 active:scale-95 shadow-sm">
                                    <span>⚡ Acak Password</span>
                                </button>
                            </div>

                            <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                Kosongkan kolom password di bawah jika Anda hanya ingin memperbarui biodata tanpa mengubah password akun pelanggan saat ini.
                            </p>

                            <!-- Password Baru -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        Password Baru
                                    </label>
                                    <button type="button" @click="editModal.showPassword = !editModal.showPassword"
                                            class="text-[11px] text-amber-600 dark:text-amber-400 hover:underline font-semibold">
                                        <span x-text="editModal.showPassword ? 'Sembunyikan' : 'Tampilkan'"></span>
                                    </button>
                                </div>
                                <input :type="editModal.showPassword ? 'text' : 'password'"
                                       name="password"
                                       x-model="editModal.password"
                                       minlength="6"
                                       placeholder="Kosongkan jika tidak diubah (min. 6 karakter)"
                                       class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500 font-mono transition" />
                            </div>

                            <!-- Konfirmasi Password Baru -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Konfirmasi Password Baru
                                </label>
                                <input :type="editModal.showPassword ? 'text' : 'password'"
                                       name="password_confirmation"
                                       x-model="editModal.password_confirmation"
                                       minlength="6"
                                       placeholder="Ulangi password baru yang sama"
                                       class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500 font-mono transition" />
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="editModal.open = false"
                                    class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition">
                                Batal
                            </button>
                            <button type="submit"
                                    class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs rounded-xl shadow transition active:scale-95 flex items-center gap-1.5">
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- 2. MODAL KONFIRMASI HAPUS AKUN PELANGGAN                      -->
        <!-- ============================================================ -->
        <div x-show="deleteModal.open"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             role="dialog"
             aria-modal="true"
             style="display: none;">
            <!-- Backdrop Blur -->
            <div x-show="deleteModal.open"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="deleteModal.open = false"
                 class="fixed inset-0 bg-slate-950/75 backdrop-blur-sm transition-opacity"></div>

            <div class="min-h-full flex items-center justify-center p-4">
                <div x-show="deleteModal.open"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.away="deleteModal.open = false"
                     class="relative bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-md p-6 space-y-5">

                    <!-- Header Modal -->
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-500 border border-rose-500/30 flex items-center justify-center text-2xl flex-shrink-0 shadow-inner">
                            🗑️
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-base font-black text-slate-900 dark:text-white">
                                Konfirmasi Hapus Pelanggan
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Tindakan permanen menghapus akun pelanggan
                            </p>
                        </div>
                    </div>

                    <!-- Detail Pelanggan Box -->
                    <div class="p-3.5 bg-slate-50 dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700/80 space-y-1">
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Akun Pelanggan:</div>
                        <div class="font-black text-slate-900 dark:text-white text-sm" x-text="deleteModal.name"></div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 font-mono" x-text="deleteModal.email"></div>
                    </div>

                    <!-- Pesan Peringatan -->
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Apakah Anda yakin ingin menghapus akun pelanggan ini? Tindakan ini akan menghapus akun beserta seluruh data riwayat pemesanan secara permanen dan tidak dapat dipulihkan.
                    </p>

                    <!-- Tombol Aksi -->
                    <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="deleteModal.open = false"
                                class="w-full py-2.5 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl transition">
                            Batal
                        </button>
                        <form method="POST" :action="deleteModal.actionUrl" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full py-2.5 px-4 bg-rose-600 hover:bg-rose-500 text-white font-black text-xs rounded-xl shadow-lg shadow-rose-600/30 transition active:scale-95 flex items-center justify-center gap-1.5">
                                <span>Ya, Hapus Akun</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- 3. POP-UP NOTIFIKASI BERHASIL / GAGAL                         -->
        <!-- ============================================================ -->
        @if (session('success') || $errors->any())
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

                        @if (session('success'))
                            <!-- NOTIFIKASI SUKSES -->
                            <div class="w-16 h-16 rounded-3xl bg-emerald-500/15 border-2 border-emerald-500/30 text-emerald-500 flex items-center justify-center text-3xl mx-auto shadow-inner">
                                ✓
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900 dark:text-white">Operasi Berhasil!</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    {{ session('success') }}
                                </p>
                            </div>

                            <button type="button" @click="notificationModal.open = false"
                                    class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs rounded-xl shadow-lg shadow-emerald-600/30 transition active:scale-95">
                                Selesai & Tutup
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
                editModal: {
                    open: false,
                    actionUrl: '',
                    name: '',
                    email: '',
                    phone: '',
                    password: '',
                    password_confirmation: '',
                    showPassword: false,
                },
                openEditModal(customer, actionUrl) {
                    this.editModal = {
                        open: true,
                        actionUrl: actionUrl,
                        name: customer.name || '',
                        email: customer.email || '',
                        phone: customer.phone || '',
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
                    this.editModal.password = pass;
                    this.editModal.password_confirmation = pass;
                    this.editModal.showPassword = true;
                },
                deleteModal: {
                    open: false,
                    actionUrl: '',
                    name: '',
                    email: '',
                },
                openDeleteModal(customer, actionUrl) {
                    this.deleteModal = {
                        open: true,
                        actionUrl: actionUrl,
                        name: customer.name || '',
                        email: customer.email || '',
                    };
                },
                notificationModal: {
                    open: {{ (session('success') || $errors->any()) ? 'true' : 'false' }},
                }
            }
        }
    </script>
    @endpush
</x-admin-layout>
