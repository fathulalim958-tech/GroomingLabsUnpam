<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers with search and filters.
     */
    public function index(Request $request)
    {
        // Only registered users who have created an account are shown in Data Pelanggan
        $query = User::where('role', 'pelanggan')
            ->where('email', 'not like', '%@walkin.local')
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
     * Update customer profile biodata & optional password reset.
     */
    public function update(Request $request, User $customer)
    {
        if ($customer->role !== 'pelanggan') {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $customer->id],
            'phone' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap pelanggan wajib diisi.',
            'email.required' => 'Alamat email pelanggan wajib diisi.',
            'email.unique' => 'Alamat email sudah digunakan oleh akun lain.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;

        $passwordUpdated = false;
        if ($request->filled('password')) {
            $customer->password = Hash::make($request->password);
            $passwordUpdated = true;
        }

        $customer->save();

        $message = "Biodata pelanggan {$customer->name} berhasil diperbarui" . ($passwordUpdated ? " beserta kata sandi baru." : ".");

        return back()->with('success', $message);
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

    /**
     * Update customer membership and discount settings.
     */
    public function updateMembership(Request $request, User $customer)
    {
        if ($customer->role !== 'pelanggan') {
            abort(403);
        }

        $validated = $request->validate([
            'is_member' => 'required|boolean',
            'member_discount' => 'required_if:is_member,1|numeric|min:0',
            'member_discount_type' => 'required_if:is_member,1|in:percent,fixed',
            'member_discount_quota' => 'required_if:is_member,1|integer|min:0',
            'member_discount_remaining' => 'nullable|integer|min:0',
        ], [
            'member_discount.required_if' => 'Besaran diskon wajib diisi jika mendaftarkan member.',
            'member_discount_quota.required_if' => 'Jumlah kuota pemakaian diskon wajib diisi.',
        ]);

        if ($validated['is_member']) {
            $quota = (int) $validated['member_discount_quota'];
            $remaining = $request->filled('member_discount_remaining')
                ? (int) $validated['member_discount_remaining']
                : $quota;

            $customer->update([
                'is_member' => true,
                'member_discount' => $validated['member_discount'],
                'member_discount_type' => $validated['member_discount_type'],
                'member_discount_quota' => $quota,
                'member_discount_remaining' => $remaining,
            ]);

            return back()->with('success', "Status keanggotaan member {$customer->name} berhasil disimpan.");
        }

        $customer->update([
            'is_member' => false,
            'member_discount' => 0,
            'member_discount_type' => 'percent',
            'member_discount_quota' => 0,
            'member_discount_remaining' => 0,
        ]);

        return back()->with('success', "Status member {$customer->name} telah dinonaktifkan.");
    }
}
