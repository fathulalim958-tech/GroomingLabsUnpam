<x-admin-layout title="Tambah Staf - Grooming Lab & MUA">
    <div class="max-w-xl mx-auto py-4 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-white">Tambah Staf Baru</h1>
                <p class="text-xs text-slate-400 mt-1">Tambahkan tenaga kapster (barber) atau penata rias (MUA)</p>
            </div>
            <a href="{{ route('admin.staffs.index') }}" class="text-xs text-slate-400 hover:text-white transition">
                &larr; Kembali
            </a>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl">
            <form method="POST" action="{{ route('admin.staffs.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-300 mb-1">Nama Staf *</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        placeholder="Contoh: Aldi Pratama" />
                    @error('name')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-xs font-semibold text-slate-300 mb-1">Peran / Profesi *</label>
                    <select id="role" name="role" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="kapster" {{ old('role') === 'kapster' ? 'selected' : '' }}>Kapster / Barber (Perawatan Pria)</option>
                        <option value="mua" {{ old('role') === 'mua' ? 'selected' : '' }}>MUA (Penata Rias & Makeup)</option>
                    </select>
                    @error('role')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-xs font-semibold text-slate-300 mb-1">Nomor WhatsApp / HP</label>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        placeholder="081234567890" />
                    @error('phone')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked
                            class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-emerald-500 focus:ring-emerald-500">
                        <span class="text-xs text-slate-300">Staf Aktif Bekerja</span>
                    </label>
                </div>

                <div class="pt-4 flex justify-end gap-2">
                    <a href="{{ route('admin.staffs.index') }}" class="px-4 py-2.5 text-xs text-slate-400 hover:text-white rounded-xl">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg transition">
                        Simpan Data Staf
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>

