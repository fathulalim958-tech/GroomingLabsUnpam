<x-admin-layout title="Edit Layanan - Grooming Lab & MUA">
    <div class="max-w-xl mx-auto py-4 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Edit Layanan</h1>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Perbarui data layanan {{ $service->name }}</p>
            </div>
            <a href="{{ route('admin.services.index') }}" class="text-xs text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">
                &larr; Kembali
            </a>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm dark:shadow-xl">
            <form method="POST" action="{{ route('admin.services.update', $service) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Layanan *</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $service->name) }}" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kategori Layanan *</label>
                    <select id="category" name="category" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="barbershop" {{ old('category', $service->category) === 'barbershop' ? 'selected' : '' }}>Barbershop</option>
                        <option value="mua" {{ old('category', $service->category) === 'mua' ? 'selected' : '' }}>MUA Studio</option>
                    </select>
                    @error('category')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Deskripsi Layanan</label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">{{ old('description', $service->description) }}</textarea>
                    @error('description')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Harga Layanan (Rp) *</label>
                        <input id="price" type="number" name="price" value="{{ old('price', $service->price) }}" required min="0" step="1000"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                        @error('price')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="duration_minutes" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Estimasi Durasi (Menit) *</label>
                        <input id="duration_minutes" type="number" name="duration_minutes" value="{{ old('duration_minutes', $service->duration_minutes) }}" required min="5" max="480"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                        @error('duration_minutes')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 rounded bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-700 text-emerald-500 focus:ring-emerald-500">
                        <span class="text-xs text-slate-700 dark:text-slate-300">Layanan Aktif (Dapat dipesan pelanggan)</span>
                    </label>
                </div>

                <div class="pt-4 flex justify-end gap-2">
                    <a href="{{ route('admin.services.index') }}" class="px-4 py-2.5 text-xs text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-xl">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg transition">
                        Perbarui Layanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
