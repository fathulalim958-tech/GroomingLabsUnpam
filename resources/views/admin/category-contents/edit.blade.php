<x-admin-layout title="Edit Konten Kategori - Grooming Lab & MUA">
    <div class="max-w-2xl mx-auto py-2 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider {{ $categoryContent->category === 'barbershop' ? 'text-amber-400' : 'text-pink-400' }}">
                    Kategori {{ ucfirst($categoryContent->category) }}
                </span>
                <h1 class="text-2xl font-black text-white mt-0.5">Edit Halaman Informasi Layanan</h1>
                <p class="text-xs text-slate-400 mt-1">Perbarui narasi, keunggulan, dan tata cara booking yang tampil kepada pelanggan</p>
            </div>
            <a href="{{ route('admin.category-contents.index') }}" class="text-xs text-slate-400 hover:text-white transition">
                &larr; Kembali
            </a>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl">
            <form method="POST" action="{{ route('admin.category-contents.update', $categoryContent) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="block text-xs font-semibold text-slate-300 mb-1">Judul Utama Halaman *</label>
                    <input id="title" type="text" name="title" value="{{ old('title', $categoryContent->title) }}" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                    @error('title')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tagline" class="block text-xs font-semibold text-slate-300 mb-1">Tagline / Slogan Sub-Judul</label>
                    <input id="tagline" type="text" name="tagline" value="{{ old('tagline', $categoryContent->tagline) }}"
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        placeholder="Contoh: Perawatan Rambut Maskulin & Modern..." />
                    @error('tagline')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-xs font-semibold text-slate-300 mb-1">Deskripsi Lengkap / Narasi Layanan *</label>
                    <textarea id="description" name="description" rows="5" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 leading-relaxed"
                        placeholder="Jelaskan konsep layanan, kenyamanan, kualitas produk yang digunakan, dll...">{{ old('description', $categoryContent->description) }}</textarea>
                    <p class="text-[11px] text-slate-500 mt-1">Teks narasi ini akan tampil pada bagian atas halaman kategori.</p>
                    @error('description')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="facilities" class="block text-xs font-semibold text-slate-300 mb-1">Daftar Fasilitas & Keunggulan</label>
                    <textarea id="facilities" name="facilities" rows="5"
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white font-mono text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500 leading-relaxed"
                        placeholder="Tulis 1 fasilitas per baris, contoh:&#10;Ruangan Full AC&#10;Kapster Berpengalaman&#10;Free Wi-Fi">{{ old('facilities', $categoryContent->facilities) }}</textarea>
                    <p class="text-[11px] text-slate-500 mt-1">Format: Tulis <strong>satu poin fasilitas per baris (Enter)</strong>. Tiap baris otomatis diberi ikon checkmark.</p>
                    @error('facilities')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="terms" class="block text-xs font-semibold text-slate-300 mb-1">Ketentuan & Panduan Antrian</label>
                    <textarea id="terms" name="terms" rows="4"
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white font-mono text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500 leading-relaxed"
                        placeholder="Tulis 1 aturan per baris, contoh:&#10;Datang 10 menit sebelum jam reservasi&#10;Nomor antrian terlewat dapat dipanggil kembali">{{ old('terms', $categoryContent->terms) }}</textarea>
                    <p class="text-[11px] text-slate-500 mt-1">Format: Tulis <strong>satu poin aturan per baris (Enter)</strong>. Tiap baris otomatis diberi nomor urut.</p>
                    @error('terms')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $categoryContent->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-emerald-500 focus:ring-emerald-500">
                        <span class="text-xs text-slate-300">Publikasikan Halaman Kategori Ini</span>
                    </label>
                </div>

                <div class="pt-4 border-t border-slate-800 flex items-center justify-between">
                    <a href="{{ route('categories.show', $categoryContent->category) }}" target="_blank"
                       class="text-xs text-slate-400 hover:text-white underline">
                        Pratinjau Halaman &rarr;
                    </a>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.category-contents.index') }}" class="px-4 py-2 text-xs text-slate-400 hover:text-white rounded-xl">Batal</a>
                        <button type="submit" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
