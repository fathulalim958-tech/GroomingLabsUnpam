<x-pelanggan-layout title="Form Booking - {{ ucfirst($category) }} - LPKKS GROOMING LAB">
    <div class="max-w-xl mx-auto py-4" x-data="bookingForm()">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider {{ $category === 'barbershop' ? 'text-[#1769AA] dark:text-[#93C5FD]' : 'text-pink-600 dark:text-pink-400' }}">
                    Langkah 2 dari 2 &bull; Layanan {{ ucfirst($category) }}
                </span>
                <h1 class="text-2xl font-extrabold text-[#0B2D50] dark:text-white mt-0.5">Form Pemesanan</h1>
            </div>
            <a href="{{ route('pelanggan.booking.category') }}" class="text-xs text-[#64748B] dark:text-slate-400 hover:text-[#0B2D50] dark:hover:text-white transition">
                Ganti Kategori
            </a>
        </div>

        <form x-ref="bookingForm" method="POST" action="{{ route('pelanggan.booking.store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="category" value="{{ $category }}">

            <!-- 1. PILIH HARI & TANGGAL (BAHASA INDONESIA) -->
            <div class="bg-white dark:bg-slate-900 border border-[#E2EAF4] dark:border-slate-800 rounded-2xl p-5 space-y-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold text-[#0B2D50] dark:text-white uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $category === 'barbershop' ? 'bg-[#1769AA]' : 'bg-pink-500' }}"></span>
                        1. Pilih Hari & Tanggal
                    </h2>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#F7FAFE] dark:bg-slate-800 text-[#0B2D50] dark:text-slate-300 border border-[#E2EAF4] dark:border-slate-700" x-text="selectedDateLabel"></span>
                </div>

                <input type="hidden" name="booking_date" :value="selectedDate">

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                    @foreach ($availableDates as $d)
                        @php
                            $cDate = \Carbon\Carbon::parse($d['date']);
                            $isClosed = !empty($d['is_closed']);
                            $isHoliday = !empty($d['is_holiday']);
                        @endphp
                        @if ($isClosed)
                            <div class="p-3 rounded-xl border border-rose-200 dark:border-rose-900/50 bg-rose-50/70 dark:bg-rose-950/30 text-rose-700 dark:text-rose-400 opacity-85 flex flex-col justify-between cursor-not-allowed select-none"
                                 title="{{ $isHoliday ? 'Tanggal Merah (Libur Nasional): ' . $d['holiday_name'] : 'Tutup' }}">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold uppercase text-rose-800 dark:text-rose-300">{{ $d['day_name'] }}</span>
                                    <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded bg-rose-500/20 text-rose-700 dark:text-rose-300">
                                        {{ $isHoliday ? 'Libur' : 'Tutup' }}
                                    </span>
                                </div>
                                <div class="mt-1">
                                    <span class="text-base font-extrabold text-rose-900 dark:text-rose-200 line-through">{{ $cDate->format('d') }}</span>
                                    <span class="text-xs text-rose-700/80 dark:text-rose-400">{{ $d['month_year'] ?? $cDate->translatedFormat('M Y') }}</span>
                                </div>
                                @if ($isHoliday)
                                    <div class="mt-1.5 text-[10px] font-semibold leading-tight line-clamp-2 text-rose-700 dark:text-rose-300">
                                        🔴 {{ $d['holiday_name'] }}
                                    </div>
                                @endif
                            </div>
                        @else
                            <button type="button"
                                    @click="setDate('{{ $d['date'] }}', '{{ $d['label'] }}', '{{ $d['open_time'] }}', '{{ $d['close_time'] }}')"
                                    class="p-3 rounded-xl border text-left transition flex flex-col justify-between"
                                    :class="selectedDate === '{{ $d['date'] }}'
                                        ? '{{ $category === 'barbershop' ? 'bg-blue-50/80 border-[#1769AA] text-[#0B2D50] dark:bg-slate-800 dark:border-sky-400 dark:text-white shadow-sm ring-1 ring-[#1769AA]/30' : 'bg-pink-50/80 border-pink-500 text-pink-900 dark:bg-slate-800 dark:border-pink-400 dark:text-white shadow-sm ring-1 ring-pink-500/30' }}'
                                        : 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:border-slate-300 dark:hover:border-slate-700'">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold uppercase">{{ $d['day_name'] }}</span>
                                    <span class="w-2 h-2 rounded-full"
                                          :class="selectedDate === '{{ $d['date'] }}' ? '{{ $category === 'barbershop' ? 'bg-[#1769AA]' : 'bg-pink-500' }}' : 'bg-transparent'"></span>
                                </div>
                                <div class="mt-1">
                                    <span class="text-base font-extrabold text-slate-900 dark:text-white">{{ $cDate->format('d') }}</span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ $d['month_year'] ?? $cDate->translatedFormat('M Y') }}</span>
                                </div>
                            </button>
                        @endif
                    @endforeach
                </div>
                @error('booking_date')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 2. PILIH 1 LAYANAN -->
            <div class="bg-white dark:bg-slate-900 border border-[#E2EAF4] dark:border-slate-800 rounded-2xl p-5 space-y-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold text-[#0B2D50] dark:text-white uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $category === 'barbershop' ? 'bg-[#1769AA]' : 'bg-pink-500' }}"></span>
                        2. Pilih Layanan
                    </h2>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#F7FAFE] dark:bg-slate-800 text-[#0B2D50] dark:text-slate-300 border border-[#E2EAF4] dark:border-slate-700">
                        Pilih 1 Layanan
                    </span>
                </div>

                <div class="space-y-2.5">
                    @forelse ($services as $service)
                        <label class="relative flex items-center justify-between p-3.5 rounded-xl border transition cursor-pointer"
                               :class="selectedService && selectedService.id === {{ $service->id }}
                                   ? '{{ $category === 'barbershop' ? 'bg-[#EAF3FF] dark:bg-sky-950/70 border-[#1769AA] dark:border-sky-400 shadow-sm ring-1 ring-[#1769AA]/30 dark:ring-sky-400/40' : 'bg-pink-500/10 dark:bg-pink-950/60 border-pink-500 dark:border-pink-400 shadow-md ring-1 ring-pink-500/30 dark:ring-pink-400/40' }}'
                                   : 'bg-[#F7FAFE] dark:bg-slate-800/40 border-[#E2EAF4] dark:border-slate-800 hover:border-[#1769AA]/30 dark:hover:border-slate-700'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="services[]" value="{{ $service->id }}"
                                       @change="selectService({{ $service->id }}, '{{ addslashes($service->name) }}', {{ $service->price }}, {{ $service->duration_minutes }}, '{{ $service->formatted_price }}')"
                                       :checked="selectedService && selectedService.id === {{ $service->id }}"
                                       class="w-4 h-4 {{ $category === 'barbershop' ? 'text-[#1769AA] focus:ring-[#1769AA]' : 'text-pink-500 focus:ring-pink-500' }} border-slate-300 dark:border-slate-700">
                                <div>
                                    <p class="font-bold text-[#0B2D50] dark:text-white text-sm">{{ $service->name }}</p>
                                    <p class="text-xs text-[#64748B] dark:text-slate-300 mt-0.5">{{ $service->description }}</p>
                                    <span class="inline-block text-[11px] text-[#64748B] dark:text-slate-400 mt-1">
                                        ⏱ {{ $service->duration_minutes }} menit
                                    </span>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0 ms-3">
                                <span class="text-sm font-extrabold {{ $category === 'barbershop' ? 'text-[#1769AA] dark:text-sky-300' : 'text-pink-600 dark:text-pink-400' }}">
                                    {{ $service->formatted_price }}
                                </span>
                            </div>
                        </label>
                    @empty
                        <p class="text-sm text-slate-500 text-center py-4">Belum ada layanan aktif pada kategori ini.</p>
                    @endforelse
                </div>
                @error('services')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 3. PILIH KAPSTER / MUA YANG TERSEDIA PADA TANGGAL TERSEBUT -->
            <div class="bg-white dark:bg-slate-900 border border-[#E2EAF4] dark:border-slate-800 rounded-2xl p-5 space-y-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-[#0B2D50] dark:text-white uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full {{ $category === 'barbershop' ? 'bg-[#1769AA]' : 'bg-pink-500' }}"></span>
                            3. Pilih {{ $category === 'barbershop' ? 'Kapster Barberman' : 'Penata Rias / MUA' }}
                        </h2>
                        <p class="text-xs text-[#64748B] dark:text-slate-400 mt-0.5">
                            Pilih petugas yang bertugas pada tanggal yang Anda tentukan
                        </p>
                    </div>
                    <span class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400" x-text="selectedStaffName"></span>
                </div>

                <input type="hidden" name="staff_id" :value="selectedStaffId">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <!-- Opsi Bebas / Siapa Saja -->
                    <label @click="selectStaff(null, 'Siapa Saja yang Tersedia')"
                           class="relative flex items-center gap-3 p-3.5 rounded-xl border transition cursor-pointer select-none"
                           :class="selectedStaffId === null
                               ? '{{ $category === 'barbershop' ? 'bg-[#EAF3FF] dark:bg-sky-950/70 border-[#1769AA] dark:border-sky-400 shadow-sm ring-1 ring-[#1769AA]/30 dark:ring-sky-400/40 text-[#0B2D50] dark:text-white' : 'bg-pink-500/10 dark:bg-pink-950/60 border-pink-500 dark:border-pink-400 shadow-sm ring-1 ring-pink-500/30 dark:ring-pink-400/40 text-pink-900 dark:text-white' }}'
                               : 'bg-[#F7FAFE] dark:bg-slate-800/40 border-[#E2EAF4] dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 text-slate-700 dark:text-slate-300'">
                        <input type="radio" name="staff_choice" value="" :checked="selectedStaffId === null" class="sr-only">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400/20 to-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-base flex-shrink-0">
                            ⭐
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-bold text-xs text-[#0B2D50] dark:text-white">Siapa Saja yang Tersedia</p>
                            <p class="text-[10px] text-slate-500 dark:text-slate-300 mt-0.5">Rekomendasi terbaik yang bertugas</p>
                        </div>
                        <div class="w-4 h-4 rounded-full border flex items-center justify-center flex-shrink-0"
                             :class="selectedStaffId === null ? 'border-emerald-500 bg-emerald-500' : 'border-slate-300 dark:border-slate-600'">
                            <span class="w-1.5 h-1.5 rounded-full bg-white" x-show="selectedStaffId === null"></span>
                        </div>
                    </label>

                    <!-- Daftar Staf Tersedia -->
                    <template x-for="st in staffList" :key="st.id">
                        <label @click="isStaffAvailable(st) && selectStaff(st.id, st.name)"
                               class="relative flex items-center gap-3 p-3.5 rounded-xl border transition select-none"
                               :class="!isStaffAvailable(st)
                                   ? 'cursor-not-allowed bg-slate-50/60 dark:bg-slate-900/60 border-dashed border-slate-300 dark:border-slate-800 text-slate-400 dark:text-slate-400'
                                   : (selectedStaffId === st.id
                                       ? '{{ $category === 'barbershop' ? 'bg-[#EAF3FF] dark:bg-sky-950/70 border-[#1769AA] dark:border-sky-400 shadow-sm ring-1 ring-[#1769AA]/30 dark:ring-sky-400/40 text-[#0B2D50] dark:text-white cursor-pointer' : 'bg-pink-500/10 dark:bg-pink-950/60 border-pink-500 dark:border-pink-400 shadow-sm ring-1 ring-pink-500/30 dark:ring-pink-400/40 text-pink-900 dark:text-white cursor-pointer' }}'
                                       : 'bg-[#F7FAFE] dark:bg-slate-800/40 border-[#E2EAF4] dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 text-slate-700 dark:text-slate-300 cursor-pointer')">
                            <input type="radio" name="staff_choice" :value="st.id" :checked="selectedStaffId === st.id" :disabled="!isStaffAvailable(st)" class="sr-only">

                            <template x-if="st.avatar">
                                <img :src="st.avatar" :alt="st.name" class="w-10 h-10 rounded-xl object-cover ring-1 ring-emerald-500/30 flex-shrink-0" :class="!isStaffAvailable(st) ? 'grayscale opacity-60' : ''">
                            </template>
                            <template x-if="!st.avatar">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br ring-1 flex items-center justify-center font-black text-xs flex-shrink-0"
                                     :class="isStaffAvailable(st)
                                         ? 'from-emerald-500/20 via-slate-100 dark:via-slate-800 to-emerald-500/10 ring-emerald-500/30 text-emerald-600 dark:text-emerald-400'
                                         : 'from-slate-200/50 to-slate-300/30 dark:from-slate-800 dark:to-slate-800/60 ring-slate-400/20 text-slate-400 dark:text-slate-500'">
                                    <span x-text="st.initials"></span>
                                </div>
                            </template>

                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-xs truncate" :class="isStaffAvailable(st) ? 'text-[#0B2D50] dark:text-white' : 'text-slate-500 dark:text-slate-400'" x-text="st.name"></p>
                                <div class="mt-0.5">
                                    <template x-if="isStaffAvailable(st)">
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 dark:text-emerald-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Tersedia
                                        </span>
                                    </template>
                                    <template x-if="!isStaffAvailable(st)">
                                        <span class="inline-flex items-center gap-1 text-[10px] text-slate-400 dark:text-slate-500 font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 dark:bg-slate-600"></span>
                                            Tidak Tersedia
                                            <span class="text-[9px] opacity-80" x-show="getStaffUnavailableReason(st)" x-text="'(' + getStaffUnavailableReason(st) + ')'"></span>
                                        </span>
                                    </template>
                                </div>
                            </div>

                            <div class="w-4 h-4 rounded-full border flex items-center justify-center flex-shrink-0"
                                 :class="selectedStaffId === st.id ? 'border-emerald-500 bg-emerald-500' : 'border-slate-300 dark:border-slate-600'">
                                <span class="w-1.5 h-1.5 rounded-full bg-white" x-show="selectedStaffId === st.id"></span>
                            </div>
                        </label>
                    </template>
                </div>
                @error('staff_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 4. PILIH SESI JAM RESERVASI (RENTANG 1 JAM) -->
            <div class="bg-white dark:bg-slate-900 border border-[#E2EAF4] dark:border-slate-800 rounded-2xl p-5 space-y-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold text-[#0B2D50] dark:text-white uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $category === 'barbershop' ? 'bg-[#1769AA]' : 'bg-pink-500' }}"></span>
                        4. Pilih Sesi Jam (Rentang 1 Jam)
                    </h2>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400" x-show="selectedTime">
                        Dipilih: <strong class="{{ $category === 'barbershop' ? 'text-[#1769AA] dark:text-sky-300' : 'text-pink-600 dark:text-pink-400' }}" x-text="selectedTimeLabel"></strong>
                    </span>
                </div>

                <input type="hidden" name="booking_time" :value="selectedTime">

                @php
                    $slots = [
                        ['value' => '09:00', 'label' => '09.00 - 10.00'],
                        ['value' => '10:00', 'label' => '10.00 - 11.00'],
                        ['value' => '11:00', 'label' => '11.00 - 12.00'],
                        ['value' => '12:00', 'label' => '12.00 - 13.00'],
                        ['value' => '13:00', 'label' => '13.00 - 14.00'],
                        ['value' => '14:00', 'label' => '14.00 - 15.00'],
                        ['value' => '15:00', 'label' => '15.00 - 16.00'],
                        ['value' => '16:00', 'label' => '16.00 - 17.00'],
                        ['value' => '17:00', 'label' => '17.00 - 18.00'],
                        ['value' => '18:00', 'label' => '18.00 - 19.00'],
                        ['value' => '19:00', 'label' => '19.00 - 20.00'],
                        ['value' => '20:00', 'label' => '20.00 - 21.00'],
                    ];
                @endphp

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                    @foreach ($slots as $slot)
                        <button type="button"
                                :disabled="!isSlotAvailable('{{ $slot['value'] }}')"
                                @click="isSlotAvailable('{{ $slot['value'] }}') && setTime('{{ $slot['value'] }}', '{{ $slot['label'] }}')"
                                :title="isSlotPast('{{ $slot['value'] }}') ? 'Waktu sesi jam ini sudah terlewat' : (isSlotFull('{{ $slot['value'] }}') ? 'Slot waktu jam ini sudah penuh' : (!isSlotAvailable('{{ $slot['value'] }}') ? 'Di luar jam operasional' : 'Tersedia'))"
                                class="py-2.5 px-3 rounded-xl border text-center transition font-mono text-xs font-bold flex flex-col items-center justify-center gap-1 relative overflow-hidden"
                                :class="!isSlotAvailable('{{ $slot['value'] }}')
                                    ? (isSlotFull('{{ $slot['value'] }}')
                                        ? 'opacity-60 cursor-not-allowed bg-rose-50 dark:bg-rose-950/20 border-rose-200 dark:border-rose-800/40 text-rose-500 dark:text-rose-400'
                                        : (isSlotPast('{{ $slot['value'] }}')
                                            ? 'opacity-35 cursor-not-allowed bg-slate-100 dark:bg-slate-900/40 border-slate-200 dark:border-slate-800/80 text-slate-400 dark:text-slate-500'
                                            : 'opacity-40 cursor-not-allowed bg-slate-100 dark:bg-slate-900/60 border-dashed border-slate-300 dark:border-slate-800 text-slate-400'))
                                    : (selectedTime === '{{ $slot['value'] }}'
                                        ? '{{ $category === 'barbershop' ? 'bg-[#0B2D50] dark:bg-sky-950 text-white font-bold border-[#0B2D50] dark:border-sky-400 dark:ring-1 dark:ring-sky-400 shadow-sm' : 'bg-pink-600 dark:bg-pink-950 text-white font-bold border-pink-600 dark:border-pink-400 dark:ring-1 dark:ring-pink-400 shadow-sm' }}'
                                        : 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:border-slate-300 dark:hover:border-slate-700')">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $slot['label'] }}</span>
                            </div>
                            <template x-if="isSlotFull('{{ $slot['value'] }}')">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-rose-500/20 text-rose-500 dark:text-rose-400 border border-rose-500/40">
                                    Penuh
                                </span>
                            </template>
                            <template x-if="!isSlotFull('{{ $slot['value'] }}') && isSlotPast('{{ $slot['value'] }}')">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-slate-200/70 dark:bg-slate-800/80 text-slate-500 dark:text-slate-400 border border-slate-300/60 dark:border-slate-700/60">
                                    Terlewat
                                </span>
                            </template>
                            <template x-if="!isSlotFull('{{ $slot['value'] }}') && !isSlotPast('{{ $slot['value'] }}') && isSlotAvailable('{{ $slot['value'] }}') && getRemainingSlots('{{ $slot['value'] }}') !== null">
                                <span class="text-[10px] font-normal" :class="selectedTime === '{{ $slot['value'] }}' ? '{{ $category === 'barbershop' ? 'text-white font-bold' : 'text-white font-bold' }}' : 'text-[#64748B] dark:text-slate-400'">
                                    Sisa <span class="font-bold font-mono" :class="selectedTime === '{{ $slot['value'] }}' ? 'text-white' : 'text-emerald-500'" x-text="getRemainingSlots('{{ $slot['value'] }}')"></span> slot
                                </span>
                            </template>
                        </button>
                    @endforeach
                </div>
                <div class="flex items-center flex-wrap gap-3 text-[11px] text-[#64748B] dark:text-slate-400 pt-1">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Tersedia
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span> Penuh (Tidak Dapat Dipilih)
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-slate-400"></span> Sesi jam sudah lewat
                    </span>
                </div>
                @error('booking_time')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 5. CATATAN KHUSUS (OPSIONAL) -->
            <div class="bg-white dark:bg-slate-900 border border-[#E2EAF4] dark:border-slate-800 rounded-2xl p-5 space-y-4 shadow-sm">
                <h2 class="text-sm font-bold text-[#0B2D50] dark:text-white uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full {{ $category === 'barbershop' ? 'bg-[#1769AA]' : 'bg-pink-500' }}"></span>
                    5. Catatan Khusus (Opsional)
                </h2>

                <div>
                    <textarea name="notes" x-model="notes" rows="3"
                              class="w-full px-4 py-3 bg-[#F7FAFE] dark:bg-slate-800/80 border border-[#E2EAF4] dark:border-slate-700 rounded-xl text-[#0B2D50] dark:text-white placeholder-slate-400 dark:placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-[#1769AA]"
                              placeholder="Contoh: Kondisi rambut tebal, kulit sensitif, atau referensi gaya potongan fade tipis...">{{ old('notes') }}</textarea>
                    <p class="text-[11px] text-[#64748B] dark:text-slate-400 mt-1">Sampaikan preferensi gaya atau catatan penting lainnya kepada petugas.</p>
                </div>
            </div>

            <!-- 6. RINGKASAN ESTIMASI BIAYA & TOMBOL PESAN -->
            <div class="bg-white dark:bg-slate-900 border border-[#E2EAF4] dark:border-slate-800 rounded-2xl p-5 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-[#64748B] dark:text-slate-400 uppercase tracking-wider">Ringkasan Estimasi Biaya</h3>

                <!-- Info Pilihan Singkat -->
                <div class="space-y-1.5 py-1 border-b border-[#E2EAF4] dark:border-slate-800 text-xs">
                    <div class="flex items-center justify-between text-[#64748B] dark:text-slate-400">
                        <span>Tanggal & Hari</span>
                        <span class="font-bold text-[#0B2D50] dark:text-white" x-text="selectedDateLabel || '-'"></span>
                    </div>
                    <div class="flex items-center justify-between text-[#64748B] dark:text-slate-400">
                        <span>Petugas</span>
                        <span class="font-bold text-[#0B2D50] dark:text-white" x-text="selectedStaffName"></span>
                    </div>
                    <div class="flex items-center justify-between text-[#64748B] dark:text-slate-400">
                        <span>Sesi Waktu</span>
                        <span class="font-mono font-bold text-[#0B2D50] dark:text-white" x-text="selectedTimeLabel ? selectedTimeLabel + ' WIB' : '-'"></span>
                    </div>
                    <div class="flex items-center justify-between text-[#64748B] dark:text-slate-400">
                        <span>Total Durasi Estimasi</span>
                        <span class="font-semibold text-[#0B2D50] dark:text-white" x-text="(selectedService ? selectedService.duration : 0) + ' Menit'">0 Menit</span>
                    </div>
                </div>

                <!-- Detail Biaya Layanan & Diskon Member -->
                <template x-if="hasMemberDiscount && selectedService">
                    <div class="space-y-1.5 py-1 border-b border-[#E2EAF4] dark:border-slate-800 text-xs">
                        <div class="flex items-center justify-between text-[#64748B] dark:text-slate-400">
                            <span>Harga Normal</span>
                            <span class="font-mono line-through" x-text="formatRupiah(selectedService.price)"></span>
                        </div>
                        <div class="flex items-center justify-between font-semibold text-[#1769AA] dark:text-[#93C5FD]">
                            <span class="flex items-center gap-1">
                                <span>⭐ Potongan Member</span>
                                <span class="text-[10px] bg-[#EAF3FF] dark:bg-sky-950/80 text-[#1769AA] dark:text-sky-300 border border-blue-200 dark:border-sky-800 px-1.5 py-0.5 rounded" x-text="'(Sisa: ' + memberDiscountRemaining + 'x)'"></span>
                            </span>
                            <span class="font-mono" x-text="'- ' + formatRupiah(getDiscountAmount())"></span>
                        </div>
                    </div>
                </template>

                <div class="flex items-center justify-between text-base py-1">
                    <span class="font-bold text-[#0B2D50] dark:text-white">Total Biaya Layanan</span>
                    <span class="text-xl font-extrabold {{ $category === 'barbershop' ? 'text-[#0B2D50] dark:text-[#93C5FD]' : 'text-pink-600 dark:text-pink-400' }}"
                          x-text="formatRupiah(getFinalPrice())">Rp 0</span>
                </div>

                <button type="button"
                        @click="openConfirmModal()"
                        :disabled="!selectedService || !selectedDate || !selectedTime"
                        class="w-full py-3.5 px-4 {{ $category === 'barbershop' ? 'bg-[#0B2D50] dark:bg-[#1769AA] hover:bg-[#164A7A] dark:hover:bg-[#1f7ecb] text-white shadow-[#0B2D50]/20' : 'bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white shadow-pink-500/20' }} disabled:opacity-50 disabled:cursor-not-allowed font-extrabold rounded-xl shadow-lg transition transform active:scale-95 text-base flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Buat Pesanan & Ambil Antrian</span>
                </button>
            </div>
        </form>

        <!-- Pop-up Modal Konfirmasi Hasil Booking (FR-CUST-CONFIRM) -->
        <div x-show="showConfirmModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4"
             style="display: none;">

            <div @click.away="showConfirmModal = false"
                 class="relative bg-white dark:bg-slate-900 border border-[#E2EAF4] dark:border-slate-800 rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-5">

                <!-- Modal Header -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl {{ $category === 'barbershop' ? 'bg-[#EAF3FF] text-[#1769AA]' : 'bg-pink-500/10 text-pink-500' }} flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-[#0B2D50] dark:text-white">Konfirmasi Pemesanan</h3>
                        <p class="text-xs text-[#64748B] dark:text-slate-400">Pastikan rincian reservasi Anda sudah sesuai</p>
                    </div>
                </div>

                <!-- Summary Details Table -->
                <div class="bg-[#F7FAFE] dark:bg-slate-800/50 rounded-2xl p-4 border border-[#E2EAF4] dark:border-slate-800 divide-y divide-[#E2EAF4] dark:divide-slate-800 text-xs space-y-2.5">
                    <div class="flex justify-between items-center pt-1">
                        <span class="text-[#64748B] dark:text-slate-400">Kategori Layanan</span>
                        <span class="font-bold uppercase tracking-wider {{ $category === 'barbershop' ? 'text-[#1769AA] dark:text-[#93C5FD]' : 'text-pink-600 dark:text-pink-400' }}">
                            {{ ucfirst($category) }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center pt-2.5">
                        <span class="text-[#64748B] dark:text-slate-400">Tanggal Reservasi</span>
                        <span class="font-bold text-[#0B2D50] dark:text-white" x-text="selectedDateLabel"></span>
                    </div>

                    <div class="flex justify-between items-center pt-2.5">
                        <span class="text-[#64748B] dark:text-slate-400">Layanan Dipilih</span>
                        <span class="font-bold text-[#0B2D50] dark:text-white text-right" x-text="selectedService ? selectedService.name : '-'"></span>
                    </div>

                    <div class="flex justify-between items-center pt-2.5">
                        <span class="text-[#64748B] dark:text-slate-400">Petugas / {{ $category === 'barbershop' ? 'Kapster' : 'MUA' }}</span>
                        <span class="font-bold text-[#0B2D50] dark:text-white text-right" x-text="selectedStaffName"></span>
                    </div>

                    <div class="flex justify-between items-center pt-2.5">
                        <span class="text-[#64748B] dark:text-slate-400">Estimasi Durasi</span>
                        <span class="font-semibold text-[#0B2D50] dark:text-slate-200" x-text="selectedService ? selectedService.duration + ' Menit' : '-'"></span>
                    </div>

                    <div class="flex justify-between items-center pt-2.5">
                        <span class="text-[#64748B] dark:text-slate-400">Sesi Waktu</span>
                        <span class="font-mono font-bold text-[#0B2D50] dark:text-white" x-text="selectedTimeLabel + ' WIB'"></span>
                    </div>

                    <div class="flex justify-between items-start pt-2.5" x-show="notes.trim().length > 0">
                        <span class="text-[#64748B] dark:text-slate-400">Catatan Khusus</span>
                        <span class="font-medium text-[#0B2D50] dark:text-slate-300 text-right max-w-[60%]" x-text="notes"></span>
                    </div>

                    <!-- Detail Biaya Layanan & Diskon Member Modal -->
                    <template x-if="hasMemberDiscount && selectedService">
                        <div class="space-y-1.5 pt-2.5 border-t border-[#E2EAF4] dark:border-slate-800 text-xs">
                            <div class="flex justify-between items-center text-[#64748B] dark:text-slate-400">
                                <span>Tarif Normal</span>
                                <span class="font-mono line-through" x-text="formatRupiah(selectedService.price)"></span>
                            </div>
                            <div class="flex justify-between items-center text-[#1769AA] dark:text-[#93C5FD] font-semibold">
                                <span>⭐ Potongan Member</span>
                                <span class="font-mono" x-text="'- ' + formatRupiah(getDiscountAmount())"></span>
                            </div>
                        </div>
                    </template>

                    <div class="flex justify-between items-center pt-3 text-sm">
                        <span class="font-bold text-[#0B2D50] dark:text-white">Total Tagihan</span>
                        <span class="font-black text-base {{ $category === 'barbershop' ? 'text-[#0B2D50] dark:text-[#93C5FD]' : 'text-pink-600 dark:text-pink-400' }}"
                              x-text="formatRupiah(getFinalPrice())"></span>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button type="button"
                            @click="showConfirmModal = false"
                            class="w-full py-2.5 px-4 bg-[#F7FAFE] dark:bg-slate-800 hover:bg-[#EAF3FF] dark:hover:bg-slate-700 text-[#0B2D50] dark:text-slate-300 font-bold text-xs rounded-xl border border-[#E2EAF4] dark:border-slate-700 transition">
                        Periksa Kembali
                    </button>

                    <button type="button"
                            @click="submitBooking()"
                            class="w-full py-2.5 px-4 {{ $category === 'barbershop' ? 'bg-[#0B2D50] hover:bg-[#164A7A] text-white' : 'bg-pink-600 hover:bg-pink-500 text-white' }} font-bold text-xs rounded-xl shadow-lg transition">
                        Konfirmasi & Ambil Antrian
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @php
        $firstOpenDate = collect($availableDates)->first(fn($d) => empty($d['is_closed'])) ?? ($availableDates[0] ?? null);
    @endphp
    <script>
        const serverSlotsAvailability = @json($slotsAvailability ?? []);
        const serverStaffList = @json($staffList ?? []);

        function bookingForm() {
            return {
                todayDate: '{{ \Carbon\Carbon::now('Asia/Jakarta')->toDateString() }}',
                serverCurrentTime: '{{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i') }}',
                slotsAvailability: serverSlotsAvailability || {},
                staffList: serverStaffList || [],
                selectedStaffId: null,
                selectedStaffName: 'Siapa Saja yang Tersedia',
                selectedService: null,
                selectedDate: '{{ $firstOpenDate['date'] ?? '' }}',
                selectedDateLabel: '{{ $firstOpenDate['label'] ?? '' }}',
                currentOpenTime: '{{ $firstOpenDate['open_time'] ?? '09:00' }}',
                currentCloseTime: '{{ $firstOpenDate['close_time'] ?? '21:00' }}',
                selectedTime: '',
                selectedTimeLabel: '',
                notes: '{{ old('notes', '') }}',
                showConfirmModal: false,
                hasMemberDiscount: {{ auth()->user()->hasActiveMemberDiscount() ? 'true' : 'false' }},
                memberDiscount: {{ auth()->user()->member_discount ?: 0 }},
                memberDiscountType: '{{ auth()->user()->member_discount_type ?? 'percent' }}',
                memberDiscountRemaining: {{ auth()->user()->member_discount_remaining ?: 0 }},

                isStaffAvailable(staff) {
                    // 1. Cek jadwal ketersediaan hari bertugas
                    if (this.selectedDate) {
                        try {
                            const d = new Date(this.selectedDate + 'T00:00:00');
                            const dayOfWeek = d.getDay(); // 0 is Sunday, 1 is Monday, ...
                            if (staff.work_days && staff.work_days.length > 0) {
                                if (!staff.work_days.includes(dayOfWeek)) {
                                    return false;
                                }
                            }
                        } catch (e) {
                            // ignore
                        }
                    }

                    // 2. Cek kesesuaian layanan yang bisa dilayani oleh kasir/kapster
                    if (this.selectedService && this.selectedService.id) {
                        if (staff.service_ids && staff.service_ids.length > 0) {
                            if (!staff.service_ids.includes(this.selectedService.id)) {
                                return false;
                            }
                        } else if (staff.has_cashier) {
                            // Akun kasir ada namun layanan yang dipilih tidak terdaftar
                            return false;
                        }
                    }

                    return true;
                },

                getStaffUnavailableReason(staff) {
                    if (this.selectedDate && staff.work_days && staff.work_days.length > 0) {
                        try {
                            const d = new Date(this.selectedDate + 'T00:00:00');
                            const dayOfWeek = d.getDay();
                            if (!staff.work_days.includes(dayOfWeek)) {
                                return 'Jadwal Libur';
                            }
                        } catch (e) {}
                    }

                    if (this.selectedService && this.selectedService.id) {
                        if (staff.service_ids && staff.service_ids.length > 0) {
                            if (!staff.service_ids.includes(this.selectedService.id)) {
                                return 'Tidak melayani layanan ini';
                            }
                        } else if (staff.has_cashier) {
                            return 'Tidak melayani layanan ini';
                        }
                    }

                    return '';
                },

                selectStaff(id, name) {
                    this.selectedStaffId = id;
                    this.selectedStaffName = name;
                },

                getCurrentTimeStr() {
                    try {
                        const now = new Date();
                        const formatter = new Intl.DateTimeFormat('en-GB', {
                            timeZone: 'Asia/Jakarta',
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false
                        });
                        return formatter.format(now);
                    } catch (e) {
                        return this.serverCurrentTime || '00:00';
                    }
                },

                getSlotEndTime(slotValue) {
                    const parts = slotValue.split(':');
                    const endHour = parseInt(parts[0], 10) + 1;
                    return String(endHour).padStart(2, '0') + ':' + parts[1];
                },

                isSlotPast(slotValue) {
                    if (!this.selectedDate) return false;

                    // Past date (yesterday or earlier)
                    if (this.selectedDate < this.todayDate) {
                        return true;
                    }

                    // Future date (tomorrow onwards)
                    if (this.selectedDate > this.todayDate) {
                        return false;
                    }

                    // Today: only past when current time has reached or passed the end of the 1-hour slot
                    const currentTime = this.getCurrentTimeStr();
                    const endTime = this.getSlotEndTime(slotValue);
                    return currentTime >= endTime;
                },

                getDiscountAmount() {
                    if (!this.hasMemberDiscount || !this.selectedService) return 0;
                    const price = this.selectedService.price;
                    if (this.memberDiscountType === 'percent') {
                        return Math.min(price, Math.round((price * this.memberDiscount) / 100));
                    }
                    return Math.min(price, this.memberDiscount);
                },

                getFinalPrice() {
                    if (!this.selectedService) return 0;
                    return Math.max(0, this.selectedService.price - this.getDiscountAmount());
                },

                selectService(id, name, price, duration, formattedPrice) {
                    this.selectedService = { id, name, price, duration, formattedPrice };

                    // Jika staf yang sedang dipilih saat ini tidak bisa melayani layanan baru ini, kembalikan ke 'Siapa Saja'
                    if (this.selectedStaffId !== null) {
                        const curStaff = this.staffList.find(s => s.id === this.selectedStaffId);
                        if (curStaff && !this.isStaffAvailable(curStaff)) {
                            this.selectStaff(null, 'Siapa Saja yang Tersedia');
                        }
                    }
                },

                setDate(date, label, openTime, closeTime) {
                    this.selectedDate = date;
                    this.selectedDateLabel = label;
                    if (openTime) this.currentOpenTime = openTime;
                    if (closeTime) this.currentCloseTime = closeTime;

                    if (this.selectedTime && !this.isSlotAvailable(this.selectedTime)) {
                        this.selectedTime = '';
                        this.selectedTimeLabel = '';
                    }

                    // If currently selected staff is not working on the newly selected day, reset to 'Siapa Saja'
                    if (this.selectedStaffId !== null) {
                        const curStaff = this.staffList.find(s => s.id === this.selectedStaffId);
                        if (curStaff && !this.isStaffAvailable(curStaff)) {
                            this.selectStaff(null, 'Siapa Saja yang Tersedia');
                        }
                    }
                },

                getSlotInfo(slotValue) {
                    if (this.slotsAvailability && this.slotsAvailability[this.selectedDate]) {
                        return this.slotsAvailability[this.selectedDate][slotValue] || null;
                    }
                    return null;
                },

                isSlotFull(slotValue) {
                    const info = this.getSlotInfo(slotValue);
                    return Boolean(info && info.is_full);
                },

                getRemainingSlots(slotValue) {
                    const info = this.getSlotInfo(slotValue);
                    return info ? info.remaining : null;
                },

                isSlotAvailable(slotValue) {
                    // Waktu sesi jam sudah terlewat
                    if (this.isSlotPast(slotValue)) {
                        return false;
                    }

                    // Di luar jam operasional
                    if (slotValue < this.currentOpenTime || slotValue >= this.currentCloseTime) {
                        return false;
                    }

                    // Slot waktu per jam sudah penuh
                    if (this.isSlotFull(slotValue)) {
                        return false;
                    }

                    // Pengguna sudah memiliki pesanan lain pada jam yang sama
                    const info = this.getSlotInfo(slotValue);
                    if (info && info.user_already_booked) {
                        return false;
                    }

                    return true;
                },

                setTime(time, label) {
                    if (!this.isSlotAvailable(time)) {
                        return;
                    }
                    this.selectedTime = time;
                    this.selectedTimeLabel = label;
                },

                openConfirmModal() {
                    if (!this.selectedDate) {
                        alert('Silakan pilih hari dan tanggal reservasi.');
                        return;
                    }
                    if (!this.selectedService) {
                        alert('Silakan pilih 1 layanan terlebih dahulu.');
                        return;
                    }
                    if (!this.selectedTime || !this.isSlotAvailable(this.selectedTime)) {
                        alert('Silakan pilih sesi jam reservasi yang masih tersedia.');
                        return;
                    }
                    this.showConfirmModal = true;
                },

                submitBooking() {
                    if (!this.selectedTime || !this.isSlotAvailable(this.selectedTime)) {
                        alert('Sesi jam reservasi yang dipilih tidak tersedia atau sudah penuh.');
                        return;
                    }
                    this.$refs.bookingForm.submit();
                },

                formatRupiah(amount) {
                    return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }
            }
        }
    </script>
    @endpush
</x-pelanggan-layout>
