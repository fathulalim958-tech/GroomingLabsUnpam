<x-admin-layout title="Kelola Kasir & Staf - Grooming Lab & MUA">
    <div class="space-y-6" x-data="cashierManager()">
        <!-- Header & Unified Tabs -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 dark:text-white">Kelola Kasir &amp; Staf</h1>
                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Manajemen akun kasir, jadwal bertugas, dan data staf kapster &amp; MUA dalam satu menu terpadu</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.cashiers.create') }}"
                       class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>+ Tambah Kasir</span>
                    </a>
                </div>
            </div>

            <!-- Unified Tabs Switcher -->
            <div class="flex border-b border-slate-200 dark:border-slate-800 space-x-1">
                <a href="{{ route('admin.cashiers.index') }}"
                   class="px-5 py-3 border-b-2 border-emerald-500 text-emerald-600 dark:text-emerald-400 font-bold text-sm flex items-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Akun &amp; Jadwal Kasir</span>
                    <span class="px-2 py-0.5 text-xs rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-mono">
                        {{ $cashiers->total() }}
                    </span>
                </a>

                <a href="{{ route('admin.staffs.index') }}"
                   class="px-5 py-3 border-b-2 border-transparent text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 font-semibold text-sm flex items-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>Data Staf (Kapster &amp; MUA)</span>
                    <span class="px-2 py-0.5 text-xs rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-mono">
                        {{ $staffCount ?? 0 }}
                    </span>
                </a>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm dark:shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-950/60 text-xs uppercase tracking-wider text-slate-600 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="py-3 px-4">Nama Kasir</th>
                            <th class="py-3 px-4">Peran</th>
                            <th class="py-3 px-4">Jadwal Tugas</th>
                            <th class="py-3 px-4 text-center">Status Hari Ini</th>
                            <th class="py-3 px-4 text-center">Status Akun</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        @forelse ($cashiers as $cashier)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <!-- Foto Profil Kasir -->
                                        <div class="relative flex-shrink-0">
                                            @if($cashier->avatar)
                                                <img src="{{ asset('storage/' . $cashier->avatar) }}" alt="{{ $cashier->name }}" class="w-10 h-10 rounded-xl object-cover ring-1 ring-emerald-500/50 shadow-sm">
                                            @else
                                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500/20 via-slate-100 dark:via-slate-800 to-emerald-500/10 ring-1 ring-emerald-500/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-black text-xs shadow-sm">
                                                    {{ strtoupper(substr($cashier->name, 0, 2)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 dark:text-white">
                                                {{ $cashier->name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if ($cashier->service_category === 'mua')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-pink-500/10 text-pink-700 dark:text-pink-400 border border-pink-500/20">
                                            ✨ MUA
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/20">
                                            💈 Barberman
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex flex-col gap-1 items-start">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-500/10 text-blue-700 dark:text-blue-300 border border-blue-500/20">
                                            📅 {{ $cashier->work_days_formatted }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    @if ($cashier->isScheduledToday())
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                            <span>Tersedia</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-300 dark:border-slate-700">
                                            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                            <span>Tidak Tersedia</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold whitespace-nowrap inline-block {{ $cashier->is_active ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30' : 'bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/30' }}">
                                        {{ $cashier->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Quick Atur Jadwal Modal Trigger -->
                                        <button type="button"
                                                @click="openScheduleModal({{ json_encode($cashier) }}, '{{ route('admin.cashiers.schedule', $cashier) }}')"
                                                class="px-2.5 py-1.5 bg-amber-500/10 hover:bg-amber-500/20 text-amber-700 dark:text-amber-400 border border-amber-500/30 rounded-xl text-xs font-bold transition flex items-center gap-1"
                                                title="Atur Jadwal Ketersediaan Kasir">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>Jadwal</span>
                                        </button>

                                        <!-- Edit -->
                                        <a href="{{ route('admin.cashiers.edit', $cashier) }}"
                                           class="px-2.5 py-1.5 bg-blue-500/10 hover:bg-blue-500/20 text-blue-600 dark:text-blue-400 border border-blue-500/30 rounded-xl text-xs font-bold transition flex items-center gap-1"
                                           title="Edit Profil & Kelola Status">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span>Edit</span>
                                        </a>

                                        <!-- Delete dengan Dialog Konfirmasi -->
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
                                <td colspan="6" class="py-8 text-center text-slate-500 text-sm">Belum ada akun kasir terdaftar.</td>
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
        <!-- MODAL CEPAT: ATUR JADWAL KASIR -->
        <!-- ============================================================ -->
        <div x-show="showScheduleModal"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-2xl border border-slate-200 dark:border-slate-800"
                 @click.outside="showScheduleModal = false">
                <form :action="scheduleActionUrl" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Atur Jadwal Kasir</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5" x-text="'Kasir: ' + (selectedCashier ? selectedCashier.name : '')"></p>
                        </div>
                        <button type="button" @click="showScheduleModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            ✕
                        </button>
                    </div>

                    <div class="py-4 space-y-4">
                        <!-- Checkboxes Hari (Manual Selection) -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                    Pilih Hari Bertugas Kasir
                                </label>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">Klik hari untuk mengaktifkan</span>
                            </div>
                            <div class="grid grid-cols-4 sm:grid-cols-7 gap-1.5">
                                <template x-for="(d, idx) in daysList" :key="d.val">
                                    <label class="flex flex-col items-center justify-center p-2 rounded-xl border text-center cursor-pointer transition select-none"
                                           :class="scheduleDays.includes(d.val)
                                               ? 'bg-emerald-500 text-slate-950 font-bold border-emerald-500 shadow-sm'
                                               : 'bg-slate-50 dark:bg-slate-800/60 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:border-slate-300'">
                                        <input type="checkbox" name="work_days[]" :value="d.val"
                                               :checked="scheduleDays.includes(d.val)"
                                               @change="toggleScheduleDay(d.val)"
                                               class="sr-only" />
                                        <span class="text-xs" x-text="d.label"></span>
                                        <span class="text-[9px] mt-0.5 opacity-80" x-text="scheduleDays.includes(d.val) ? '✓' : 'Off'"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-2">
                        <button type="button" @click="showScheduleModal = false" class="px-4 py-2 text-xs font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-xl">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg transition">
                            Simpan Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- POP-UP DIALOG KONFIRMASI HAPUS KASIR -->
        <!-- ============================================================ -->
        <div x-show="showDeleteModal"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-2xl border border-slate-200 dark:border-slate-800"
                 @click.outside="showDeleteModal = false">
                <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-500 flex items-center justify-center mb-4 ring-1 ring-rose-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">Hapus Akun Kasir?</h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 mb-6">
                    Apakah Anda yakin ingin menghapus akun kasir <strong class="text-slate-900 dark:text-white" x-text="selectedCashier ? selectedCashier.name : ''"></strong>? Tindakan ini tidak dapat dibatalkan.
                </p>

                <div class="flex items-center justify-end gap-2.5">
                    <button type="button"
                            @click="showDeleteModal = false"
                            class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-xl transition">
                        Batal
                    </button>
                    <form :action="deleteActionUrl" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-rose-600/20 transition">
                            Ya, Hapus Kasir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function cashierManager() {
            return {
                showDeleteModal: false,
                deleteActionUrl: '',
                showScheduleModal: false,
                scheduleActionUrl: '',
                selectedCashier: null,
                scheduleDays: [1, 2, 3, 4, 5, 6, 0],
                daysList: [
                    { val: 1, label: 'Sen' },
                    { val: 2, label: 'Sel' },
                    { val: 3, label: 'Rab' },
                    { val: 4, label: 'Kam' },
                    { val: 5, label: 'Jum' },
                    { val: 6, label: 'Sab' },
                    { val: 0, label: 'Min' }
                ],

                openDeleteModal(cashier, actionUrl) {
                    this.selectedCashier = cashier;
                    this.deleteActionUrl = actionUrl;
                    this.showDeleteModal = true;
                },

                openScheduleModal(cashier, actionUrl) {
                    this.selectedCashier = cashier;
                    this.scheduleActionUrl = actionUrl;
                    let existing = cashier.work_days;
                    if (!existing || existing.length === 0) {
                        this.scheduleDays = [1, 2, 3, 4, 5, 6, 0];
                    } else {
                        this.scheduleDays = existing.map(Number);
                    }
                    this.showScheduleModal = true;
                },

                toggleScheduleDay(val) {
                    const idx = this.scheduleDays.indexOf(val);
                    if (idx > -1) {
                        this.scheduleDays.splice(idx, 1);
                    } else {
                        this.scheduleDays.push(val);
                    }
                }
            };
        }
    </script>
    @endpush
</x-admin-layout>
