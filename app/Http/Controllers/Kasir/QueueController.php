<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    /**
     * Set booking status to In Progress (Proses).
     */
    public function process(Booking $booking)
    {
        if (!in_array($booking->status, ['waiting', 'skipped'])) {
            return back()->withErrors(['error' => 'Booking tidak dapat diproses.']);
        }

        // Check if there's already an in-progress booking for this category
        $inProgress = Booking::today()
            ->where('service_category', $booking->service_category)
            ->where('status', 'in_progress')
            ->first();

        if ($inProgress) {
            return back()->withErrors(['error' => 'Masih ada antrian yang sedang diproses (' . $inProgress->queue_number . '). Selesaikan terlebih dahulu.']);
        }

        $booking->update([
            'status' => 'in_progress',
            'cashier_id' => auth()->id(),
        ]);

        return back()->with('success', 'Antrian ' . $booking->queue_number . ' sedang diproses.');
    }

    /**
     * Skip a booking (customer not present).
     */
    public function skip(Booking $booking)
    {
        if ($booking->status !== 'waiting') {
            return back()->withErrors(['error' => 'Hanya booking dengan status Menunggu yang dapat dilewati.']);
        }

        $booking->update(['status' => 'skipped']);

        return back()->with('success', 'Antrian ' . $booking->queue_number . ' dilewati.');
    }

    /**
     * Complete a booking (service done, payment received).
     */
    public function complete(Booking $booking)
    {
        if ($booking->status !== 'in_progress') {
            return back()->withErrors(['error' => 'Hanya booking yang sedang diproses yang dapat diselesaikan.']);
        }

        $booking->update([
            'status' => 'completed',
            'cashier_id' => auth()->id(),
        ]);

        return back()->with('success', 'Antrian ' . $booking->queue_number . ' telah selesai.');
    }

    /**
     * Cancel a booking.
     */
    public function cancel(Booking $booking)
    {
        if (!in_array($booking->status, ['waiting', 'skipped'])) {
            return back()->withErrors(['error' => 'Booking tidak dapat dibatalkan.']);
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Antrian ' . $booking->queue_number . ' dibatalkan.');
    }
}

