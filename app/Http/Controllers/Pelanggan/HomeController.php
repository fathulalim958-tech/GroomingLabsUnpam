<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Booking;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the customer home page with service catalog.
     */
    public function index()
    {
        $barbershopServices = Service::active()->barbershop()->take(6)->get();
        $muaServices = Service::active()->mua()->take(6)->get();

        // Get current active booking for this customer (today)
        $activeBooking = Booking::where('user_id', auth()->id())
            ->today()
            ->whereIn('status', ['waiting', 'in_progress', 'skipped'])
            ->first();

        return view('pelanggan.home', compact('barbershopServices', 'muaServices', 'activeBooking'));
    }
}

