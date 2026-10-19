<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers with search and filters.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'pelanggan')
            ->withCount('bookings')
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Display customer details and booking history.
     */
    public function show(User $customer)
    {
        if ($customer->role !== 'pelanggan') {
            abort(404);
        }

        $customer->load(['bookings' => function ($q) {
            $q->with('items.service')->latest();
        }]);

        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Delete customer.
     */
    public function destroy(User $customer)
    {
        if ($customer->role !== 'pelanggan') {
            abort(403);
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Data pelanggan berhasil dihapus.');
    }

    /**
     * Toggle block/active status of customer.
     */
    public function toggleBlock(User $customer)
    {
        if ($customer->role !== 'pelanggan') {
            abort(403);
        }

        $customer->is_active = !$customer->is_active;
        $customer->save();

        $status = $customer->is_active ? 'diaktifkan kembali' : 'diblokir';

        return back()->with('success', "Akun pelanggan berhasil {$status}.");
    }

    /**
     * Reset customer password to default.
     */
    public function resetPassword(User $customer)
    {
        if ($customer->role !== 'pelanggan') {
            abort(403);
        }

        $newPassword = 'password123';
        $customer->password = Hash::make($newPassword);
        $customer->save();

        return back()->with('success', "Password pelanggan berhasil di-reset ke: {$newPassword}");
    }
}

