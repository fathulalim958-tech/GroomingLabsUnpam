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
    public function index()
    {
        $operatingHours = OperatingHour::orderBy('day_of_week')->get();

        return view('admin.operating-hours.index', compact('operatingHours'));
    }

    /**
     * Update operating hours settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
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

        return redirect()->route('admin.operating-hours.index')
            ->with('success', 'Jadwal & jam operasional berhasil diperbarui.');
    }
}
