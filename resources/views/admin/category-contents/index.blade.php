<x-admin-layout title="Kelola Konten & Halaman Login - Grooming Lab & MUA">
    <div class="space-y-6 max-w-6xl mx-auto py-2">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Kelola Konten Halaman Kategori & Login</h1>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Atur narasi penjelasan layanan Barbershop, MUA Studio, dan teks/tampilan pada halaman login akun</p>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-600 dark:text-emerald-400 text-xs font-bold flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse ($contents as $content)
                @php
                    $isLogin = $content->category === 'login';
                    $isBarber = $content->category === 'barbershop';
                    $borderColor = $isBarber
                        ? 'border-amber-300 dark:border-amber-500/40'
                        : ($isLogin ? 'border-blue-300 dark:border-blue-500/40' : 'border-pink-300 dark:border-pink-500/40');
                    $badgeColor = $isBarber
                        ? 'bg-amber-500/10 text-amber-700 dark:text-amber-400 border-amber-500/30'
                        : ($isLogin ? 'bg-blue-500/10 text-blue-700 dark:text-blue-400 border-blue-500/30' : 'bg-pink-500/10 text-pink-700 dark:text-pink-400 border-pink-500/30');
                    $badgeText = $isBarber ? '💈 Barbershop' : ($isLogin ? '🔐 Halaman Login' : '✨ MUA Studio');
                @endphp

                <div class="bg-white dark:bg-slate-900 border-2 {{ $borderColor }} rounded-3xl p-6 shadow-sm dark:shadow-xl flex flex-col justify-between transition hover:shadow-md">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $badgeColor }}">
                                {{ $badgeText }}
                            </span>
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded {{ $content->is_active ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-red-500/10 text-red-600 dark:text-red-400' }}">
                                {{ $content->is_active ? 'Aktif Tayang' : 'Nonaktif' }}
                            </span>
                        </div>

                        <div>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white">
                                {{ $isLogin ? 'Tampilan & Narasi Halaman Login' : $content->title }}
                            </h2>
                            @if ($content->tagline)
                                <p class="text-xs font-semibold mt-1 {{ $isBarber ? 'text-amber-700 dark:text-amber-300' : ($isLogin ? 'text-blue-600 dark:text-blue-400' : 'text-pink-700 dark:text-pink-300') }}">
                                    {{ $content->tagline }}
                                    @if ($isLogin && $content->facilities)
                                        <span class="font-normal text-slate-500 dark:text-slate-400">&bull; {{ $content->facilities }}</span>
                                    @endif
                                </p>
                            @endif
                        </div>

                        <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-3 leading-relaxed">
                            {{ $content->description }}
                        </p>

                        <div class="pt-2 text-xs text-slate-600 dark:text-slate-400 space-y-1.5 bg-slate-50 dark:bg-slate-800/50 p-3 rounded-2xl border border-slate-100 dark:border-slate-800">
                            @if ($isLogin)
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-slate-500 dark:text-slate-400 font-medium">Sub-Judul Form:</span>
                                    <span class="font-semibold text-slate-800 dark:text-slate-200 truncate max-w-[150px]">{{ $content->terms ?? '-' }}</span>
                                </div>
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-slate-500 dark:text-slate-400 font-medium">Foto Background:</span>
                                    <span class="font-semibold {{ $content->banner_image ? 'text-blue-600 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300' }}">
                                        {{ $content->banner_image ? 'Foto Kustom' : 'Foto Default' }}
                                    </span>
                                </div>
                            @else
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-slate-500 dark:text-slate-400 font-medium">Fasilitas / Keunggulan:</span>
                                    <span class="font-semibold text-slate-800 dark:text-slate-200">{{ count($content->facilities_list) }} poin</span>
                                </div>
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-slate-500 dark:text-slate-400 font-medium">Ketentuan Antrian:</span>
                                    <span class="font-semibold text-slate-800 dark:text-slate-200">{{ count($content->terms_list) }} aturan</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between gap-2">
                        <!-- Preview Link -->
                        <a href="{{ $isLogin ? route('admin.category-contents.preview-login') : route('categories.show', $content->category) }}" target="_blank"
                           class="text-xs text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white flex items-center gap-1 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            <span>{{ $isLogin ? 'Lihat Login' : 'Lihat Halaman' }}</span>
                        </a>

                        <!-- Edit Button -->
                        <a href="{{ route('admin.category-contents.edit', $content) }}"
                           class="px-3.5 py-2 {{ $isLogin ? 'bg-blue-600 hover:bg-blue-500 text-white' : 'bg-emerald-500 hover:bg-emerald-400 text-slate-950' }} font-bold text-xs rounded-xl shadow transition flex items-center gap-1.5 active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>Edit Konten</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-8 text-center text-slate-500 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl">
                    Belum ada konten kategori yang terdaftar.
                </div>
            @endforelse
        </div>
    </div>
</x-admin-layout>
