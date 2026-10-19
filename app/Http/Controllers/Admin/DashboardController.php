<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Service;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with summary statistics.
     */
    public function index()
    {
        $today = Carbon::today();

        $stats = [
            'total_customers' => User::where('role', 'pelanggan')->count(),
            'total_services' => Service::where('is_active', true)->count(),
            'today_bookings' => Booking::whereDate('booking_date', $today)->count(),
            'today_completed' => Booking::whereDate('booking_date', $today)->where('status', 'completed')->count(),
            'today_revenue' => Booking::whereDate('booking_date', $today)->where('status', 'completed')->sum('total_price'),
            'month_revenue' => Booking::whereMonth('booking_date', $today->month)
                ->whereYear('booking_date', $today->year)
                ->where('status', 'completed')
                ->sum('total_price'),
            'month_bookings' => Booking::whereMonth('booking_date', $today->month)
                ->whereYear('booking_date', $today->year)
                ->count(),
        ];

        // Recent bookings
        $recentBookings = Booking::with(['user', 'items.service'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings'));
    }
}

