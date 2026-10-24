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
                                    <div class="font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                                        <span>{{ $customer->name }}</span>
                                        @if ($customer->is_member)
                                            <span class="text-amber-500 text-xs" title="Member Aktif">⭐</span>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-slate-500 font-mono">ID: #{{ $customer->id }}</div>
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

                                        <!-- Toggle Block (FR-ADM-02) -->
                                        <form method="POST" action="{{ route('admin.customers.toggle-block', $customer) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="px-2.5 py-1 text-xs rounded-lg border transition {{ $customer->is_active ? 'border-amber-500/30 text-amber-600 dark:text-amber-400 hover:bg-amber-500/10' : 'border-emerald-500/30 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500/10' }}">
                                                {{ $customer->is_active ? 'Blokir' : 'Buka Blokir' }}
                                            </button>
                                        </form>

                                        <!-- Reset Password (FR-ADM-02) -->
                                        <form method="POST" action="{{ route('admin.customers.reset-password', $customer) }}" onsubmit="return confirm('Reset password akun {{ $customer->name }} ke password123?');">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 text-xs rounded-lg border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition" title="Reset password ke default">
                                                Reset Pass
                                            </button>
                                        </form>
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
                }
            }
        }
    </script>
    @endpush
</x-admin-layout>
