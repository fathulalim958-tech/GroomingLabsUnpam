<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    /**
     * Call a customer (Dipanggil) - Notifies customer to approach service chair/desk.
     */
    public function call(Booking $booking)
    {
        if (!in_array($booking->status, ['waiting', 'called', 'skipped'])) {
            return back()->withErrors(['error' => 'Hanya antrian yang Menunggu atau Dilewati yang dapat dipanggil.']);
        }

        $booking->update([
            'status' => 'called',
            'cashier_id' => auth()->id(),
        ]);

        return back()->with('success', 'Nomor antrian ' . $booking->queue_number . ' (' . $booking->customer_name . ') berhasil dipanggil! Pemberitahuan telah dikirimkan ke pelanggan.');
    }

    /**
     * Serve a customer (Layani) - Set booking status to In Progress (Sedang Dilayani).
     */
    public function process(Booking $booking)
    {
        if (!in_array($booking->status, ['waiting', 'called', 'skipped'])) {
            return back()->withErrors(['error' => 'Booking tidak dapat dilayani.']);
        }

        // Cek apakah akun kasir yang sedang login ini sudah melayani antrian aktif (setiap akun kasir hanya bisa melayani 1 antrian)
        $inProgress = Booking::today()
            ->where('cashier_id', auth()->id())
            ->where('status', 'in_progress')
            ->where('id', '!=', $booking->id)
            ->first();

        if ($inProgress) {
            return back()->withErrors(['error' => 'Akun kasir Anda masih melayani antrian (' . $inProgress->queue_number . ' - ' . $inProgress->customer_name . '). Selesaikan antrian tersebut terlebih dahulu sebelum melayani antrian baru.']);
        }

        $booking->update([
            'status' => 'in_progress',
            'cashier_id' => auth()->id(),
        ]);

        return back()->with('success', 'Nomor antrian ' . $booking->queue_number . ' (' . $booking->customer_name . ') sedang dilayani.');
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

        // Cek jika antrian ini sedang diproses oleh akun kasir lain
        if ($booking->cashier_id && $booking->cashier_id !== auth()->id()) {
            $otherCashierName = $booking->cashier ? $booking->cashier->name : 'kasir lain';
            return back()->withErrors(['error' => "Antrian ini sedang diproses oleh {$otherCashierName}. Hanya kasir yang melayani yang dapat menyelesaikan antrian ini."]);
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
        if (!in_array($booking->status, ['waiting', 'called', 'skipped'])) {
            return back()->withErrors(['error' => 'Booking tidak dapat dibatalkan.']);
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Antrian ' . $booking->queue_number . ' dibatalkan.');
    }
}

