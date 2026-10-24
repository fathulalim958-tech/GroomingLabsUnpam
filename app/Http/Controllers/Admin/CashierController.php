<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;

class CashierController extends Controller
{
    public function index()
    {
        $cashiers = User::where('role', 'kasir')->orderBy('name')->paginate(15);
        return view('admin.cashiers.index', compact('cashiers'));
    }

    public function create()
    {
        return view('admin.cashiers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+]+$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'phone.regex' => 'Nomor WhatsApp / telepon hanya boleh berisi angka nomor seluler.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => bcrypt($validated['password']),
            'role' => 'kasir',
        ]);

        return redirect()->route('admin.cashiers.index')->with('success', 'Kasir berhasil ditambahkan.');
    }

    public function show(User $cashier)
    {
        return view('admin.cashiers.show', compact('cashier'));
    }

    public function edit(User $cashier)
    {
        return view('admin.cashiers.edit', compact('cashier'));
    }

    public function update(Request $request, User $cashier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $cashier->id,
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+]+$/'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ], [
            'phone.regex' => 'Nomor WhatsApp / telepon hanya boleh berisi angka nomor seluler.',
        ]);

        $cashier->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        if (!empty($validated['password'])) {
            $cashier->update(['password' => bcrypt($validated['password'])]);
        }

        return redirect()->route('admin.cashiers.index')->with('success', 'Data kasir berhasil diperbarui.');
    }

    public function destroy(User $cashier)
    {
        $cashier->delete();
        return redirect()->route('admin.cashiers.index')->with('success', 'Kasir berhasil dihapus.');
    }

    public function toggleActive(User $cashier)
    {
        $cashier->update(['is_active' => !$cashier->is_active]);
        $status = $cashier->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun kasir berhasil {$status}.");
    }
}

