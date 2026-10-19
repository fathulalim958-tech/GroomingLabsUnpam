<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Display booking history for the customer.
     */
    public function index(Request $request)
    {
        $query = Booking::with(['items.service'])
            ->where('user_id', auth()->id())
            ->whereIn('status', ['completed', 'cancelled'])
            ->orderBy('created_at', 'desc');

        // Filter by category
        if ($request->filled('category') && in_array($request->category, ['barbershop', 'mua'])) {
            $query->where('service_category', $request->category);
        }

        $bookings = $query->paginate(10);
        $currentFilter = $request->get('category', 'all');

        return view('pelanggan.history.index', compact('bookings', 'currentFilter'));
    }
}

