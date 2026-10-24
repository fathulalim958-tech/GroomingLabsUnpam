<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OrderController extends Controller
{
    /**
     * Display the list of orders with upcoming and monthly filter views.
     */
    public function index(Request $request)
    {
        $viewType = $request->get('view', 'upcoming'); // upcoming, month, all
        $selectedMonth = $request->get('month', Carbon::today()->format('Y-m'));
        $category = $request->get('category', 'all');
        $status = $request->get('status', 'all');
        $search = $request->get('search');

        $query = Booking::with(['user', 'items.service', 'staff', 'cashier']);

        // Date range filtering based on view or specific date
        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->get('date'));
        } elseif ($viewType === 'upcoming') {
            $query->whereDate('booking_date', '>', Carbon::today());
        } elseif ($viewType === 'month') {
            $startOfMonth = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
            $endOfMonth = Carbon::createFromFormat('Y-m', $selectedMonth)->endOfMonth();
            $query->whereDate('booking_date', '>=', $startOfMonth)
                  ->whereDate('booking_date', '<=', $endOfMonth);
        }

        // Category filter
        if ($category !== 'all' && in_array($category, ['barbershop', 'mua'])) {
            $query->where('service_category', $category);
        }

        // Status filter
        if ($status !== 'all' && array_key_exists($status, Booking::STATUS_LABELS)) {
            $query->where('status', $status);
        }

        // Search filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('queue_number', 'like', "%{$search}%");
            });
        }

        // Sorting
        if ($viewType === 'upcoming') {
            $query->orderBy('booking_date', 'asc')->orderBy('booking_time', 'asc');
        } else {
            $query->orderBy('booking_date', 'desc')->orderBy('booking_time', 'desc');
        }

        $orders = $query->paginate(15)->withQueryString();

        // Metrics for badges & summary cards
        $upcomingCount = Booking::whereDate('booking_date', '>', Carbon::today())
            ->whereIn('status', ['waiting', 'called', 'in_progress'])
            ->count();

        $currentMonthCount = Booking::whereYear('booking_date', Carbon::today()->year)
            ->whereMonth('booking_date', Carbon::today()->month)
            ->count();

        return view('kasir.orders.index', compact(
            'orders',
            'viewType',
            'selectedMonth',
            'category',
            'status',
            'search',
            'upcomingCount',
            'currentMonthCount'
        ));
    }
}
