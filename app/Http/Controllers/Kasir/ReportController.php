<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Display cashier report page (Harian & Mingguan).
     */
    public function index(Request $request)
    {
        $reportType = $request->get('type', 'daily'); // daily or weekly
        $selectedDate = $request->get('date', Carbon::today()->format('Y-m-d'));
        $category = $request->get('category', 'all');

        if ($reportType === 'weekly') {
            $startDate = Carbon::parse($selectedDate)->startOfWeek()->format('Y-m-d');
            $endDate = Carbon::parse($selectedDate)->endOfWeek()->format('Y-m-d');
        } else {
            $reportType = 'daily';
            $startDate = $selectedDate;
            $endDate = $selectedDate;
        }

        $query = Booking::with(['user', 'cashier', 'staff', 'items.service'])
            ->whereDate('booking_date', '>=', $startDate)
            ->whereDate('booking_date', '<=', $endDate);

        if ($category !== 'all' && in_array($category, ['barbershop', 'mua'])) {
            $query->where('service_category', $category);
        }

        $allBookings = (clone $query)->get();
        $completedBookings = $allBookings->where('status', 'completed');

        $summary = [
            'total_orders'           => $allBookings->count(),
            'completed_orders'       => $completedBookings->count(),
            'total_revenue'          => $completedBookings->sum('total_price'),
            'barbershop_revenue'     => $completedBookings->where('service_category', 'barbershop')->sum('total_price'),
            'mua_revenue'            => $completedBookings->where('service_category', 'mua')->sum('total_price'),
            'barbershop_orders'      => $completedBookings->where('service_category', 'barbershop')->count(),
            'mua_orders'             => $completedBookings->where('service_category', 'mua')->count(),
        ];

        $bookings = (clone $query)->orderBy('booking_date', 'asc')->orderBy('booking_time', 'asc')->paginate(15)->withQueryString();

        return view('kasir.reports.index', compact(
            'bookings',
            'summary',
            'reportType',
            'selectedDate',
            'startDate',
            'endDate',
            'category'
        ));
    }

    /**
     * Export cashier report to Excel (strictly Daily or Weekly only - Monthly prohibited).
     */
    public function exportExcel(Request $request)
    {
        $reportType = $request->get('type', $request->get('period', 'daily'));
        $selectedDate = $request->get('date', Carbon::today()->format('Y-m-d'));
        $category = $request->get('category', 'all');

        if (in_array(strtolower($reportType), ['monthly', 'month'])) {
            return response('Unduh laporan bulanan tidak diizinkan untuk kasir. Silakan gunakan menu Order untuk melihat orderan secara bulanan.', 400);
        }

        if ($reportType === 'weekly') {
            $startDate = Carbon::parse($selectedDate)->startOfWeek()->format('Y-m-d');
            $endDate = Carbon::parse($selectedDate)->endOfWeek()->format('Y-m-d');
            $reportTitle = 'LAPORAN MINGGUAN KASIR (' . Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y') . ')';
            $filename = 'Laporan-Kasir-Mingguan-' . $startDate . '-sd-' . $endDate . '.xlsx';
        } else {
            $reportType = 'daily';
            $startDate = $selectedDate;
            $endDate = $selectedDate;
            $reportTitle = 'LAPORAN HARIAN KASIR (' . Carbon::parse($startDate)->format('d/m/Y') . ')';
            $filename = 'Laporan-Kasir-Harian-' . $startDate . '.xlsx';
        }

        $query = Booking::with(['user', 'cashier', 'staff', 'items.service'])
            ->whereDate('booking_date', '>=', $startDate)
            ->whereDate('booking_date', '<=', $endDate);

        if ($category !== 'all' && in_array($category, ['barbershop', 'mua'])) {
            $query->where('service_category', $category);
        }

        $bookings = $query->orderBy('booking_date', 'asc')->orderBy('booking_time', 'asc')->get();
        $completedBookings = $bookings->where('status', 'completed');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Pelayanan');

        $spreadsheet->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);

        // Header Title
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'GROOMING LAB - ' . $reportTitle);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Table Header
        $headers = ['No', 'No. Antrian', 'Kode Booking', 'Tanggal & Jam', 'Pelanggan', 'Kategori', 'Layanan', 'Kasir', 'Total Tarif', 'Status'];
        $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

        $headerRow = 3;
        foreach ($headers as $index => $hdr) {
            $cell = $cols[$index] . $headerRow;
            $sheet->setCellValue($cell, $hdr);
            $sheet->getStyle($cell)->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
            $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('1E293B');
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $row = 4;
        $no = 1;
        foreach ($bookings as $b) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $b->queue_number);
            $sheet->setCellValue('C' . $row, $b->booking_code);
            $sheet->setCellValue('D' . $row, $b->booking_date->format('d/m/Y') . ' ' . $b->booking_time);
            $sheet->setCellValue('E' . $row, $b->customer_name);
            $sheet->setCellValue('F' . $row, ucfirst($b->service_category));
            $sheet->setCellValue('G' . $row, $b->items->map(fn($i) => $i->service->name)->join(', '));
            $sheet->setCellValue('H' . $row, $b->cashier->name ?? '-');
            $sheet->setCellValue('I' . $row, $b->total_price);
            $sheet->getStyle('I' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue('J' . $row, $b->status_label);

            $sheet->getStyle('A' . $row . ':J' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $row++;
        }

        // Summary row
        $sheet->setCellValue('A' . $row, 'TOTAL PENDAPATAN (SELESAI)');
        $sheet->mergeCells('A' . $row . ':H' . $row);
        $sheet->setCellValue('I' . $row, $completedBookings->sum('total_price'));
        $sheet->getStyle('I' . $row)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('A' . $row . ':J' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':J' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('F1F5F9');

        foreach ($cols as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    /**
     * Printable view for daily or weekly report.
     */
    public function exportPdf(Request $request)
    {
        $reportType = $request->get('type', $request->get('period', 'daily'));
        $selectedDate = $request->get('date', Carbon::today()->format('Y-m-d'));
        $category = $request->get('category', 'all');

        if (in_array(strtolower($reportType), ['monthly', 'month'])) {
            return response('Unduh laporan bulanan tidak diizinkan untuk kasir. Silakan gunakan menu Order untuk melihat orderan secara bulanan.', 400);
        }

        if ($reportType === 'weekly') {
            $startDate = Carbon::parse($selectedDate)->startOfWeek()->format('Y-m-d');
            $endDate = Carbon::parse($selectedDate)->endOfWeek()->format('Y-m-d');
        } else {
            $startDate = $selectedDate;
            $endDate = $selectedDate;
        }

        $query = Booking::with(['user', 'cashier', 'staff', 'items.service'])
            ->whereDate('booking_date', '>=', $startDate)
            ->whereDate('booking_date', '<=', $endDate)
            ->where('status', 'completed');

        if ($category !== 'all' && in_array($category, ['barbershop', 'mua'])) {
            $query->where('service_category', $category);
        }

        $bookings = $query->orderBy('booking_date', 'asc')->get();
        $totalRevenue = $bookings->sum('total_price');

        return view('admin.reports.print', compact('bookings', 'totalRevenue', 'startDate', 'endDate', 'category'));
    }
}
