<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the cashier dashboard with today's orders.
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'items.service', 'staff'])
            ->today()
            ->orderByRaw("CASE status
                WHEN 'in_progress' THEN 1
                WHEN 'waiting' THEN 2
                WHEN 'skipped' THEN 3
                WHEN 'completed' THEN 4
                WHEN 'cancelled' THEN 5
                ELSE 6 END")
            ->orderBy('queue_number');

        // Filter by category
        if ($request->filled('category') && in_array($request->category, ['barbershop', 'mua'])) {
            $query->where('service_category', $request->category);
        }

        // Filter by status
        if ($request->filled('status') && in_array($request->status, ['waiting', 'in_progress', 'skipped', 'completed', 'cancelled'])) {
            $query->where('status', $request->status);
        }

        $bookings = $query->get();

        // Stats
        $stats = [
            'total' => Booking::today()->count(),
            'waiting' => Booking::today()->where('status', 'waiting')->count(),
            'in_progress' => Booking::today()->where('status', 'in_progress')->count(),
            'completed' => Booking::today()->where('status', 'completed')->count(),
            'skipped' => Booking::today()->where('status', 'skipped')->count(),
        ];

        // Current serving
        $currentServing = [
            'barbershop' => Booking::today()->where('service_category', 'barbershop')->where('status', 'in_progress')->first(),
            'mua' => Booking::today()->where('service_category', 'mua')->where('status', 'in_progress')->first(),
        ];

        return view('kasir.dashboard', compact('bookings', 'stats', 'currentServing'));
    }

    /**
     * Get orders via AJAX for real-time updates.
     */
    public function getOrders(Request $request)
    {
        $query = Booking::with(['user', 'items.service'])
            ->today()
            ->orderByRaw("CASE status
                WHEN 'in_progress' THEN 1
                WHEN 'waiting' THEN 2
                WHEN 'skipped' THEN 3
                WHEN 'completed' THEN 4
                WHEN 'cancelled' THEN 5
                ELSE 6 END")
            ->orderBy('queue_number');

        if ($request->filled('category')) {
            $query->where('service_category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->get();

        return response()->json([
            'bookings' => $bookings->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'queue_number' => $booking->queue_number,
                    'customer_name' => $booking->user->name,
                    'service_category' => $booking->service_category,
                    'services' => $booking->items->map(fn($item) => $item->service->name)->join(', '),
                    'booking_time' => $booking->booking_time,
                    'total_price' => number_format($booking->total_price, 0, ',', '.'),
                    'status' => $booking->status,
                    'status_label' => $booking->status_label,
                    'status_color' => $booking->status_color,
                    'notes' => $booking->notes,
                ];
            }),
            'stats' => [
                'total' => Booking::today()->count(),
                'waiting' => Booking::today()->where('status', 'waiting')->count(),
                'in_progress' => Booking::today()->where('status', 'in_progress')->count(),
                'completed' => Booking::today()->where('status', 'completed')->count(),
            ],
        ]);
    }

    /**
     * Display walk-in booking form.
     */
    public function walkInForm()
    {
        $barbershopServices = Service::active()->barbershop()->get();
        $muaServices = Service::active()->mua()->get();

        return view('kasir.walkin', compact('barbershopServices', 'muaServices'));
    }

    /**
     * Store a walk-in booking.
     */
    public function walkInStore(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'category' => 'required|in:barbershop,mua',
            'services' => 'required|array|min:1',
            'services.*' => 'exists:services,id',
            'notes' => 'nullable|string|max:500',
        ]);

        // Find or create walk-in customer
        $customer = User::firstOrCreate(
            ['phone' => $validated['customer_phone']],
            [
                'name' => $validated['customer_name'],
                'email' => 'walkin_' . time() . '_' . rand(100, 999) . '@walkin.local',
                'password' => bcrypt('walkin' . rand(10000, 99999)),
                'role' => 'pelanggan',
            ]
        );

        // Calculate total price
        $selectedServices = Service::whereIn('id', $validated['services'])->get();
        $totalPrice = $selectedServices->sum('price');

        $bookingCode = Booking::generateBookingCode();
        $queueNumber = Booking::generateQueueNumber($validated['category'], now()->format('Y-m-d'));

        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'user_id' => $customer->id,
            'cashier_id' => auth()->id(),
            'service_category' => $validated['category'],
            'booking_date' => now()->format('Y-m-d'),
            'booking_time' => now()->format('H:i'),
            'queue_number' => $queueNumber,
            'notes' => $validated['notes'] ?? 'Walk-in customer',
            'total_price' => $totalPrice,
            'status' => 'waiting',
        ]);

        foreach ($selectedServices as $service) {
            BookingItem::create([
                'booking_id' => $booking->id,
                'service_id' => $service->id,
                'price' => $service->price,
            ]);
        }

        return redirect()->route('kasir.dashboard')
            ->with('success', 'Walk-in booking berhasil! Nomor antrian: ' . $queueNumber);
    }
}

