<?php

namespace App\Services;

use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class HolidayService
{
    /**
     * Default list of Indonesian Public Holidays and Joint Leaves (SKB 3 Menteri).
     * Format: 'YYYY-MM-DD' => ['name' => '...', 'is_national' => true]
     */
    public const DEFAULT_HOLIDAYS = [
        // 2025 Holidays
        '2025-01-01' => ['name' => 'Tahun Baru 2025 Masehi', 'is_national' => true],
        '2025-01-27' => ['name' => 'Isra Mikraj Nabi Muhammad SAW', 'is_national' => true],
        '2025-01-28' => ['name' => 'Cuti Bersama Tahun Baru Imlek 2576 Kongzili', 'is_national' => true],
        '2025-01-29' => ['name' => 'Tahun Baru Imlek 2576 Kongzili', 'is_national' => true],
        '2025-03-28' => ['name' => 'Cuti Bersama Hari Suci Nyepi', 'is_national' => true],
        '2025-03-29' => ['name' => 'Hari Suci Nyepi (Tahun Baru Saka 1947)', 'is_national' => true],
        '2025-03-31' => ['name' => 'Hari Raya Idulfitri 1446 H (Hari 1)', 'is_national' => true],
        '2025-04-01' => ['name' => 'Hari Raya Idulfitri 1446 H (Hari 2)', 'is_national' => true],
        '2025-04-02' => ['name' => 'Cuti Bersama Hari Raya Idulfitri 1446 H', 'is_national' => true],
        '2025-04-03' => ['name' => 'Cuti Bersama Hari Raya Idulfitri 1446 H', 'is_national' => true],
        '2025-04-04' => ['name' => 'Cuti Bersama Hari Raya Idulfitri 1446 H', 'is_national' => true],
        '2025-04-07' => ['name' => 'Cuti Bersama Hari Raya Idulfitri 1446 H', 'is_national' => true],
        '2025-04-18' => ['name' => 'Wafat Yesus Kristus', 'is_national' => true],
        '2025-04-20' => ['name' => 'Kebangkitan Yesus Kristus (Paskah)', 'is_national' => true],
        '2025-05-01' => ['name' => 'Hari Buruh Internasional', 'is_national' => true],
        '2025-05-12' => ['name' => 'Hari Raya Waisak 2569 BE', 'is_national' => true],
        '2025-05-13' => ['name' => 'Cuti Bersama Hari Raya Waisak 2569 BE', 'is_national' => true],
        '2025-05-29' => ['name' => 'Kenaikan Yesus Kristus', 'is_national' => true],
        '2025-05-30' => ['name' => 'Cuti Bersama Kenaikan Yesus Kristus', 'is_national' => true],
        '2025-06-01' => ['name' => 'Hari Lahir Pancasila', 'is_national' => true],
        '2025-06-06' => ['name' => 'Hari Raya Iduladha 1446 Hijriah', 'is_national' => true],
        '2025-06-09' => ['name' => 'Cuti Bersama Hari Raya Iduladha 1446 Hijriah', 'is_national' => true],
        '2025-06-27' => ['name' => 'Tahun Baru Islam 1447 Hijriah', 'is_national' => true],
        '2025-08-17' => ['name' => 'Proklamasi Kemerdekaan RI Ke-80', 'is_national' => true],
        '2025-09-05' => ['name' => 'Maulid Nabi Muhammad SAW', 'is_national' => true],
        '2025-12-25' => ['name' => 'Kelahiran Yesus Kristus (Hari Raya Natal)', 'is_national' => true],
        '2025-12-26' => ['name' => 'Cuti Bersama Hari Raya Natal', 'is_national' => true],

        // 2026 Holidays (Resmi SKB 3 Menteri)
        '2026-01-01' => ['name' => 'Tahun Baru 2026 Masehi', 'is_national' => true],
        '2026-01-16' => ['name' => 'Isra Mikraj Nabi Muhammad SAW', 'is_national' => true],
        '2026-02-16' => ['name' => 'Cuti Bersama Tahun Baru Imlek 2577 Kongzili', 'is_national' => true],
        '2026-02-17' => ['name' => 'Tahun Baru Imlek 2577 Kongzili', 'is_national' => true],
        '2026-03-18' => ['name' => 'Cuti Bersama Hari Suci Nyepi', 'is_national' => true],
        '2026-03-19' => ['name' => 'Hari Suci Nyepi (Tahun Baru Saka 1948)', 'is_national' => true],
        '2026-03-20' => ['name' => 'Cuti Bersama Hari Raya Idulfitri 1447 H', 'is_national' => true],
        '2026-03-21' => ['name' => 'Hari Raya Idulfitri 1447 Hijriah (Hari 1)', 'is_national' => true],
        '2026-03-22' => ['name' => 'Hari Raya Idulfitri 1447 Hijriah (Hari 2)', 'is_national' => true],
        '2026-03-23' => ['name' => 'Cuti Bersama Hari Raya Idulfitri 1447 H', 'is_national' => true],
        '2026-03-24' => ['name' => 'Cuti Bersama Hari Raya Idulfitri 1447 H', 'is_national' => true],
        '2026-04-03' => ['name' => 'Wafat Yesus Kristus', 'is_national' => true],
        '2026-04-05' => ['name' => 'Kebangkitan Yesus Kristus (Paskah)', 'is_national' => true],
        '2026-05-01' => ['name' => 'Hari Buruh Internasional', 'is_national' => true],
        '2026-05-14' => ['name' => 'Kenaikan Yesus Kristus', 'is_national' => true],
        '2026-05-15' => ['name' => 'Cuti Bersama Kenaikan Yesus Kristus', 'is_national' => true],
        '2026-05-27' => ['name' => 'Hari Raya Iduladha 1447 Hijriah', 'is_national' => true],
        '2026-05-28' => ['name' => 'Cuti Bersama Hari Raya Iduladha 1447 Hijriah', 'is_national' => true],
        '2026-05-31' => ['name' => 'Hari Raya Waisak 2570 BE', 'is_national' => true],
        '2026-06-01' => ['name' => 'Hari Lahir Pancasila', 'is_national' => true],
        '2026-06-16' => ['name' => 'Tahun Baru Islam 1448 Hijriah', 'is_national' => true],
        '2026-08-17' => ['name' => 'Proklamasi Kemerdekaan RI Ke-81', 'is_national' => true],
        '2026-08-25' => ['name' => 'Maulid Nabi Muhammad SAW', 'is_national' => true],
        '2026-12-24' => ['name' => 'Cuti Bersama Hari Raya Natal', 'is_national' => true],
        '2026-12-25' => ['name' => 'Kelahiran Yesus Kristus (Hari Raya Natal)', 'is_national' => true],

        // 2027 Core Known Holidays
        '2027-01-01' => ['name' => 'Tahun Baru 2027 Masehi', 'is_national' => true],
        '2027-01-05' => ['name' => 'Isra Mikraj Nabi Muhammad SAW', 'is_national' => true],
        '2027-02-06' => ['name' => 'Tahun Baru Imlek 2578 Kongzili', 'is_national' => true],
        '2027-03-09' => ['name' => 'Hari Suci Nyepi (Tahun Baru Saka 1949)', 'is_national' => true],
        '2027-03-10' => ['name' => 'Hari Raya Idulfitri 1448 Hijriah (Hari 1)', 'is_national' => true],
        '2027-03-11' => ['name' => 'Hari Raya Idulfitri 1448 Hijriah (Hari 2)', 'is_national' => true],
        '2027-03-26' => ['name' => 'Wafat Yesus Kristus', 'is_national' => true],
        '2027-05-01' => ['name' => 'Hari Buruh Internasional', 'is_national' => true],
        '2027-05-06' => ['name' => 'Kenaikan Yesus Kristus', 'is_national' => true],
        '2027-05-17' => ['name' => 'Hari Raya Iduladha 1448 Hijriah', 'is_national' => true],
        '2027-05-20' => ['name' => 'Hari Raya Waisak 2571 BE', 'is_national' => true],
        '2027-06-01' => ['name' => 'Hari Lahir Pancasila', 'is_national' => true],
        '2027-06-06' => ['name' => 'Tahun Baru Islam 1449 Hijriah', 'is_national' => true],
        '2027-08-15' => ['name' => 'Maulid Nabi Muhammad SAW', 'is_national' => true],
        '2027-08-17' => ['name' => 'Proklamasi Kemerdekaan RI Ke-82', 'is_national' => true],
        '2027-12-25' => ['name' => 'Kelahiran Yesus Kristus (Hari Raya Natal)', 'is_national' => true],
    ];

    /**
     * Check if a given date is an Indonesian public holiday or scheduled holiday.
     */
    public static function isHoliday(string|Carbon $date): bool
    {
        return self::getHoliday($date) !== null;
    }

    /**
     * Get details of a holiday for a given date.
     */
    public static function getHoliday(string|Carbon $date): ?array
    {
        $dateStr = $date instanceof Carbon ? $date->format('Y-m-d') : Carbon::parse($date)->format('Y-m-d');

        // 1. Check database first (includes custom admin holidays)
        try {
            $dbHoliday = Holiday::whereDate('date', $dateStr)->first();
            if ($dbHoliday) {
                return [
                    'id' => $dbHoliday->id,
                    'date' => $dateStr,
                    'name' => $dbHoliday->name,
                    'is_national' => (bool) $dbHoliday->is_national,
                    'description' => $dbHoliday->description,
                ];
            }
        } catch (\Throwable $e) {
            // Fallback if table not ready
        }

        // 2. Check default built-in official holidays
        if (isset(self::DEFAULT_HOLIDAYS[$dateStr])) {
            $item = self::DEFAULT_HOLIDAYS[$dateStr];
            return [
                'id' => null,
                'date' => $dateStr,
                'name' => $item['name'],
                'is_national' => $item['is_national'] ?? true,
                'description' => null,
            ];
        }

        return null;
    }

    /**
     * Get all holidays for a given month and year.
     */
    public static function getHolidaysForMonth(int $year, int $month): array
    {
        $holidays = [];
        $prefix = sprintf('%04d-%02d-', $year, $month);

        // From built-in defaults
        foreach (self::DEFAULT_HOLIDAYS as $dateStr => $data) {
            if (str_starts_with($dateStr, $prefix)) {
                $holidays[$dateStr] = [
                    'id' => null,
                    'date' => $dateStr,
                    'name' => $data['name'],
                    'is_national' => $data['is_national'] ?? true,
                    'description' => null,
                ];
            }
        }

        // Overlay with database records
        try {
            $dbHolidays = Holiday::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->get();

            foreach ($dbHolidays as $h) {
                $dateStr = $h->date->format('Y-m-d');
                $holidays[$dateStr] = [
                    'id' => $h->id,
                    'date' => $dateStr,
                    'name' => $h->name,
                    'is_national' => (bool) $h->is_national,
                    'description' => $h->description,
                ];
            }
        } catch (\Throwable $e) {
            // Ignore if DB error
        }

        ksort($holidays);
        return $holidays;
    }

    /**
     * Get all holidays for a given year.
     */
    public static function getHolidaysForYear(int $year): array
    {
        $holidays = [];
        $prefix = sprintf('%04d-', $year);

        foreach (self::DEFAULT_HOLIDAYS as $dateStr => $data) {
            if (str_starts_with($dateStr, $prefix)) {
                $holidays[$dateStr] = [
                    'id' => null,
                    'date' => $dateStr,
                    'name' => $data['name'],
                    'is_national' => $data['is_national'] ?? true,
                    'description' => null,
                ];
            }
        }

        try {
            $dbHolidays = Holiday::whereYear('date', $year)->get();
            foreach ($dbHolidays as $h) {
                $dateStr = $h->date->format('Y-m-d');
                $holidays[$dateStr] = [
                    'id' => $h->id,
                    'date' => $dateStr,
                    'name' => $h->name,
                    'is_national' => (bool) $h->is_national,
                    'description' => $h->description,
                ];
            }
        } catch (\Throwable $e) {
            // Ignore
        }

        ksort($holidays);
        return $holidays;
    }

    /**
     * Seed or sync default holidays into the database if not present.
     */
    public static function seedDefaultsToDatabase(): void
    {
        try {
            foreach (self::DEFAULT_HOLIDAYS as $dateStr => $data) {
                Holiday::firstOrCreate(
                    ['date' => $dateStr],
                    [
                        'name' => $data['name'],
                        'is_national' => $data['is_national'] ?? true,
                        'description' => 'Hari Libur Nasional / Cuti Bersama Resmi',
                    ]
                );
            }
        } catch (\Throwable $e) {
            // Ignore
        }
    }
}
