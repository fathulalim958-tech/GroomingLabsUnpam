<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminController extends Controller
{
    private const EMPLOYEE_POSITIONS = ['MUA Artist', 'Hair Stylist'];

    public function dashboard(): View
    {
        $today = now();
        $todayBookings = Booking::with('service')
            ->whereDate('appointment_at', $today)
            ->get();

        return view('Admin.dashboard', [
            'todayBookings' => $todayBookings,
            'todayRevenue' => $todayBookings->whereIn('status', ['confirmed', 'completed'])->sum(fn (Booking $booking): int => $booking->service->price),
            'weeklyRevenue' => collect(range(6, 0))->map(function (int $daysAgo) use ($today): int {
                return Booking::with('service')
                    ->whereDate('appointment_at', $today->copy()->subDays($daysAgo))
                    ->whereIn('status', ['confirmed', 'completed'])
                    ->get()
                    ->sum(fn (Booking $booking): int => $booking->service->price);
            }),
            'employees' => User::whereIn('role', ['kasir', 'admin'])->orderBy('role')->orderBy('name')->get(),
        ]);
    }

    public function storeEmployee(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'regex:/^[A-Za-z0-9._-]+$/', 'min:3', 'max:50', 'unique:users,username'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'position' => ['required', Rule::in(self::EMPLOYEE_POSITIONS)],
        ]);

        DB::transaction(function () use ($data): void {
            $user = User::create([
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => Str::lower($data['username']).'@ruangrias.local',
                'password' => $data['password'],
                'role' => 'kasir',
            ]);

            Employee::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'position' => $data['position'],
            ]);
        });

        return to_route('admin.karyawan')->with('success', 'Akun dan profil karyawan berhasil dibuat.');
    }

    public function employees(): View
    {
        return view('Admin.KelolaKaryawan', [
            'employees' => Employee::with('user')->orderBy('name')->get(),
            'positions' => self::EMPLOYEE_POSITIONS,
        ]);
    }

    public function updateEmployee(Request $request, Employee $employee): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'position' => ['required', Rule::in(self::EMPLOYEE_POSITIONS)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        DB::transaction(function () use ($data, $employee): void {
            $employee->update(['name' => $data['name'], 'position' => $data['position']]);
            if ($employee->user) {
                $userData = ['name' => $data['name']];
                if (!empty($data['password'])) {
                    $userData['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
                }
                $employee->user->update($userData);
            }
        });

        return to_route('admin.karyawan')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroyEmployee(Employee $employee): RedirectResponse
    {
        DB::transaction(function () use ($employee): void {
            $user = $employee->user;
            $employee->delete();
            $user?->delete();
        });

        return to_route('admin.karyawan')->with('success', 'Akun dan profil karyawan berhasil dihapus.');
    }
}
