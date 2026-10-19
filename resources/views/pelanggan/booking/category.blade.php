<x-pelanggan-layout title="Pilih Kategori - Grooming Lab & MUA">
    <div class="space-y-6 max-w-xl mx-auto py-4">
        <div class="text-center">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-400">Langkah 1 dari 2</span>
            <h1 class="text-2xl font-extrabold text-white mt-1">Pilih Kategori Layanan</h1>
            <p class="text-sm text-slate-400 mt-1">Pilih tipe perawatan yang ingin Anda reservasi</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Barbershop Card -->
            <a href="{{ route('pelanggan.booking.create', 'barbershop') }}"
               class="group relative overflow-hidden rounded-3xl bg-slate-900 border-2 border-slate-800 hover:border-amber-500 p-6 transition-all duration-300 shadow-xl flex flex-col justify-between hover:-translate-y-1">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-400 flex items-center justify-center mb-4 group-hover:scale-110 transition duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879a3 3 0 11-4.242-4.242L7.757 7.757m0 0l-2.879-2.879a3 3 0 10-4.242 4.242L3.515 12" />
                        </svg>
                    </div>

                    <h2 class="text-xl font-bold text-white group-hover:text-amber-400 transition">
                        Layanan Barbershop
                    </h2>
                    <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                        Potong rambut pria, modern fade, hair wash, shaving, creambath, dan penataan styling kapster berpengalaman.
                    </p>

                    <div class="mt-4 flex flex-wrap gap-1.5">
                        <span class="px-2 py-0.5 rounded-md bg-slate-800 text-[10px] text-slate-300">Haircut</span>
                        <span class="px-2 py-0.5 rounded-md bg-slate-800 text-[10px] text-slate-300">Shaving</span>
                        <span class="px-2 py-0.5 rounded-md bg-slate-800 text-[10px] text-slate-300">Coloring</span>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-800 flex items-center justify-between text-xs font-bold text-amber-400">
                    <span>Mulai dari Rp 20.000</span>
                    <span class="inline-flex items-center gap-1 group-hover:translate-x-1 transition">
                        Pilih &rarr;
                    </span>
                </div>
            </a>

            <!-- MUA Card -->
            <a href="{{ route('pelanggan.booking.create', 'mua') }}"
               class="group relative overflow-hidden rounded-3xl bg-slate-900 border-2 border-slate-800 hover:border-pink-500 p-6 transition-all duration-300 shadow-xl flex flex-col justify-between hover:-translate-y-1">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-pink-500/10 border border-pink-500/30 text-pink-400 flex items-center justify-center mb-4 group-hover:scale-110 transition duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>

                    <h2 class="text-xl font-bold text-white group-hover:text-pink-400 transition">
                        Layanan MUA
                    </h2>
                    <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                        Make-up artist profesional untuk pesta, wisuda, bridal wedding, pemotretan pre-wedding, maupun daily natural look.
                    </p>

                    <div class="mt-4 flex flex-wrap gap-1.5">
                        <span class="px-2 py-0.5 rounded-md bg-slate-800 text-[10px] text-slate-300">Party</span>
                        <span class="px-2 py-0.5 rounded-md bg-slate-800 text-[10px] text-slate-300">Graduation</span>
                        <span class="px-2 py-0.5 rounded-md bg-slate-800 text-[10px] text-slate-300">Bridal</span>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-800 flex items-center justify-between text-xs font-bold text-pink-400">
                    <span>Mulai dari Rp 150.000</span>
                    <span class="inline-flex items-center gap-1 group-hover:translate-x-1 transition">
                        Pilih &rarr;
                    </span>
                </div>
            </a>
        </div>

        <div class="text-center pt-2">
            <a href="{{ route('pelanggan.home') }}" class="text-xs text-slate-500 hover:text-slate-300 transition">
                &larr; Kembali ke Home
            </a>
        </div>
    </div>
</x-pelanggan-layout>
