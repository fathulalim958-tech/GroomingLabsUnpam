<x-admin-layout title="Katalog Layanan - Grooming Lab & MUA">
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-white">Katalog Layanan</h1>
                <p class="text-xs text-slate-400 mt-1">Kelola data layanan Barbershop dan MUA Studio</p>
            </div>

            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('admin.services.index') }}" class="flex items-center gap-2">
                    <select name="category" onchange="this.form.submit()"
                            class="px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">Semua Kategori</option>
                        <option value="barbershop" {{ request('category') === 'barbershop' ? 'selected' : '' }}>Barbershop</option>
                        <option value="mua" {{ request('category') === 'mua' ? 'selected' : '' }}>MUA Studio</option>
                    </select>

                    <select name="status" onchange="this.form.submit()"
                            class="px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </form>

                <a href="{{ route('admin.services.create') }}"
                   class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>+ Tambah Layanan</span>
                </a>
            </div>
        </div>

        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($services as $service)
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl flex flex-col justify-between hover:border-slate-700 transition">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $service->category === 'barbershop' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-pink-500/10 text-pink-400 border border-pink-500/20' }}">
                                {{ ucfirst($service->category) }}
                            </span>

                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $service->is_active ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' }}">
                                {{ $service->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>

                        <h3 class="font-bold text-white text-base">{{ $service->name }}</h3>
                        <p class="text-xs text-slate-400 mt-1 line-clamp-2">{{ $service->description ?? 'Tidak ada deskripsi.' }}</p>

                        <div class="mt-4 flex items-center justify-between text-xs pt-3 border-t border-slate-800">
                            <span class="text-slate-400 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $service->duration_minutes }} Menit
                            </span>
                            <span class="font-extrabold text-base {{ $service->category === 'barbershop' ? 'text-amber-400' : 'text-pink-400' }}">
                                {{ $service->formatted_price }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-end gap-2">
                        <a href="{{ route('admin.services.edit', $service) }}" class="p-1.5 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition" title="Edit Layanan">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>

                        <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Hapus layanan {{ $service->name }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition" title="Hapus Layanan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-12 text-center text-slate-500 text-sm bg-slate-900 border border-slate-800 rounded-2xl">
                    Belum ada layanan yang sesuai dengan kriteria.
                </div>
            @endforelse
        </div>

        <div class="p-4">
            {{ $services->links() }}
        </div>
    </div>
</x-admin-layout>
