<x-admin-layout title="Kelola Kasir - Grooming Lab & MUA">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-white">Kelola Data Kasir</h1>
                <p class="text-xs text-slate-400 mt-1">Kelola akun staf operasional kasir front-desk</p>
            </div>
            <a href="{{ route('admin.cashiers.create') }}"
               class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>+ Tambah Kasir</span>
            </a>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-950/60 text-xs uppercase tracking-wider text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="py-3 px-4">Nama Kasir</th>
                            <th class="py-3 px-4">Email</th>
                            <th class="py-3 px-4">No. Telepon</th>
                            <th class="py-3 px-4">Status Akun</th>
                            <th class="py-3 px-4">Terdaftar</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80">
                        @forelse ($cashiers as $cashier)
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="py-3.5 px-4 font-bold text-white">{{ $cashier->name }}</td>
                                <td class="py-3.5 px-4 text-xs font-mono text-slate-300">{{ $cashier->email }}</td>
                                <td class="py-3.5 px-4 text-xs text-slate-300">{{ $cashier->phone ?? '-' }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $cashier->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-red-500/10 text-red-400 border border-red-500/30' }}">
                                        {{ $cashier->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-xs text-slate-400">{{ $cashier->created_at->format('d M Y') }}</td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Toggle Active (FR-ADM-01) -->
                                        <form method="POST" action="{{ route('admin.cashiers.toggle-active', $cashier) }}">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 text-xs rounded-lg border transition {{ $cashier->is_active ? 'border-amber-500/30 text-amber-400 hover:bg-amber-500/10' : 'border-emerald-500/30 text-emerald-400 hover:bg-emerald-500/10' }}"
                                                    title="{{ $cashier->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                                {{ $cashier->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                        <!-- Edit -->
                                        <a href="{{ route('admin.cashiers.edit', $cashier) }}" class="p-1.5 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <!-- Delete -->
                                        <form method="POST" action="{{ route('admin.cashiers.destroy', $cashier) }}" onsubmit="return confirm('Hapus kasir {{ $cashier->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
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

            <div class="p-4 border-t border-slate-800">
                {{ $cashiers->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
