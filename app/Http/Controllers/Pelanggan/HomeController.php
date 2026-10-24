<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display the customer home page with service catalog.
     */
    public function index()
    {
        $barbershopServices = Service::active()->barbershop()->take(6)->get();
        $muaServices = Service::active()->mua()->take(6)->get();

        // Get active bookings for this customer (today or upcoming)
        $activeBookings = Booking::where('user_id', Auth::id())
            ->whereDate('booking_date', '>=', Carbon::today())
            ->whereIn('status', ['waiting', 'called', 'in_progress', 'skipped'])
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->get();

        $activeBooking = $activeBookings->first();

        return view('pelanggan.home', compact('barbershopServices', 'muaServices', 'activeBookings', 'activeBooking'));
    }
}

