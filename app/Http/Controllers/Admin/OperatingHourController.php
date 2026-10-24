<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OperatingHour;
use Illuminate\Http\Request;

class OperatingHourController extends Controller
{
    /**
     * Display operating hours settings.
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

        // If for any reason operating hours for this category don't exist yet, generate them
        if ($operatingHours->isEmpty()) {
            $this->seedCategoryHours($category);
            $operatingHours = OperatingHour::where('category', $category)
                ->orderBy('day_of_week')
                ->get();
        }

        return view('admin.operating-hours.index', compact('operatingHours', 'category'));
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
