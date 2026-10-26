<x-admin-layout title="Kelola Kasir - Grooming Lab & MUA">
    <div class="space-y-6" x-data="cashierManager()">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Kelola Data Kasir</h1>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Kelola akun staf operasional kasir front-desk</p>
            </div>
            <a href="{{ route('admin.cashiers.create') }}"
               class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>+ Tambah Kasir</span>
            </a>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm dark:shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-950/60 text-xs uppercase tracking-wider text-slate-600 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="py-3 px-4">Nama Kasir</th>
                            <th class="py-3 px-4">Email</th>
                            <th class="py-3 px-4">No. Telepon</th>
                            <th class="py-3 px-4 text-center">Status Akun</th>
                            <th class="py-3 px-4">Terdaftar</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        @forelse ($cashiers as $cashier)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-3.5 px-4 font-bold text-slate-900 dark:text-white">{{ $cashier->name }}</td>
                                <td class="py-3.5 px-4 text-xs font-mono text-slate-600 dark:text-slate-300">{{ $cashier->email }}</td>
                                <td class="py-3.5 px-4 text-xs text-slate-600 dark:text-slate-300">{{ $cashier->phone ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold whitespace-nowrap inline-block {{ $cashier->is_active ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30' : 'bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/30' }}">
                                        {{ $cashier->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $cashier->created_at->format('d M Y') }}</td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Edit (Status Aktif / Nonaktif dikelola di dalam halaman edit) -->
                                        <a href="{{ route('admin.cashiers.edit', $cashier) }}"
                                           class="px-2.5 py-1.5 bg-blue-500/10 hover:bg-blue-500/20 text-blue-600 dark:text-blue-400 border border-blue-500/30 rounded-xl text-xs font-bold transition flex items-center gap-1"
                                           title="Edit Profil & Kelola Status Aktif / Nonaktif">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span>Edit</span>
                                        </a>

                                        <!-- Delete dengan Pop-up Dialog Konfirmasi -->
                                        <button type="button"
                                                @click="openDeleteModal({{ json_encode($cashier) }}, '{{ route('admin.cashiers.destroy', $cashier) }}')"
                                                class="px-2.5 py-1.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-500/30 rounded-xl text-xs font-bold transition flex items-center gap-1"
                                                title="Hapus Akun Kasir">
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
                                <td colspan="6" class="py-6 text-center text-slate-500 text-sm">Belum ada akun kasir terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                {{ $cashiers->links() }}
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- MODAL POP-UP KONFIRMASI HAPUS AKUN KASIR                     -->
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
                        <div class="w-12 h-12 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-500 flex items-center justify-center text-2xl flex-shrink-0 shadow-inner">
                            🗑️
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-base font-black text-slate-900 dark:text-white">
                                Konfirmasi Hapus Akun Kasir
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Tindakan ini permanen dan tidak dapat dibatalkan
                            </p>
                        </div>
                    </div>

                    <!-- Detail Kasir Box -->
                    <div class="p-3.5 bg-slate-50 dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700/80 space-y-1">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Akun Kasir yang Dihapus:</div>
                        <div class="font-black text-slate-900 dark:text-white text-sm" x-text="deleteModal.name"></div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 font-mono" x-text="deleteModal.email"></div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 font-mono" x-text="deleteModal.phone"></div>
                    </div>

                    <!-- Pesan Peringatan -->
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Apakah Anda yakin ingin menghapus akun kasir ini? Seluruh data akun beserta hak akses kasir akan dihapus dari sistem.
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
                                Ya, Hapus Kasir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function cashierManager() {
            return {
                deleteModal: {
                    open: false,
                    actionUrl: '',
                    name: '',
                    email: '',
                    phone: '',
                },
                openDeleteModal(cashier, actionUrl) {
                    this.deleteModal = {
                        open: true,
                        actionUrl: actionUrl,
                        name: cashier.name,
                        email: cashier.email,
                        phone: cashier.phone || '-',
                    };
                }
            }
        }
    </script>
    @endpush
</x-admin-layout>
