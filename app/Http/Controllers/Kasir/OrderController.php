<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OrderController extends Controller
{
    /**
     * Display the list of upcoming orders only (excludes completed, cancelled, or past orders).
     * By default displays orders for tomorrow (H+1) with a date picker supporting up to 7 days ahead.
     */
    public function index(Request $request)
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');
        $max7Days = $today->copy()->addDays(7)->format('Y-m-d');

        // By default, display orders for tomorrow (H+1) unless explicitly viewing all or picking a date
        if ($request->get('view') === 'upcoming' || $request->get('date') === 'all') {
            $selectedDate = null;
        } else {
            $selectedDate = $request->get('date', $tomorrow);
        }

        $category = $request->get('category', 'all');
        $search = $request->get('search');

        // Strictly UPCOMING orders only:
        // Exclude completed (sudah dilayani), cancelled (dibatalkan), and skipped
        // Must be today or future dates
        $query = Booking::with(['user', 'items.service', 'staff', 'cashier'])
            ->whereDate('booking_date', '>=', $today)
            ->whereNotIn('status', ['completed', 'cancelled', 'skipped']);

        // Filter per date (defaults to tomorrow)
        if (!empty($selectedDate)) {
            $query->whereDate('booking_date', $selectedDate);
        }

        // Category filter
        if ($category !== 'all' && in_array($category, ['barbershop', 'mua'])) {
            $query->where('service_category', $category);
        }

        // Search filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('queue_number', 'like', "%{$search}%");
            });
        }

        // Sorting: Closest upcoming date and time first
        $query->orderBy('booking_date', 'asc')->orderBy('booking_time', 'asc');

        $orders = $query->paginate(15)->withQueryString();

        return view('kasir.orders.index', compact(
            'orders',
            'selectedDate',
            'category',
            'search',
            'today',
            'tomorrow',
            'max7Days'
        ));
    }
}
