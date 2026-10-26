<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

            if (! empty($search)) {
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

            if (! empty($search)) {
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

    /**
     * Toggle block/active status of customer.
     */
    public function toggleBlock(User $customer)
    {
        if ($customer->role !== 'pelanggan') {
            abort(403);
        }

        $customer->is_active = ! $customer->is_active;
        $customer->save();

        $status = $customer->is_active ? 'diaktifkan kembali' : 'diblokir';

        return back()->with('block_status_updated', [
            'name' => $customer->name,
            'is_active' => $customer->is_active,
            'message' => "Akun {$customer->name} berhasil {$status}.",
        ])->with('success', "Akun pelanggan {$customer->name} berhasil {$status}.");
    }

    /**
     * Reset customer password to custom input or default.
     */
    public function resetPassword(Request $request, User $customer)
    {
        if ($customer->role !== 'pelanggan') {
            abort(403);
        }

        $newPassword = $request->input('password');

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ], [
                'password.required' => 'Password baru wajib diisi.',
                'password.min' => 'Password minimal harus 6 karakter.',
                'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            ]);
            $newPassword = $request->password;
        } else {
            $newPassword = 'password123';
        }

        $customer->password = Hash::make($newPassword);
        $customer->save();

        return back()->with('password_reset_success', [
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'new_password' => $newPassword,
        ])->with('success', "Password baru akun {$customer->name} berhasil disimpan.");
    }
}
