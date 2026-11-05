<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use App\Models\OperatingHour;
use App\Services\HolidayService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OperatingHourController extends Controller
{
    /**
     * Display operating hours settings & integrated calendar with Indonesian holidays.
     */
    public function index(Request $request)
    {
        $category = $request->get('category', 'barbershop');
        if (!in_array($category, ['barbershop', 'mua'])) {
            $category = 'barbershop';
        }

        $operatingHours = OperatingHour::where('category', $category)
            ->orderBy('day_of_week')
            ->get();

        if ($operatingHours->isEmpty()) {
            $this->seedCategoryHours($category);
            $operatingHours = OperatingHour::where('category', $category)
                ->orderBy('day_of_week')
                ->get();
        }

        // Calendar Month & Year
        $selectedYear = (int) $request->get('year', Carbon::now('Asia/Jakarta')->year);
        $selectedMonth = (int) $request->get('month', Carbon::now('Asia/Jakarta')->month);

        if ($selectedMonth < 1) {
            $selectedMonth = 12;
            $selectedYear--;
        } elseif ($selectedMonth > 12) {
            $selectedMonth = 1;
            $selectedYear++;
        }

        $calendarDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1, 'Asia/Jakarta');
        $holidaysThisMonth = HolidayService::getHolidaysForMonth($selectedYear, $selectedMonth);
        $upcomingHolidays = HolidayService::getHolidaysForYear($selectedYear);

        // Build month days grid
        $daysInMonth = $calendarDate->daysInMonth;
        $startDayOfWeek = $calendarDate->copy()->startOfMonth()->dayOfWeek; // 0=Sun, 1=Mon...
        // Convert to Monday-first for Indonesian calendar grid (1=Mon ... 7=Sun)
        $startDayOffset = ($startDayOfWeek === 0) ? 6 : ($startDayOfWeek - 1);

        $days = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = Carbon::createFromDate($selectedYear, $selectedMonth, $day, 'Asia/Jakarta');
            $dateStr = $currentDate->format('Y-m-d');
            $dayOfWeek = $currentDate->dayOfWeek; // 0=Sun, 1=Mon...
            $holiday = HolidayService::getHoliday($dateStr);
            $isSunday = ($dayOfWeek === 0);
            $isRedDate = $isSunday || ($holiday !== null);

            $days[] = [
                'day' => $day,
                'date' => $dateStr,
                'day_of_week' => $dayOfWeek,
                'day_name' => $currentDate->translatedFormat('l'),
                'is_sunday' => $isSunday,
                'is_holiday' => ($holiday !== null),
                'holiday_name' => $holiday ? $holiday['name'] : null,
                'is_red_date' => $isRedDate,
                'is_today' => ($dateStr === Carbon::now('Asia/Jakarta')->format('Y-m-d')),
            ];
        }

        return view('admin.operating-hours.index', compact(
            'operatingHours',
            'category',
            'selectedYear',
            'selectedMonth',
            'calendarDate',
            'holidaysThisMonth',
            'upcomingHolidays',
            'days',
            'startDayOffset'
        ));
    }

    /**
     * Update operating hours settings.
     */
    public function update(Request $request)
    {
        $category = $request->input('category', 'barbershop');
        if (!in_array($category, ['barbershop', 'mua'])) {
            $category = 'barbershop';
        }

        $validated = $request->validate([
            'category'                      => 'nullable|string|in:barbershop,mua',
            'hours'                         => 'required|array',
            'hours.*.id'                    => 'required|exists:operating_hours,id',
            'hours.*.open_time'             => 'required',
            'hours.*.close_time'            => 'required',
            'hours.*.is_closed'             => 'nullable',
            'hours.*.max_bookings_per_slot' => 'required|integer|min:1|max:50',
        ]);

        foreach ($validated['hours'] as $hourData) {
            $hour = OperatingHour::findOrFail($hourData['id']);
            $hour->update([
                'open_time'             => $hourData['open_time'],
                'close_time'            => $hourData['close_time'],
                'is_closed'             => isset($hourData['is_closed']),
                'max_bookings_per_slot' => $hourData['max_bookings_per_slot'],
            ]);
        }

        $categoryName = $category === 'mua' ? 'MUA Studio' : 'Barbershop';

        return redirect()->route('admin.operating-hours.index', ['category' => $category])
            ->with('success', "Jadwal & jam operasional {$categoryName} berhasil diperbarui.");
    }

    /**
     * Store a custom holiday date.
     */
    public function storeHoliday(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        Holiday::updateOrCreate(
            ['date' => $validated['date']],
            [
                'name' => $validated['name'],
                'is_national' => false,
                'description' => $validated['description'] ?? 'Libur Khusus / Tambahan Toko',
            ]
        );

        $dateCarbon = Carbon::parse($validated['date']);

        return redirect()->route('admin.operating-hours.index', [
            'month' => $dateCarbon->month,
            'year' => $dateCarbon->year,
        ])->with('success', "Tanggal merah / hari libur '{$validated['name']}' berhasil ditambahkan ke kalender.");
    }

    /**
     * Remove a custom holiday.
     */
    public function destroyHoliday(Holiday $holiday)
    {
        $dateCarbon = Carbon::parse($holiday->date);
        $name = $holiday->name;
        $holiday->delete();

        return redirect()->route('admin.operating-hours.index', [
            'month' => $dateCarbon->month,
            'year' => $dateCarbon->year,
        ])->with('success', "Hari libur '{$name}' berhasil dihapus dari kalender.");
    }

    /**
     * Helper to seed hours for a category if missing.
     */
    private function seedCategoryHours(string $category): void
    {
        $defaultQuota = $category === 'barbershop' ? 5 : 2;

        for ($day = 1; $day <= 6; $day++) {
            OperatingHour::create([
                'category'              => $category,
                'day_of_week'           => $day,
                'open_time'             => '09:00',
                'close_time'            => '21:00',
                'is_closed'             => false,
                'max_bookings_per_slot' => $defaultQuota,
            ]);
        }

        OperatingHour::create([
            'category'              => $category,
            'day_of_week'           => 0,
            'open_time'             => '09:00',
            'close_time'            => '18:00',
            'is_closed'             => true,
            'max_bookings_per_slot' => $defaultQuota,
        ]);
    }
}
