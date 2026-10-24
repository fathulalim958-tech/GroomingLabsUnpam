<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display list of customers (registered accounts and walk-in records).
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $tab = $request->get('tab', 'registered'); // registered or walkin

        if ($tab === 'walkin') {
            $query = Booking::whereNull('user_id')
                ->select('customer_name', 'customer_phone')
                ->selectRaw('count(*) as total_orders')
                ->selectRaw('max(booking_date) as last_booking_date')
                ->groupBy('customer_name', 'customer_phone');

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('customer_name', 'like', "%{$search}%")
                      ->orWhere('customer_phone', 'like', "%{$search}%");
                });
            }

            $customers = $query->orderBy('last_booking_date', 'desc')->paginate(15)->withQueryString();
        } else {
            $query = User::where('role', 'pelanggan')
                ->withCount('bookings')
                ->withMax('bookings', 'booking_date');

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            $customers = $query->orderBy('name', 'asc')->paginate(15)->withQueryString();
        }

        $totalRegistered = User::where('role', 'pelanggan')->count();
        $totalWalkIns = Booking::whereNull('user_id')->count();

        return view('kasir.customers.index', compact(
            'customers',
            'tab',
            'search',
            'totalRegistered',
            'totalWalkIns'
        ));
    }

    /**
     * Display detail of a registered customer.
     */
    public function show(User $customer)
    {
        if ($customer->role !== 'pelanggan') {
            abort(404);
        }

        $bookings = Booking::where('user_id', $customer->id)
            ->with(['items.service', 'staff', 'cashier'])
            ->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'desc')
            ->paginate(10);

        return view('kasir.customers.show', compact('customer', 'bookings'));
    }
}

