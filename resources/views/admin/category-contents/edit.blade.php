<x-admin-layout title="Edit Konten {{ $categoryContent->category === 'login' ? 'Halaman Login' : 'Kategori' }} - Grooming Lab & MUA">
    @php
        $isLogin = $categoryContent->category === 'login';
        $isBarber = $categoryContent->category === 'barbershop';
        $badgeClass = $isBarber
            ? 'text-amber-600 dark:text-amber-400'
            : ($isLogin ? 'text-blue-600 dark:text-blue-400' : 'text-pink-600 dark:text-pink-400');
        $badgeLabel = $isBarber
            ? 'Kategori Barbershop'
            : ($isLogin ? '🔐 Halaman Login Akun' : 'Kategori MUA Studio');
    @endphp

    <div class="max-w-2xl mx-auto py-2 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider {{ $badgeClass }}">
                    {{ $badgeLabel }}
                </span>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white mt-0.5">
                    {{ $isLogin ? 'Edit Konten & Tampilan Halaman Login' : 'Edit Halaman Informasi Layanan' }}
                </h1>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                    {{ $isLogin ? 'Perbarui judul sambutan, slogan, sub-judul form, dan foto background hero login' : 'Perbarui narasi, keunggulan, dan tata cara booking yang tampil kepada pelanggan' }}
                </p>
            </div>
            <a href="{{ route('admin.category-contents.index') }}" class="text-xs text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">
                &larr; Kembali
            </a>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm dark:shadow-xl">
            <form method="POST" action="{{ route('admin.category-contents.update', $categoryContent) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                @if ($isLogin)
                    {{-- Form Khusus Halaman Login --}}
                    <div>
                        <label for="title" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Judul Sambutan Banner (Kiri) *
                        </label>
                        <input id="title" type="text" name="title" value="{{ old('title', $categoryContent->title) }}" required
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Contoh: Selamat Datang" />
                        <p class="text-[11px] text-slate-500 mt-1">Kata terakhir otomatis di-highlight dengan aksen warna biru muda pada halaman login.</p>
                        @error('title')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Deskripsi Sambutan Banner (Kiri) *
                        </label>
                        <textarea id="description" name="description" rows="3" required
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 leading-relaxed"
                            placeholder="Contoh: Masuk untuk memesan layanan atau mengelola antrian">{{ old('description', $categoryContent->description) }}</textarea>
                        <p class="text-[11px] text-slate-500 mt-1">Teks pengantar di bawah judul pada banner sebelah kiri.</p>
                        @error('description')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="tagline" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Slogan Banner - Baris 1
                            </label>
                            <input id="tagline" type="text" name="tagline" value="{{ old('tagline', $categoryContent->tagline) }}"
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Contoh: Grooming Experience" />
                            @error('tagline')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="facilities" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Slogan Banner - Baris 2 (Aksen Biru)
                            </label>
                            <input id="facilities" type="text" name="facilities" value="{{ old('facilities', $categoryContent->facilities) }}"
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Contoh: Better Together" />
                            @error('facilities')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="terms" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Sub-Judul Card Form Login (Kanan)
                        </label>
                        <input id="terms" type="text" name="terms" value="{{ old('terms', $categoryContent->terms) }}"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Contoh: Akses akun Anda untuk melanjutkan" />
                        <p class="text-[11px] text-slate-500 mt-1">Teks di bawah judul "Login Account" pada kolom form login sebelah kanan.</p>
                        @error('terms')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            Foto Background Banner Login (Kursi Barber / Interior)
                        </label>
                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                            <div class="w-24 h-20 rounded-xl overflow-hidden bg-slate-900 flex-shrink-0 border border-slate-300 dark:border-slate-700 relative">
                                <img src="{{ $categoryContent->banner_image ? asset('storage/' . $categoryContent->banner_image) : asset('images/hero-barber-chair.png') }}"
                                     alt="Preview Background Login" class="w-full h-full object-cover">
                                <span class="absolute bottom-1 right-1 bg-black/70 text-white text-[9px] px-1 py-0.5 rounded font-mono">
                                    {{ $categoryContent->banner_image ? 'Kustom' : 'Default' }}
                                </span>
                            </div>
                            <div class="flex-1 space-y-2">
                                <input type="file" name="banner_image" id="banner_image" accept="image/png,image/jpeg,image/webp"
                                    class="block w-full text-xs text-slate-600 dark:text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500 file:cursor-pointer cursor-pointer">
                                <p class="text-[11px] text-slate-500">Format gambar: JPG, PNG, atau WEBP. Maksimal ukuran 3MB. Kosongkan jika tidak ingin mengganti foto saat ini.</p>
                                @error('banner_image')
                                    <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                @else
                    {{-- Form Konten Kategori (Barbershop & MUA) --}}
                    <div>
                        <label for="title" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Judul Utama Halaman *</label>
                        <input id="title" type="text" name="title" value="{{ old('title', $categoryContent->title) }}" required
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                        @error('title')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tagline" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tagline / Slogan Sub-Judul</label>
                        <input id="tagline" type="text" name="tagline" value="{{ old('tagline', $categoryContent->tagline) }}"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            placeholder="Contoh: Perawatan Rambut Maskulin & Modern..." />
                        @error('tagline')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Deskripsi Lengkap / Narasi Layanan *</label>
                        <textarea id="description" name="description" rows="5" required
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 leading-relaxed"
                            placeholder="Jelaskan konsep layanan, kenyamanan, kualitas produk yang digunakan, dll...">{{ old('description', $categoryContent->description) }}</textarea>
                        <p class="text-[11px] text-slate-500 mt-1">Teks narasi ini akan tampil pada bagian atas halaman kategori.</p>
                        @error('description')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="facilities" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Daftar Fasilitas & Keunggulan</label>
                        <textarea id="facilities" name="facilities" rows="5"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white font-mono text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500 leading-relaxed"
                            placeholder="Tulis 1 fasilitas per baris, contoh:&#10;Ruangan Full AC&#10;Kapster Berpengalaman&#10;Free Wi-Fi">{{ old('facilities', $categoryContent->facilities) }}</textarea>
                        <p class="text-[11px] text-slate-500 mt-1">Format: Tulis <strong>satu poin fasilitas per baris (Enter)</strong>. Tiap baris otomatis diberi ikon checkmark.</p>
                        @error('facilities')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="terms" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Ketentuan & Panduan Antrian</label>
                        <textarea id="terms" name="terms" rows="4"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white font-mono text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500 leading-relaxed"
                            placeholder="Tulis 1 aturan per baris, contoh:&#10;Datang 10 menit sebelum jam reservasi&#10;Nomor antrian terlewat dapat dipanggil kembali">{{ old('terms', $categoryContent->terms) }}</textarea>
                        <p class="text-[11px] text-slate-500 mt-1">Format: Tulis <strong>satu poin aturan per baris (Enter)</strong>. Tiap baris otomatis diberi nomor urut.</p>
                        @error('terms')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <div class="pt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $categoryContent->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 rounded bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-700 text-blue-600 focus:ring-blue-500">
                        <span class="text-xs text-slate-700 dark:text-slate-300">
                            {{ $isLogin ? 'Aktifkan Pengaturan Konten Login Ini' : 'Publikasikan Halaman Kategori Ini' }}
                        </span>
                    </label>
                </div>

                <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <a href="{{ $isLogin ? route('admin.category-contents.preview-login') : route('categories.show', $categoryContent->category) }}" target="_blank"
                       class="text-xs text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white underline">
                        {{ $isLogin ? 'Pratinjau Halaman Login &rarr;' : 'Pratinjau Halaman &rarr;' }}
                    </a>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.category-contents.index') }}" class="px-4 py-2 text-xs text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-xl">Batal</a>
                        <button type="submit" class="px-5 py-2.5 {{ $isLogin ? 'bg-blue-600 hover:bg-blue-500 text-white' : 'bg-emerald-500 hover:bg-emerald-400 text-slate-950' }} font-bold text-xs rounded-xl shadow-lg transition active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
