<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;

class CashierController extends Controller
{
    public function index()
    {
        $cashiers = User::where('role', 'kasir')
            ->with('services')
            ->orderBy('name')
            ->paginate(15);
        $staffCount = Staff::count();

        return view('admin.cashiers.index', compact('cashiers', 'staffCount'));
    }

    public function create()
    {
        // Only staff who don't already have an active cashier account can be selected
        $existingCashierNames = User::where('role', 'kasir')->pluck('name')->toArray();
        $staffs = Staff::whereNotIn('name', $existingCashierNames)->orderBy('name')->get();
        $barberServices = Service::barbershop()->active()->orderBy('name')->get();
        $muaServices = Service::mua()->active()->orderBy('name')->get();

        return view('admin.cashiers.create', compact('staffs', 'barberServices', 'muaServices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'exists:staffs,name',
                function ($attribute, $value, $fail) {
                    if (User::where('role', 'kasir')->where('name', $value)->exists()) {
                        $fail("Staf '{$value}' sudah memiliki akun kasir aktif.");
                    }
                },
            ],
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+]+$/'],
            'service_category' => ['nullable', 'in:barbershop,mua'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'work_days' => 'nullable|array',
            'work_days.*' => 'integer|min:0|max:6',
            'services' => 'nullable|array',
            'services.*' => 'integer|exists:services,id',
        ], [
            'name.exists' => 'Nama kasir harus dipilih dari data karyawan yang sudah terdaftar.',
            'phone.regex' => 'Nomor WhatsApp / telepon hanya boleh berisi angka nomor seluler.',
        ]);

        $workDays = $request->filled('work_days')
            ? array_map('intval', $request->input('work_days'))
            : [1, 2, 3, 4, 5, 6, 0];

        $category = $request->input('service_category', 'barbershop');

        $cashier = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => bcrypt($validated['password']),
            'role' => 'kasir',
            'service_category' => $category,
            'work_days' => $workDays,
        ]);

        if ($request->has('services')) {
            $validServiceIds = Service::where('category', $category)
                ->whereIn('id', (array) $request->input('services', []))
                ->pluck('id');
            $cashier->services()->sync($validServiceIds);
        }

        return redirect()->route('admin.cashiers.index')->with('success', 'Kasir berhasil ditambahkan.');
    }

    public function show(User $cashier)
    {
        $cashier->load('services');

        return view('admin.cashiers.show', compact('cashier'));
    }

    public function edit(User $cashier)
    {
        // Available staffs are those who don't have another cashier account, plus this current cashier's staff
        $otherCashierNames = User::where('role', 'kasir')
            ->where('id', '!=', $cashier->id)
            ->pluck('name')
            ->toArray();
        $staffs = Staff::whereNotIn('name', $otherCashierNames)->orderBy('name')->get();
        $barberServices = Service::barbershop()->active()->orderBy('name')->get();
        $muaServices = Service::mua()->active()->orderBy('name')->get();

        $cashier->load('services');

        if (empty($cashier->service_category)) {
            $matchedStaff = $staffs->firstWhere('name', $cashier->name);
            $cashier->service_category = ($matchedStaff && $matchedStaff->role === 'mua') ? 'mua' : 'barbershop';
        }

        return view('admin.cashiers.edit', compact('cashier', 'staffs', 'barberServices', 'muaServices'));
    }

    public function update(Request $request, User $cashier)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($cashier) {
                    $alreadyUsedByOther = User::where('role', 'kasir')
                        ->where('name', $value)
                        ->where('id', '!=', $cashier->id)
                        ->exists();
                    if ($alreadyUsedByOther) {
                        $fail("Staf '{$value}' sudah digunakan oleh akun kasir lain.");
                    }
                },
            ],
            'email' => 'required|string|email|max:255|unique:users,email,'.$cashier->id,
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+]+$/'],
            'service_category' => ['nullable', 'in:barbershop,mua'],
            'is_active' => ['nullable', 'boolean'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'work_days' => 'nullable|array',
            'work_days.*' => 'integer|min:0|max:6',
            'services' => 'nullable|array',
            'services.*' => 'integer|exists:services,id',
        ], [
            'phone.regex' => 'Nomor WhatsApp / telepon hanya boleh berisi angka nomor seluler.',
        ]);

        $category = $request->input('service_category', $cashier->service_category ?? 'barbershop');

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'service_category' => $category,
        ];

        if ($request->has('work_days')) {
            $updateData['work_days'] = array_map('intval', (array) $request->input('work_days', []));
        }

        if ($request->has('is_active')) {
            $updateData['is_active'] = $request->boolean('is_active');
        }

        $cashier->update($updateData);

        // Sync services strictly for the chosen category
        $selectedServiceIds = (array) $request->input('services', []);
        $validServiceIds = Service::where('category', $category)
            ->whereIn('id', $selectedServiceIds)
            ->pluck('id');
        $cashier->services()->sync($validServiceIds);

        if (! empty($validated['password'])) {
            $cashier->update(['password' => bcrypt($validated['password'])]);
        }

        return redirect()->route('admin.cashiers.index')->with('success', 'Data kasir & layanan berhasil diperbarui.');
    }

    public function updateSchedule(Request $request, User $cashier)
    {
        $validated = $request->validate([
            'work_days' => 'nullable|array',
            'work_days.*' => 'integer|min:0|max:6',
        ]);

        $workDays = $request->has('work_days')
            ? array_map('intval', (array) $request->input('work_days', []))
            : [];

        $cashier->update(['work_days' => $workDays]);

        return back()->with('success', "Jadwal tugas kasir {$cashier->name} berhasil diperbarui.");
    }

    public function destroy(User $cashier)
    {
        $cashier->delete();

        return redirect()->route('admin.cashiers.index')->with('success', 'Kasir berhasil dihapus.');
    }

    public function toggleActive(User $cashier)
    {
        $cashier->update(['is_active' => ! $cashier->is_active]);
        $status = $cashier->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun kasir berhasil {$status}.");
    }
}
