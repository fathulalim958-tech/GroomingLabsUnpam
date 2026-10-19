<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display active orders for the customer.
     */
    public function index()
    {
        $activeBookings = Booking::with(['items.service', 'staff'])
            ->where('user_id', auth()->id())
            ->today()
            ->whereIn('status', ['waiting', 'in_progress', 'skipped'])
            ->orderBy('queue_number')
            ->get();

        // Get current serving queue numbers for each category
        $currentServing = [
            'barbershop' => Booking::today()
                ->where('service_category', 'barbershop')
                ->where('status', 'in_progress')
                ->first(),
            'mua' => Booking::today()
                ->where('service_category', 'mua')
                ->where('status', 'in_progress')
                ->first(),
        ];

        return view('pelanggan.order.index', compact('activeBookings', 'currentServing'));
    }

    /**
     * Check queue status via AJAX (polling).
     */
    public function checkQueue(Request $request)
    {
        $activeBookings = Booking::with(['items.service'])
            ->where('user_id', auth()->id())
            ->today()
            ->whereIn('status', ['waiting', 'in_progress', 'skipped'])
            ->orderBy('queue_number')
            ->get();

        $currentServing = [
            'barbershop' => Booking::today()
                ->where('service_category', 'barbershop')
                ->where('status', 'in_progress')
                ->value('queue_number'),
            'mua' => Booking::today()
                ->where('service_category', 'mua')
                ->where('status', 'in_progress')
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
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($booking->status, ['waiting', 'skipped'])) {
            return back()->withErrors(['error' => 'Booking tidak dapat dibatalkan.']);
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('pelanggan.order.index')
            ->with('success', 'Booking berhasil dibatalkan.');
    }
}

