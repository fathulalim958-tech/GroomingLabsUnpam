<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Display a listing of staff/kapsters with their active status.
     */
    public function index(Request $request)
    {
        $roleFilter = $request->get('role', 'all');
        $statusFilter = $request->get('status', 'all');
        $search = $request->get('search', '');

        $query = Staff::with(['bookings' => function ($q) {
            $q->whereDate('booking_date', Carbon::today())
              ->where('status', 'in_progress');
        }])
        ->withCount([
            'bookings as bookings_today_completed' => function ($q) {
                $q->whereDate('booking_date', Carbon::today())
                  ->where('status', 'completed');
            },
            'bookings as bookings_today_total' => function ($q) {
                $q->whereDate('booking_date', Carbon::today());
            }
        ]);

        if (in_array($roleFilter, ['kapster', 'mua'])) {
            $query->where('role', $roleFilter);
        }

        if ($statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $staffs = $query->orderBy('is_active', 'desc')
            ->orderBy('role', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        // Calculate KPI stats
        $allStaff = Staff::all();
        $totalStaff = $allStaff->count();
        $totalKapsters = $allStaff->where('role', 'kapster')->count();
        $activeKapsters = $allStaff->where('role', 'kapster')->where('is_active', true)->count();
        $totalMuas = $allStaff->where('role', 'mua')->count();
        $activeMuas = $allStaff->where('role', 'mua')->where('is_active', true)->count();

        // Count staff currently on duty / in_progress today
        $servingStaffIds = Booking::today()
            ->where('status', 'in_progress')
            ->whereNotNull('staff_id')
            ->pluck('staff_id')
            ->unique();
        $onDutyCount = $servingStaffIds->count();

        return view('kasir.staffs.index', compact(
            'staffs',
            'roleFilter',
            'statusFilter',
            'search',
            'totalStaff',
            'totalKapsters',
            'activeKapsters',
            'totalMuas',
            'activeMuas',
            'onDutyCount'
        ));
    }
}

