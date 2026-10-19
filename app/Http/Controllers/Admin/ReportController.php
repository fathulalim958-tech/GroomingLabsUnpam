<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Display reports dashboard with filtering.
     */
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::today()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::today()->format('Y-m-d'));
        $category = $request->get('category', 'all');

        $query = Booking::with(['user', 'cashier', 'items.service'])
            ->whereDate('booking_date', '>=', $startDate)
            ->whereDate('booking_date', '<=', $endDate)
            ->where('status', 'completed');

        if ($category !== 'all' && in_array($category, ['barbershop', 'mua'])) {
            $query->where('service_category', $category);
        }

        $bookings = (clone $query)->orderBy('booking_date', 'desc')->paginate(15)->withQueryString();

        // Calculate summary
        $summary = [
            'total_completed_orders' => (clone $query)->count(),
            'total_revenue'          => (clone $query)->sum('total_price'),
            'barbershop_revenue'     => (clone $query)->where('service_category', 'barbershop')->sum('total_price'),
            'mua_revenue'            => (clone $query)->where('service_category', 'mua')->sum('total_price'),
            'barbershop_orders'      => (clone $query)->where('service_category', 'barbershop')->count(),
            'mua_orders'             => (clone $query)->where('service_category', 'mua')->count(),
        ];

        return view('admin.reports.index', compact('bookings', 'summary', 'startDate', 'endDate', 'category'));
    }

    /**
     * Export reports as CSV / Excel format.
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        $startDate = $request->get('start_date', Carbon::today()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::today()->format('Y-m-d'));
        $category = $request->get('category', 'all');

        $query = Booking::with(['user', 'cashier', 'items.service'])
            ->whereDate('booking_date', '>=', $startDate)
            ->whereDate('booking_date', '<=', $endDate)
            ->where('status', 'completed');

        if ($category !== 'all' && in_array($category, ['barbershop', 'mua'])) {
            $query->where('service_category', $category);
        }

        $bookings = $query->orderBy('booking_date', 'desc')->get();

        $filename = "laporan-transaksi-{$startDate}-sampai-{$endDate}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($bookings) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'No',
                'Kode Booking',
                'Nomor Antrian',
                'Nama Pelanggan',
                'Kategori Layanan',
                'Daftar Layanan',
                'Tanggal Booking',
                'Waktu Booking',
                'Kasir',
                'Total Harga (Rp)',
                'Status'
            ]);

            foreach ($bookings as $index => $booking) {
                fputcsv($file, [
                    $index + 1,
                    $booking->booking_code,
                    $booking->queue_number,
                    $booking->user->name ?? 'Walk-in',
                    ucfirst($booking->service_category),
                    $booking->items->map(fn($item) => $item->service->name)->join(', '),
                    $booking->booking_date->format('d/m/Y'),
                    $booking->booking_time,
                    $booking->cashier->name ?? '-',
                    $booking->total_price,
                    $booking->status_label,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export reports as printable HTML / PDF view.
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::today()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::today()->format('Y-m-d'));
        $category = $request->get('category', 'all');

        $query = Booking::with(['user', 'cashier', 'items.service'])
            ->whereDate('booking_date', '>=', $startDate)
            ->whereDate('booking_date', '<=', $endDate)
            ->where('status', 'completed');

        if ($category !== 'all' && in_array($category, ['barbershop', 'mua'])) {
            $query->where('service_category', $category);
        }

        $bookings = $query->orderBy('booking_date', 'desc')->get();
        $totalRevenue = $bookings->sum('total_price');

        return view('admin.reports.print', compact('bookings', 'totalRevenue', 'startDate', 'endDate', 'category'));
    }
}
