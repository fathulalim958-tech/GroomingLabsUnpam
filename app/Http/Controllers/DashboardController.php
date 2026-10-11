<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Employee;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    private const WALK_IN_SERVICE_CATALOG = [
        'barber' => [
            'Fast Haircut' => 25000,
            'Rileks Ganteng' => 35000,
            'Full Grooming' => 50000,
        ],
        'mua' => [
            'Makeup Only' => 250000,
            'Make Up + Soft Lens' => 300000,
            'Make Up + Hijab/Hair Do' => 320000,
            'Make Up + Hijab/Hair Do + Soft Lens' => 360000,
        ],
    ];

    // function index(){
    //     echo "hallo selamat datang " . Auth::user()->name;
    //     echo "<br>";
    //     echo "<a href='logout'>Logout</a>";
    // }
    public function pelanggan()
    {
        return view('pelanggan.Dashboard');
    }

    public function kasir()
    {
        $walkInBookings = Booking::query()
            ->whereDate('appointment_at', now()->toDateString())
            ->where('status', '!=', 'cancelled')
            ->latest('appointment_at')
            ->get();

        $onlineBookings = clone $walkInBookings;
        $onlineBookings = $onlineBookings->whereNull('cashier_id');
        $walkInOnly = clone $walkInBookings;
        $walkInOnly = $walkInOnly->whereNotNull('cashier_id');

        $onlineRevenue = $onlineBookings->where('status', 'completed')->sum('service_price');
        $onlineCompletedCount = $onlineBookings->where('status', 'completed')->count();

        $walkInRevenue = $walkInOnly->where('status', 'completed')->sum('service_price');
        $walkInCompletedCount = $walkInOnly->where('status', 'completed')->count();

        $totalRevenue = $onlineRevenue + $walkInRevenue;

        $loggedInEmployee = \App\Models\Employee::where('name', \Illuminate\Support\Facades\Auth::user()->name)->first();

        return view('Kasir.Dashboard', [
            'walkInBookings' => $walkInBookings,
            'onlineRevenue' => $onlineRevenue,
            'onlineCompletedCount' => $onlineCompletedCount,
            'walkInRevenue' => $walkInRevenue,
            'walkInCompletedCount' => $walkInCompletedCount,
            'totalRevenue' => $totalRevenue,
            'loggedInEmployee' => $loggedInEmployee,
        ]);
    }

    public function bookingWalkin()
    {
        return view('Kasir.BookingWalkin', [
            'walkInServiceCatalog' => self::WALK_IN_SERVICE_CATALOG,
            'employees' => Employee::query()
                ->orderBy('name')
                ->get(['name', 'position'])
                ->toArray(),
        ]);
    }

    public function storeWalkin(Request $request): RedirectResponse
    {
        $category = $request->input('category');
        $availableServices = self::WALK_IN_SERVICE_CATALOG[$category] ?? [];
        $staffPosition = $category === 'mua' ? 'MUA Artist' : 'Hair Stylist';
        $availableStaff = Employee::query()
            ->where('position', $staffPosition)
            ->pluck('name')
            ->all();

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:100'],
            'category' => ['required', Rule::in(array_keys(self::WALK_IN_SERVICE_CATALOG))],
            'service_name' => ['required', 'string', Rule::in(array_keys($availableServices))],
            'staff_name' => ['required', 'string', Rule::in($availableStaff)],
        ]);

        Booking::create([
            'customer_name' => $data['customer_name'],
            'category' => $data['category'],
            'service_name' => $data['service_name'],
            'service_price' => self::WALK_IN_SERVICE_CATALOG[$data['category']][$data['service_name']],
            'staff_name' => $data['staff_name'],
            'cashier_id' => $request->user()->getKey(),
            'appointment_at' => now(),
            'status' => 'pending',
        ]);

        return to_route('kasir.booking.walkin')->with('success', 'Booking walk-in berhasil disimpan.');
    }

    public function completeBooking(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless(in_array($booking->status, ['pending', 'confirmed', 'in_progress'], true), 409);

        $employee = \App\Models\Employee::where('name', $request->user()->name)->first();
        if ($employee) {
            if ($employee->position === 'Hair Stylist' && $booking->category === 'mua') {
                abort(403, 'Hair Stylist tidak dapat memproses booking MUA.');
            }
            if ($employee->position === 'MUA Artist' && $booking->category === 'barber') {
                abort(403, 'MUA Artist tidak dapat memproses booking Barber.');
            }
        }

        if (in_array($booking->status, ['pending', 'confirmed'])) {
            $booking->update(['status' => 'in_progress']);
            return to_route('kasir.dashboard')->with('success', 'Booking sedang diproses.');
        }

        $booking->update(['status' => 'completed']);

        return to_route('kasir.dashboard')->with('success', 'Booking berhasil diselesaikan.');
    }

    public function admin()
    {
        $today = CarbonImmutable::today();
        $monthStart = $today->startOfMonth();
        $todayBookings = Booking::query()->whereDate('appointment_at', $today);
        $todayWalkIns = Booking::query()
            ->whereDate('appointment_at', $today)
            ->whereNotNull('cashier_id');

        $weeklyDays = collect(range(6, 0))->map(function (int $daysAgo) use ($today): array {
            $date = $today->subDays($daysAgo);
            $revenue = Booking::query()
                ->whereDate('appointment_at', $date)
                ->where('status', 'completed')
                ->sum('service_price');

            return [
                'date' => $date,
                'label' => [1 => 'Sen', 2 => 'Sel', 3 => 'Rab', 4 => 'Kam', 5 => 'Jum', 6 => 'Sab', 7 => 'Min'][$date->dayOfWeekIso],
                'revenue' => (int) $revenue,
                'isToday' => $date->isSameDay($today),
            ];
        });
        $maxDailyRevenue = max(1, $weeklyDays->max('revenue'));
        $weeklyDays = $weeklyDays->map(function (array $day) use ($maxDailyRevenue): array {
            $day['barHeight'] = $day['revenue'] > 0 ? max(12, (int) round($day['revenue'] / $maxDailyRevenue * 100)) : 4;

            return $day;
        });

        $completedServiceCounts = Booking::query()
            ->where('status', 'completed')
            ->selectRaw('staff_name, COUNT(*) as completed_count')
            ->groupBy('staff_name')
            ->pluck('completed_count', 'staff_name');
        $employees = Employee::query()
            ->orderBy('name')
            ->get()
            ->map(function (Employee $employee) use ($completedServiceCounts): Employee {
                $employee->setAttribute('completed_service_count', (int) $completedServiceCounts->get($employee->name, 0));

                return $employee;
            });

        $monthRevenue = Booking::query()
            ->where('status', 'completed')
            ->whereBetween('appointment_at', [$monthStart->startOfDay(), $today->endOfDay()])
            ->sum('service_price');

        return view('Admin.Dashboard', [
            'dashboardDate' => $today->locale('id')->translatedFormat('l, d F Y'),
            'monthStartLabel' => $monthStart->format('d/m/Y'),
            'monthEndLabel' => $today->format('d/m/Y'),
            'monthRevenue' => (int) $monthRevenue,
            'todayBookingCount' => $todayBookings->count(),
            'todayPendingCount' => (clone $todayBookings)->where('status', 'pending')->count(),
            'todayInProgressCount' => (clone $todayBookings)->whereIn('status', ['confirmed', 'in_progress'])->count(),
            'todayWalkInCount' => (clone $todayWalkIns)->count(),
            'todayWalkInRevenue' => (int) (clone $todayWalkIns)->where('status', 'completed')->sum('service_price'),
            'employeeCount' => $employees->count(),
            'weeklyRevenue' => (int) $weeklyDays->sum('revenue'),
            'weeklyStartLabel' => $today->subDays(6)->format('d/m'),
            'weeklyEndLabel' => $today->format('d/m'),
            'weeklyDays' => $weeklyDays,
            'employees' => $employees,
        ]);
    }

    public function kelolaKaryawan()
    {
        return view('Admin.KelolaKaryawan');
    }

    public function laporanPendapatan(Request $request): View|RedirectResponse
    {
        if ($request->hasAny(['start_date', 'end_date'])) {
            $range = $request->validate([
                'start_date' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
                'end_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_date', 'before_or_equal:today'],
            ]);

            $startDate = CarbonImmutable::parse($range['start_date']);
            $endDate = CarbonImmutable::parse($range['end_date']);
        } else {
            $startDate = CarbonImmutable::now()->startOfMonth();
            $endDate = CarbonImmutable::today();
        }

        if ($startDate->diffInDays($endDate) > 30) {
            return back()->withErrors(['end_date' => 'Rentang laporan maksimal 31 hari.'])->withInput();
        }

                $query = Booking::query()
            ->whereBetween('appointment_at', [$startDate->startOfDay(), $endDate->endOfDay()]);

        $completedQuery = (clone $query)->where('status', 'completed');
        $reportTotal = (clone $completedQuery)->sum('service_price');
        $barberTotal = (clone $completedQuery)->where('category', 'barber')->sum('service_price');
        $muaTotal = (clone $completedQuery)->where('category', 'mua')->sum('service_price');

        $transactions = $query->latest('appointment_at')->paginate(10)->withQueryString();

        return view('Admin.LaporanPendapatan', [
            'transactions' => $transactions,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
            'today' => CarbonImmutable::today()->toDateString(),
            'reportTotal' => $reportTotal,
            'barberTotal' => $barberTotal,
            'muaTotal' => $muaTotal,
        ]);
    }

    public function exportLaporanPendapatan(Request $request): StreamedResponse|RedirectResponse
    {
        $range = $request->validate([
            'start_date' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'end_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_date', 'before_or_equal:today'],
        ]);

        $startDate = CarbonImmutable::parse($range['start_date']);
        $endDate = CarbonImmutable::parse($range['end_date']);

        if ($startDate->diffInDays($endDate) > 30) {
            return back()->withErrors(['end_date' => 'Rentang ekspor maksimal 31 hari.'])->withInput();
        }

        return response()->streamDownload(function () use ($startDate, $endDate): void {
            $transactions = Booking::query()
                ->whereBetween('appointment_at', [$startDate->startOfDay(), $endDate->endOfDay()])
                ->orderBy('appointment_at')
                ->get();
            $completedTransactions = $transactions->where('status', 'completed');
            $completedRevenue = $completedTransactions->sum('service_price');

            $stream = fopen('php://output', 'w');
            fwrite($stream, "\xEF\xBB\xBF");
            fputcsv($stream, ['sep=,'], ',', '"', '\\');
            fputcsv($stream, ['LAPORAN PENDAPATAN GLOWCUT'], ',', '"', '\\');
            fputcsv($stream, ['Periode', $startDate->format('d/m/Y'), 's.d.', $endDate->format('d/m/Y')], ',', '"', '\\');
            fputcsv($stream, ['Dibuat pada', CarbonImmutable::now()->format('d/m/Y H:i')], ',', '"', '\\');
            fputcsv($stream, [], ',', '"', '\\');
            fputcsv($stream, ['RINGKASAN'], ',', '"', '\\');
            fputcsv($stream, ['Pendapatan selesai (Rp)', $completedRevenue], ',', '"', '\\');
            fputcsv($stream, ['Pendapatan Barbershop selesai (Rp)', $completedTransactions->where('category', 'barber')->sum('service_price')], ',', '"', '\\');
            fputcsv($stream, ['Pendapatan MUA selesai (Rp)', $completedTransactions->where('category', 'mua')->sum('service_price')], ',', '"', '\\');
            fputcsv($stream, ['Jumlah transaksi', $transactions->count()], ',', '"', '\\');
            fputcsv($stream, ['Transaksi selesai', $completedTransactions->count()], ',', '"', '\\');
            fputcsv($stream, ['Transaksi menunggu', $transactions->where('status', 'pending')->count()], ',', '"', '\\');
            fputcsv($stream, [], ',', '"', '\\');
            fputcsv($stream, ['No.', 'Tanggal', 'Waktu', 'Jenis', 'Pelanggan', 'Kategori', 'Layanan', 'Staff', 'Harga (Rp)', 'Status'], ',', '"', '\\');

            foreach ($transactions->values() as $index => $transaction) {
                $price = (int) ($transaction->service_price ?? 0);

                fputcsv($stream, [
                    $index + 1,
                    $transaction->appointment_at?->format('Y-m-d') ?? '',
                    $transaction->appointment_at?->format('H:i') ?? '',
                    $transaction->cashier_id ? 'Walk-in' : 'Online',
                    $this->spreadsheetText($transaction->customer_name),
                    $transaction->category === 'mua' ? 'MUA' : 'Barbershop',
                    $this->spreadsheetText($transaction->service_name),
                    $this->spreadsheetText($transaction->staff_name),
                    $price,
                    ucfirst($transaction->status),
                ], ',', '"', '\\');
            }

            fputcsv($stream, [], ',', '"', '\\');
            fputcsv($stream, ['', '', '', '', '', '', 'TOTAL PENDAPATAN SELESAI (Rp)', $completedRevenue], ',', '"', '\\');
            fclose($stream);
        }, 'laporan-pendapatan_'.$startDate->toDateString().'_'.$endDate->toDateString().'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function spreadsheetText(?string $value): string
    {
        $value ??= '';

        return preg_match('/^[\s]*[=+\-@]/u', $value) === 1 ? "'{$value}" : $value;
    }

    public function Barbershop()
    {
        return view('pelanggan.Barbershop');
    }

    public function MUA()
    {
        return view('pelanggan.MUA');
    }

    public function booking()
    {
        return view('pelanggan.Booking');
    }

    public function riwayat(Request $request)
    {
        $bookings = Booking::where('user_id', $request->user()->getKey())
                           ->whereIn('status', ['completed', 'cancelled'])
                           ->orderBy('appointment_at', 'desc')
                           ->get();
        return view('pelanggan.Riwayat', compact('bookings'));
    }

    public function order(Request $request)
    {
        $bookings = Booking::where('user_id', $request->user()->getKey())
                           ->whereNotIn('status', ['completed', 'cancelled'])
                           ->orderBy('appointment_at', 'desc')
                           ->get();
        return view('pelanggan.Order', compact('bookings'));
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        
        $totalBooking = Booking::where('user_id', $user->getKey())->count();
        $selesai = Booking::where('user_id', $user->getKey())->where('status', 'completed')->count();
        $dibatalkan = Booking::where('user_id', $user->getKey())->where('status', 'cancelled')->count();
        
        return view('pelanggan.Profile', compact('user', 'totalBooking', 'selesai', 'dibatalkan'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    public function bookingInput(Request $request)
    {
        $category = $request->query('category', 'barber');
        $services = self::WALK_IN_SERVICE_CATALOG[$category] ?? self::WALK_IN_SERVICE_CATALOG['barber'];
        
        $staffPosition = $category === 'mua' ? 'MUA Artist' : 'Hair Stylist';
        $employees = Employee::where('position', $staffPosition)->get();
        
        return view('pelanggan.BookingInput', compact('employees', 'services', 'category'));
    }

    public function storeBooking(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category' => ['required', 'string', \Illuminate\Validation\Rule::in(['barber', 'mua'])],
            'service_name' => 'required|string|max:120',
            'service_price' => 'required|integer',
            'staff_name' => 'required|string|max:100',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|string',
            'customer_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $appointmentAt = CarbonImmutable::parse($data['appointment_date'] . ' ' . $data['appointment_time']);

        Booking::create([
            'customer_name' => $data['customer_name'],
            'category' => $data['category'],
            'service_name' => $data['service_name'],
            'service_price' => $data['service_price'],
            'staff_name' => $data['staff_name'],
            'user_id' => $request->user()->getKey(),
            'appointment_at' => $appointmentAt,
            'status' => 'pending',
            'notes' => $data['notes'] . ' (Phone: ' . $data['phone'] . ')',
        ]);

        return to_route('pelanggan.order')->with('success', 'Booking berhasil dibuat!');
    }

    public function cancelBooking(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === $request->user()->id, 403);
        abort_unless(in_array($booking->status, ['pending', 'confirmed']), 400, 'Booking cannot be cancelled.');

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
