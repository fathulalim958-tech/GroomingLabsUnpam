<x-admin-layout title="Kelola Konten Kategori - Grooming Lab & MUA">
    <div class="space-y-6 max-w-4xl mx-auto py-2">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Kelola Konten Halaman Kategori</h1>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Atur teks penjelasan, keunggulan, fasilitas, dan tata tertib layanan Barbershop & MUA</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse ($contents as $content)
                <div class="bg-white dark:bg-slate-900 border-2 {{ $content->category === 'barbershop' ? 'border-amber-300 dark:border-amber-500/40' : 'border-pink-300 dark:border-pink-500/40' }} rounded-3xl p-6 shadow-sm dark:shadow-xl flex flex-col justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $content->category === 'barbershop' ? 'bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/30' : 'bg-pink-500/10 text-pink-700 dark:text-pink-400 border border-pink-500/30' }}">
                                {{ ucfirst($content->category) }}
                            </span>
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded {{ $content->is_active ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-red-500/10 text-red-600 dark:text-red-400' }}">
                                {{ $content->is_active ? 'Aktif Tayang' : 'Nonaktif' }}
                            </span>
                        </div>

                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ $content->title }}</h2>

                        @if ($content->tagline)
                            <p class="text-xs font-semibold {{ $content->category === 'barbershop' ? 'text-amber-700 dark:text-amber-300' : 'text-pink-700 dark:text-pink-300' }}">
                                {{ $content->tagline }}
                            </p>
                        @endif

                        <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-3 leading-relaxed">
                            {{ $content->description }}
                        </p>

                        <div class="pt-2 text-xs text-slate-600 dark:text-slate-400 space-y-1">
                            <div>
                                <strong class="text-slate-800 dark:text-slate-300">Fasilitas / Keunggulan:</strong> {{ count($content->facilities_list) }} poin terdaftar
                            </div>
                            <div>
                                <strong class="text-slate-800 dark:text-slate-300">Ketentuan Antrian:</strong> {{ count($content->terms_list) }} poin aturan
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
                        <!-- Preview Link -->
                        <a href="{{ route('categories.show', $content->category) }}" target="_blank"
                           class="text-xs text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white flex items-center gap-1 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            <span>Lihat Halaman Publik</span>
                        </a>

                        <!-- Edit Button -->
                        <a href="{{ route('admin.category-contents.edit', $content) }}"
                           class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg transition flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>Edit Konten</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-2 py-8 text-center text-slate-500 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl">
                    Belum ada konten kategori yang terdaftar.
                </div>
            @endforelse
        </div>
    </div>
</x-admin-layout>
