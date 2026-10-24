<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the cashier dashboard with today's orders (separated Barbershop & MUA).
     */
    public function index(Request $request)
    {
        // Category separation: barbershop or mua (default: barbershop)
        $activeCategory = $request->get('category', 'barbershop');
        if (!in_array($activeCategory, ['barbershop', 'mua'])) {
            $activeCategory = 'barbershop';
        }

        // Dashboard ONLY displays orders for TODAY
        $query = Booking::with(['user', 'items.service', 'staff', 'cashier'])
            ->whereDate('booking_date', Carbon::today())
            ->where('service_category', $activeCategory);

        // Optional status filter
        if ($request->filled('status') && array_key_exists($request->status, Booking::STATUS_LABELS)) {
            $query->where('status', $request->status);
        }

        // Priority ordering: in_progress first, then called, then waiting, skipped, completed, cancelled
        $query->orderByRaw("CASE status
                WHEN 'in_progress' THEN 1
                WHEN 'called' THEN 2
                WHEN 'waiting' THEN 3
                WHEN 'skipped' THEN 4
                WHEN 'completed' THEN 5
                WHEN 'cancelled' THEN 6
                ELSE 7 END")
            ->orderBy('booking_time', 'asc')
            ->orderBy('queue_number', 'asc');

        $bookings = $query->get();

        // Upcoming bookings count across all or for this category
        $upcomingCount = Booking::whereDate('booking_date', '>', Carbon::today())
            ->whereIn('status', ['waiting', 'called', 'in_progress'])
            ->count();

        // Stats for TODAY in active category
        $todayCategoryQuery = Booking::whereDate('booking_date', Carbon::today())
            ->where('service_category', $activeCategory);

        $stats = [
            'total'       => (clone $todayCategoryQuery)->count(),
            'waiting'     => (clone $todayCategoryQuery)->where('status', 'waiting')->count(),
            'called'      => (clone $todayCategoryQuery)->where('status', 'called')->count(),
            'in_progress' => (clone $todayCategoryQuery)->where('status', 'in_progress')->count(),
            'completed'   => (clone $todayCategoryQuery)->where('status', 'completed')->count(),
            'cancelled'   => (clone $todayCategoryQuery)->whereIn('status', ['cancelled', 'skipped'])->count(),
        ];

        // Currently serving customer (in_progress) today handled by THIS cashier (or unassigned fallback)
        $currentServing = Booking::today()
            ->where('service_category', $activeCategory)
            ->where('status', 'in_progress')
            ->where('cashier_id', auth()->id())
            ->first();

        if (!$currentServing) {
            $currentServing = Booking::today()
                ->where('service_category', $activeCategory)
                ->where('status', 'in_progress')
                ->whereNull('cashier_id')
                ->first();
        }

        return view('kasir.dashboard', compact(
            'bookings',
            'stats',
            'currentServing',
            'activeCategory',
            'upcomingCount'
        ));
    }

    /**
     * Get orders via AJAX for real-time updates.
     */
    public function getOrders(Request $request)
    {
        $activeCategory = $request->get('category', 'barbershop');
        if (!in_array($activeCategory, ['barbershop', 'mua'])) {
            $activeCategory = 'barbershop';
        }

        $query = Booking::with(['user', 'items.service'])
            ->whereDate('booking_date', Carbon::today())
            ->where('service_category', $activeCategory);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $query->orderByRaw("CASE status
                WHEN 'in_progress' THEN 1
                WHEN 'called' THEN 2
                WHEN 'waiting' THEN 3
                WHEN 'skipped' THEN 4
                WHEN 'completed' THEN 5
                WHEN 'cancelled' THEN 6
                ELSE 7 END")
            ->orderBy('booking_time', 'asc')
            ->orderBy('queue_number', 'asc');

        $bookings = $query->get();

        return response()->json([
            'bookings' => $bookings->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'queue_number' => $booking->queue_number,
                    'customer_name' => $booking->customer_name,
                    'customer_phone' => $booking->customer_phone,
                    'service_category' => $booking->service_category,
                    'services' => $booking->items->map(fn($item) => $item->service->name)->join(', '),
                    'booking_date' => $booking->booking_date->format('Y-m-d'),
                    'booking_date_formatted' => $booking->booking_date->translatedFormat('d M Y'),
                    'is_today' => true,
                    'booking_time' => $booking->booking_time,
                    'total_price' => number_format($booking->total_price, 0, ',', '.'),
                    'status' => $booking->status,
                    'status_label' => $booking->status_label,
                    'status_color' => $booking->status_color,
                    'notes' => $booking->notes,
                ];
            }),
            'stats' => [
                'total' => Booking::today()->where('service_category', $activeCategory)->count(),
                'waiting' => Booking::today()->where('service_category', $activeCategory)->where('status', 'waiting')->count(),
                'called' => Booking::today()->where('service_category', $activeCategory)->where('status', 'called')->count(),
                'in_progress' => Booking::today()->where('service_category', $activeCategory)->where('status', 'in_progress')->count(),
                'completed' => Booking::today()->where('service_category', $activeCategory)->where('status', 'completed')->count(),
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
        if ($request->has('service_id') && ! $request->has('services')) {
            $request->merge(['services' => [$request->service_id]]);
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+]+$/'],
            'category' => 'required|in:barbershop,mua',
            'services' => 'required|array|min:1|max:1',
            'services.*' => 'exists:services,id',
            'notes' => 'nullable|string|max:500',
        ], [
            'services.required' => 'Silakan pilih 1 layanan.',
            'services.max' => 'Hanya dapat memilih 1 layanan per pesanan.',
            'customer_phone.regex' => 'Nomor WhatsApp / telepon hanya boleh berisi angka nomor seluler.',
        ]);

        // Calculate total price
        $selectedServices = Service::whereIn('id', $validated['services'])->get();
        $totalPrice = $selectedServices->sum('price');

        $now = Carbon::now('Asia/Jakarta');
        $bookingCode = Booking::generateBookingCode();
        $queueNumber = Booking::generateQueueNumber($validated['category'], $now->toDateString());

        // Offline / walk-in orders do not create a user account.
        // Data is saved directly on the booking.
        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'user_id' => null,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'cashier_id' => Auth::id(),
            'service_category' => $validated['category'],
            'booking_date' => $now->toDateString(),
            'booking_time' => $now->format('H:i:s'),
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

