<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function dashboard(): View|RedirectResponse
    {
        $user = request()->user();

        if ($user->role === 'admin') {
            return to_route('admin.dashboard');
        }

        $bookings = $user->role === 'customer'
            ? $user->bookings()->with('service')->latest('appointment_at')->get()
            : Booking::with(['user', 'service'])->latest('appointment_at')->get();

        return view('dashboard', ['bookings' => $bookings, 'services' => Service::where('is_active', true)->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'appointment_at' => ['required', 'date', 'after:now'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $alreadyBooked = Booking::where('appointment_at', $data['appointment_at'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();
        if ($alreadyBooked) {
            return back()->withErrors(['appointment_at' => 'Jam tersebut sudah dipesan. Pilih waktu lain.'])->withInput();
        }

        request()->user()->bookings()->create($data);

        return to_route('dashboard')->with('success', 'Booking berhasil dibuat dan menunggu konfirmasi kasir.');
    }

    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', Rule::in(['confirmed', 'completed', 'cancelled'])]]);
        $booking->update($data);

        return to_route('dashboard')->with('success', 'Status booking diperbarui.');
    }
}
