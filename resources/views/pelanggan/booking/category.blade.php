<x-pelanggan-layout title="Pilih Kategori - LPKKS GROOMING LAB">
    <div class="space-y-6 max-w-xl mx-auto py-4">
        <div class="text-center">
            <span class="text-xs font-bold uppercase tracking-wider text-[#1769AA] dark:text-[#93C5FD]">Langkah 1 dari 2</span>
            <h1 class="text-2xl font-extrabold text-[#0B2D50] dark:text-white mt-1">Pilih Kategori Layanan</h1>
            <p class="text-sm text-[#64748B] dark:text-slate-400 mt-1">Pilih tipe perawatan yang ingin Anda reservasi</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Barbershop Card -->
            <a href="{{ route('pelanggan.booking.create', 'barbershop') }}"
               class="group relative overflow-hidden rounded-3xl bg-white dark:bg-slate-900 border-2 border-[#E2EAF4] dark:border-slate-800 hover:border-[#1769AA] p-6 transition-all duration-300 shadow-sm dark:shadow-xl flex flex-col justify-between hover:-translate-y-1">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-[#EAF3FF] dark:bg-[#1769AA]/20 border border-[#D7E7FA] dark:border-[#1769AA]/30 text-[#1769AA] dark:text-[#93C5FD] flex items-center justify-center mb-4 group-hover:scale-110 transition duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879a3 3 0 11-4.242-4.242L7.757 7.757m0 0l-2.879-2.879a3 3 0 10-4.242 4.242L3.515 12" />
                        </svg>
                    </div>

                    <h2 class="text-xl font-bold text-[#0B2D50] dark:text-white group-hover:text-[#1769AA] dark:group-hover:text-[#93C5FD] transition">
                        Layanan Barbershop
                    </h2>
                    <p class="text-xs text-[#64748B] dark:text-slate-400 mt-2 leading-relaxed">
                        Potong rambut pria, modern fade, hair wash, shaving, creambath, dan penataan styling kapster berpengalaman.
                    </p>

                    <div class="mt-4 flex flex-wrap gap-1.5">
                        <span class="px-2 py-0.5 rounded-md bg-[#F7FAFE] dark:bg-slate-800 text-[10px] text-[#0B2D50] dark:text-slate-300 border border-[#E2EAF4] dark:border-slate-700">Haircut</span>
                        <span class="px-2 py-0.5 rounded-md bg-[#F7FAFE] dark:bg-slate-800 text-[10px] text-[#0B2D50] dark:text-slate-300 border border-[#E2EAF4] dark:border-slate-700">Shaving</span>
                        <span class="px-2 py-0.5 rounded-md bg-[#F7FAFE] dark:bg-slate-800 text-[10px] text-[#0B2D50] dark:text-slate-300 border border-[#E2EAF4] dark:border-slate-700">Coloring</span>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-[#E2EAF4] dark:border-slate-800 flex items-center justify-between text-xs font-bold text-[#1769AA] dark:text-[#93C5FD]">
                    <span>Mulai dari Rp 20.000</span>
                    <span class="inline-flex items-center gap-1 group-hover:translate-x-1 transition">
                        Pilih &rarr;
                    </span>
                </div>
            </a>

            <!-- MUA Card -->
            <a href="{{ route('pelanggan.booking.create', 'mua') }}"
               class="group relative overflow-hidden rounded-3xl bg-white dark:bg-slate-900 border-2 border-[#E2EAF4] dark:border-slate-800 hover:border-[#1769AA] p-6 transition-all duration-300 shadow-sm dark:shadow-xl flex flex-col justify-between hover:-translate-y-1">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-[#EAF3FF] dark:bg-[#1769AA]/20 border border-[#D7E7FA] dark:border-[#1769AA]/30 text-[#1769AA] dark:text-[#93C5FD] flex items-center justify-center mb-4 group-hover:scale-110 transition duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>

                    <h2 class="text-xl font-bold text-[#0B2D50] dark:text-white group-hover:text-[#1769AA] dark:group-hover:text-[#93C5FD] transition">
                        Layanan MUA
                    </h2>
                    <p class="text-xs text-[#64748B] dark:text-slate-400 mt-2 leading-relaxed">
                        Make-up artist profesional untuk pesta, wisuda, bridal wedding, pemotretan pre-wedding, maupun daily natural look.
                    </p>

                    <div class="mt-4 flex flex-wrap gap-1.5">
                        <span class="px-2 py-0.5 rounded-md bg-[#F7FAFE] dark:bg-slate-800 text-[10px] text-[#0B2D50] dark:text-slate-300 border border-[#E2EAF4] dark:border-slate-700">Party</span>
                        <span class="px-2 py-0.5 rounded-md bg-[#F7FAFE] dark:bg-slate-800 text-[10px] text-[#0B2D50] dark:text-slate-300 border border-[#E2EAF4] dark:border-slate-700">Graduation</span>
                        <span class="px-2 py-0.5 rounded-md bg-[#F7FAFE] dark:bg-slate-800 text-[10px] text-[#0B2D50] dark:text-slate-300 border border-[#E2EAF4] dark:border-slate-700">Bridal</span>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-[#E2EAF4] dark:border-slate-800 flex items-center justify-between text-xs font-bold text-[#1769AA] dark:text-[#93C5FD]">
                    <span>Mulai dari Rp 150.000</span>
                    <span class="inline-flex items-center gap-1 group-hover:translate-x-1 transition">
                        Pilih &rarr;
                    </span>
                </div>
            </a>
        </div>

        <div class="text-center pt-2">
            <a href="{{ route('pelanggan.home') }}" class="text-xs text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition">
                &larr; Kembali ke Home
            </a>
        </div>
    </div>
</x-pelanggan-layout>
