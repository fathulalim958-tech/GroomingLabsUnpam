<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OrderController extends Controller
{
    /**
     * Display active orders for the customer.
     */
    public function index(Request $request)
    {
        $activeBookings = Booking::with(['items.service', 'staff'])
            ->where('user_id', Auth::id())
            ->whereDate('booking_date', '>=', Carbon::today())
            ->whereIn('status', ['waiting', 'called', 'in_progress', 'skipped'])
            ->orderBy('booking_date')
            ->orderBy('queue_number')
            ->get();

        $barbershopBookings = $activeBookings->where('service_category', 'barbershop');
        $muaBookings = $activeBookings->where('service_category', 'mua');

        // Determine initial active tab
        $requestedCategory = $request->get('category');
        if (in_array($requestedCategory, ['barbershop', 'mua'])) {
            $activeTab = $requestedCategory;
        } elseif ($barbershopBookings->isEmpty() && $muaBookings->isNotEmpty()) {
            $activeTab = 'mua';
        } else {
            $activeTab = 'barbershop';
        }

        // Get current serving queue numbers for each category (ambil nomor antrian tertinggi/terupdate yang sedang dilayani)
        $currentServing = [
            'barbershop' => Booking::today()
                ->where('service_category', 'barbershop')
                ->where('status', 'in_progress')
                ->orderBy('queue_number', 'desc')
                ->orderBy('updated_at', 'desc')
                ->first(),
            'mua' => Booking::today()
                ->where('service_category', 'mua')
                ->where('status', 'in_progress')
                ->orderBy('queue_number', 'desc')
                ->orderBy('updated_at', 'desc')
                ->first(),
        ];

        return view('pelanggan.order.index', compact(
            'activeBookings',
            'barbershopBookings',
            'muaBookings',
            'activeTab',
            'currentServing'
        ));
    }

    /**
     * Check queue status via AJAX (polling).
     */
    public function checkQueue(Request $request)
    {
        $activeBookings = Booking::with(['items.service'])
            ->where('user_id', Auth::id())
            ->whereDate('booking_date', '>=', Carbon::today())
            ->whereIn('status', ['waiting', 'called', 'in_progress', 'skipped'])
            ->orderBy('booking_date')
            ->orderBy('queue_number')
            ->get();

        $currentServing = [
            'barbershop' => Booking::today()
                ->where('service_category', 'barbershop')
                ->where('status', 'in_progress')
                ->orderBy('queue_number', 'desc')
                ->orderBy('updated_at', 'desc')
                ->value('queue_number'),
            'mua' => Booking::today()
                ->where('service_category', 'mua')
                ->where('status', 'in_progress')
                ->orderBy('queue_number', 'desc')
                ->orderBy('updated_at', 'desc')
                ->value('queue_number'),
        ];

        return response()->json([
            'bookings' => $activeBookings->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'queue_number' => $booking->queue_number,
                    'status' => $booking->status,
                    'status_label' => $booking->status_label,
                    'status_color' => $booking->status_color,
                    'service_category' => $booking->service_category,
                    'booking_date' => $booking->booking_date->format('Y-m-d'),
                    'is_today' => $booking->booking_date->isToday(),
                    'services' => $booking->items->map(fn($item) => $item->service->name)->join(', '),
                ];
            }),
            'current_serving' => $currentServing,
        ]);
    }

    /**
     * Cancel a booking.
     */
    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($booking->status, ['waiting', 'called', 'skipped'])) {
            return back()->withErrors(['error' => 'Booking tidak dapat dibatalkan.']);
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('pelanggan.order.index')
            ->with('success', 'Booking berhasil dibatalkan.');
    }
}

