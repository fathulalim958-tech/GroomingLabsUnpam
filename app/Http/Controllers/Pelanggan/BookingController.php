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
        $availableDates = $this->getAvailableDates($category);

        return view('pelanggan.booking.create', compact('category', 'services', 'availableDates'));
    }

    /**
     * Store a new booking.
     */
    public function store(Request $request)
    {
        if ($request->has('service_id') && ! $request->has('services')) {
            $request->merge(['services' => [$request->service_id]]);
        }

        $validated = $request->validate([
            'category' => 'required|in:barbershop,mua',
            'services' => 'required|array|min:1|max:1',
            'services.*' => 'exists:services,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
            'notes' => 'nullable|string|max:500',
        ], [
            'services.required' => 'Silakan pilih 1 layanan.',
            'services.max' => 'Hanya dapat memilih 1 layanan per pesanan.',
        ]);

        // Check if the selected date is not a closed day for this category
        $dayOfWeek = Carbon::parse($validated['booking_date'])->dayOfWeek;
        $operatingHour = OperatingHour::where('category', $validated['category'])
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if ($operatingHour && $operatingHour->is_closed) {
            $catLabel = $validated['category'] === 'mua' ? 'layanan MUA' : 'layanan Barbershop';
            return back()->withErrors(['booking_date' => "Maaf, pada hari tersebut {$catLabel} tutup."])->withInput();
        }

        // Check booking time is within operating hours
        if ($operatingHour) {
            $bookingTime = substr($validated['booking_time'], 0, 5);
            $openTime = substr($operatingHour->open_time, 0, 5);
            $closeTime = substr($operatingHour->close_time, 0, 5);

            if ($bookingTime < $openTime || $bookingTime >= $closeTime) {
                return back()->withErrors([
                    'booking_time' => "Waktu booking di luar jam operasional ({$openTime} - {$closeTime} WIB)."
                ])->withInput();
            }
        }

        // Check slot availability for this service category
        $timePrefix = substr($validated['booking_time'], 0, 5);
        $existingBookings = Booking::whereDate('booking_date', $validated['booking_date'])
            ->where(function ($q) use ($timePrefix) {
                $q->where('booking_time', 'like', "{$timePrefix}%");
            })
            ->where('service_category', $validated['category'])
            ->whereNotIn('status', ['cancelled'])
            ->count();

        $maxPerSlot = $operatingHour ? $operatingHour->max_bookings_per_slot : ($validated['category'] === 'barbershop' ? 5 : 2);
        if ($existingBookings >= $maxPerSlot) {
            return back()->withErrors(['booking_time' => 'Slot waktu ini sudah penuh untuk layanan tersebut. Silakan pilih waktu lain.'])->withInput();
        }

        // Check if customer already has an active booking for this date in this category (max 1 barbershop and max 1 mua per day)
        $existingActive = Booking::where('user_id', auth()->id())
            ->where('service_category', $validated['category'])
            ->whereDate('booking_date', $validated['booking_date'])
            ->whereIn('status', ['waiting', 'in_progress', 'skipped'])
            ->exists();

        if ($existingActive) {
            $catLabel = $validated['category'] === 'barbershop' ? 'Barbershop' : 'MUA Studio';
            return back()->withErrors(['booking_date' => "Anda sudah memiliki pesanan aktif untuk {$catLabel} pada tanggal ini (maksimal 1 pesanan Barbershop dan 1 pesanan MUA per hari)."])->withInput();
        }

        // Prevent booking at the exact same hour if customer already booked the other category at this time
        $sameTimeBooking = Booking::where('user_id', auth()->id())
            ->whereDate('booking_date', $validated['booking_date'])
            ->where('booking_time', 'like', substr($validated['booking_time'], 0, 5) . '%')
            ->whereIn('status', ['waiting', 'in_progress', 'skipped'])
            ->exists();

        if ($sameTimeBooking) {
            return back()->withErrors(['booking_time' => 'Anda sudah memiliki reservasi pada jam ini untuk layanan lain. Silakan pilih sesi jam yang berbeda.'])->withInput();
        }

        // Calculate total price & apply member discount if available
        $selectedServices = Service::whereIn('id', $validated['services'])->get();
        $originalPrice = (float) $selectedServices->sum('price');
        $discountAmount = 0;

        $user = auth()->user();
        if ($user && $user->hasActiveMemberDiscount()) {
            $discountAmount = $user->calculateDiscount($originalPrice);
            $user->decrement('member_discount_remaining');
        }

        $totalPrice = max(0, $originalPrice - $discountAmount);

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
            'notes' => $validated['notes'] ?? null,
            'total_price' => $totalPrice,
            'discount_amount' => $discountAmount,
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
     * Get available dates for the next 7 days for a specific category.
     */
    private function getAvailableDates(string $category = 'barbershop'): array
    {
        $dates = [];
        $operatingHours = OperatingHour::where('category', $category)->get()->keyBy('day_of_week');

        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->addDays($i);
            $dayOfWeek = $date->dayOfWeek;
            $oh = $operatingHours->get($dayOfWeek);

            if (!$oh || !$oh->is_closed) {
                $dates[] = [
                    'date' => $date->format('Y-m-d'),
                    'label' => $date->translatedFormat('l, d M Y'),
                    'open_time' => $oh ? substr($oh->open_time, 0, 5) : '09:00',
                    'close_time' => $oh ? substr($oh->close_time, 0, 5) : '21:00',
                ];
            }
        }

        return $dates;
    }
}

