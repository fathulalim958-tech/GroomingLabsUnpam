<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Service;
use App\Models\OperatingHour;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BookingController extends Controller
{
    /**
     * Display category selection page.
     */
    public function selectCategory()
    {
        return view('pelanggan.booking.category');
    }

    /**
     * Display booking form for a specific category.
     */
    public function create(string $category)
    {
        if (!in_array($category, ['barbershop', 'mua'])) {
            abort(404);
        }

        $services = Service::active()
            ->where('category', $category)
            ->get();

        // Get available dates (next 7 days, excluding closed days)
        $availableDates = $this->getAvailableDates();

        return view('pelanggan.booking.create', compact('category', 'services', 'availableDates'));
    }

    /**
     * Store a new booking.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|in:barbershop,mua',
            'services' => 'required|array|min:1',
            'services.*' => 'exists:services,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if the selected date is not a closed day
        $dayOfWeek = Carbon::parse($validated['booking_date'])->dayOfWeek;
        $operatingHour = OperatingHour::where('day_of_week', $dayOfWeek)->first();

        if ($operatingHour && $operatingHour->is_closed) {
            return back()->withErrors(['booking_date' => 'Maaf, hari tersebut toko tutup.'])->withInput();
        }

        // Check booking time is within operating hours
        if ($operatingHour) {
            $bookingTime = $validated['booking_time'];
            if ($bookingTime < $operatingHour->open_time || $bookingTime >= $operatingHour->close_time) {
                return back()->withErrors(['booking_time' => 'Waktu booking di luar jam operasional.'])->withInput();
            }
        }

        // Check slot availability
        $existingBookings = Booking::where('booking_date', $validated['booking_date'])
            ->where('booking_time', $validated['booking_time'])
            ->whereNotIn('status', ['cancelled'])
            ->count();

        $maxPerSlot = $operatingHour ? $operatingHour->max_bookings_per_slot : 5;
        if ($existingBookings >= $maxPerSlot) {
            return back()->withErrors(['booking_time' => 'Slot waktu ini sudah penuh. Silakan pilih waktu lain.'])->withInput();
        }

        // Check if customer already has an active booking for this date
        $existingActive = Booking::where('user_id', auth()->id())
            ->where('booking_date', $validated['booking_date'])
            ->whereIn('status', ['waiting', 'in_progress', 'skipped'])
            ->exists();

        if ($existingActive) {
            return back()->withErrors(['booking_date' => 'Anda sudah memiliki booking aktif pada tanggal ini.'])->withInput();
        }

        // Calculate total price
        $selectedServices = Service::whereIn('id', $validated['services'])->get();
        $totalPrice = $selectedServices->sum('price');

        // Generate booking code and queue number
        $bookingCode = Booking::generateBookingCode();
        $queueNumber = Booking::generateQueueNumber($validated['category'], $validated['booking_date']);

        // Create booking
        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'user_id' => auth()->id(),
            'service_category' => $validated['category'],
            'booking_date' => $validated['booking_date'],
            'booking_time' => $validated['booking_time'],
            'queue_number' => $queueNumber,
            'notes' => $validated['notes'],
            'total_price' => $totalPrice,
            'status' => 'waiting',
        ]);

        // Create booking items
        foreach ($selectedServices as $service) {
            BookingItem::create([
                'booking_id' => $booking->id,
                'service_id' => $service->id,
                'price' => $service->price,
            ]);
        }

        return redirect()->route('pelanggan.order.index')
            ->with('success', 'Booking berhasil dibuat! Nomor antrian Anda: ' . $queueNumber);
    }

    /**
     * Get available dates for the next 7 days.
     */
    private function getAvailableDates(): array
    {
        $dates = [];
        $operatingHours = OperatingHour::all()->keyBy('day_of_week');

        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->addDays($i);
            $dayOfWeek = $date->dayOfWeek;
            $oh = $operatingHours->get($dayOfWeek);

            if (!$oh || !$oh->is_closed) {
                $dates[] = [
                    'date' => $date->format('Y-m-d'),
                    'label' => $date->translatedFormat('l, d M Y'),
                    'open_time' => $oh ? $oh->open_time : '09:00',
                    'close_time' => $oh ? $oh->close_time : '21:00',
                ];
            }
        }

        return $dates;
    }
}

