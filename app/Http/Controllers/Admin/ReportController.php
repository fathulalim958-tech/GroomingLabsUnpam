<?php

namespace App\Http\Controllers\Admin;

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
     * Display reports dashboard with filtering.
     */
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::today()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::today()->format('Y-m-d'));
        $category = $request->get('category', 'all');

        $query = Booking::with(['user', 'cashier', 'staff', 'items.service'])
            ->whereDate('booking_date', '>=', $startDate)
            ->whereDate('booking_date', '<=', $endDate);

        if ($category !== 'all' && in_array($category, ['barbershop', 'mua'])) {
            $query->where('service_category', $category);
        }

        $allBookings = (clone $query)->get();
        $completedBookings = $allBookings->where('status', 'completed');

        // Calculate summary
        $summary = [
            'total_all_orders'       => $allBookings->count(),
            'total_completed_orders' => $completedBookings->count(),
            'total_revenue'          => $completedBookings->sum('total_price'),
            'barbershop_revenue'     => $completedBookings->where('service_category', 'barbershop')->sum('total_price'),
            'mua_revenue'            => $completedBookings->where('service_category', 'mua')->sum('total_price'),
            'barbershop_orders'      => $completedBookings->where('service_category', 'barbershop')->count(),
            'mua_orders'             => $completedBookings->where('service_category', 'mua')->count(),
        ];

        $bookings = (clone $query)->orderBy('booking_date', 'desc')->paginate(15)->withQueryString();

        return view('admin.reports.index', compact('bookings', 'summary', 'startDate', 'endDate', 'category'));
    }

    /**
     * Export reports as Excel format (.xlsx) or CSV format.
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::today()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::today()->format('Y-m-d'));
        $category = $request->get('category', 'all');

        $query = Booking::with(['user', 'cashier', 'staff', 'items.service'])
            ->whereDate('booking_date', '>=', $startDate)
            ->whereDate('booking_date', '<=', $endDate);

        if ($category !== 'all' && in_array($category, ['barbershop', 'mua'])) {
            $query->where('service_category', $category);
        }

        $bookings = $query->orderBy('booking_date', 'asc')->orderBy('booking_time', 'asc')->get();

        // Support explicit CSV format request
        if ($request->get('format') === 'csv') {
            return $this->exportCsv($bookings, $startDate, $endDate);
        }

        // Summary metrics
        $totalAllOrders = $bookings->count();
        $completedBookings = $bookings->where('status', 'completed');
        $totalCompletedOrders = $completedBookings->count();
        $barbershopRevenue = $completedBookings->where('service_category', 'barbershop')->sum('total_price');
        $muaRevenue = $completedBookings->where('service_category', 'mua')->sum('total_price');
        $totalRevenue = $completedBookings->sum('total_price');

        // Create Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Worksheet');

        $spreadsheet->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);

        // 1. Title Block
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'LAPORAN PENDAPATAN');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // 2. Period
        $startFormatted = Carbon::parse($startDate)->format('d/m/Y');
        $endFormatted = Carbon::parse($endDate)->format('d/m/Y');
        $sheet->mergeCells('A2:J2');
        $sheet->setCellValue('A2', "Periode: {$startFormatted} - {$endFormatted}");
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // 3. Ringkasan
        $sheet->setCellValue('A4', 'RINGKASAN');
        $sheet->getStyle('A4')->getFont()->setBold(true);

        $sheet->setCellValue('A5', 'Jumlah Transaksi (Semua)');
        $sheet->setCellValue('B5', $totalAllOrders);
        $sheet->getStyle('B5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->setCellValue('A6', 'Jumlah Transaksi (Selesai)');
        $sheet->setCellValue('B6', $totalCompletedOrders);
        $sheet->getStyle('B6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->setCellValue('A7', 'Pendapatan Barbershop');
        $sheet->setCellValue('B7', 'Rp' . number_format($barbershopRevenue, 0, ',', '.'));
        $sheet->getStyle('B7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->setCellValue('A8', 'Pendapatan MUA');
        $sheet->setCellValue('B8', 'Rp' . number_format($muaRevenue, 0, ',', '.'));
        $sheet->getStyle('B8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->setCellValue('A9', 'Total Pendapatan');
        $sheet->setCellValue('B9', 'Rp' . number_format($totalRevenue, 0, ',', '.'));
        $sheet->getStyle('B9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // 4. Table Header (Row 11)
        $headers = [
            'A11' => 'No.',
            'B11' => 'Tanggal',
            'C11' => 'Waktu',
            'D11' => 'Jenis',
            'E11' => 'Pelanggan',
            'F11' => 'Kategori',
            'G11' => 'Layanan',
            'H11' => 'Staff',
            'I11' => 'Harga',
            'J11' => 'Status',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'E5E7EB',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '9CA3AF'],
                ],
            ],
        ];
        $sheet->getStyle('A11:J11')->applyFromArray($headerStyle);
        $sheet->getRowDimension(11)->setRowHeight(24);

        // 5. Data Rows
        $currentRow = 12;
        $cellBorderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CBD5E1'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        foreach ($bookings as $index => $booking) {
            $tanggal = $booking->booking_date ? Carbon::parse($booking->booking_date)->format('d/m/Y') : '-';
            $waktu = $booking->booking_time ? substr($booking->booking_time, 0, 5) : '-';
            $jenis = ($booking->cashier_id || ($booking->notes && str_contains(strtolower($booking->notes), 'walk-in'))) ? 'Offline' : 'Online';
            $pelanggan = $booking->customer_name;
            $kategori = $booking->service_category === 'mua' ? 'MUA' : 'Barbershop';
            $layanan = $booking->items->map(fn($item) => $item->service->name ?? '')->filter()->join(', ');
            if (empty($layanan)) {
                $layanan = '-';
            }
            $staff = $booking->staff->name ?? '-';
            $harga = 'Rp' . number_format($booking->total_price, 0, ',', '.');
            
            // Format status: completed -> Completed, cancelled -> Cancelled, etc.
            $status = match (strtolower($booking->status)) {
                'completed'   => 'Completed',
                'cancelled'   => 'Cancelled',
                'waiting'     => 'Waiting',
                'in_progress' => 'In Progress',
                'skipped'     => 'Skipped',
                default       => ucfirst($booking->status),
            };

            $sheet->setCellValue("A{$currentRow}", $index + 1);
            $sheet->setCellValue("B{$currentRow}", $tanggal);
            $sheet->setCellValue("C{$currentRow}", $waktu);
            $sheet->setCellValue("D{$currentRow}", $jenis);
            $sheet->setCellValue("E{$currentRow}", $pelanggan);
            $sheet->setCellValue("F{$currentRow}", $kategori);
            $sheet->setCellValue("G{$currentRow}", $layanan);
            $sheet->setCellValue("H{$currentRow}", $staff);
            $sheet->setCellValue("I{$currentRow}", $harga);
            $sheet->setCellValue("J{$currentRow}", $status);

            // Alignment
            $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("F{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("G{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("H{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("I{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("J{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle("A{$currentRow}:J{$currentRow}")->applyFromArray($cellBorderStyle);
            $sheet->getRowDimension($currentRow)->setRowHeight(20);

            $currentRow++;
        }

        // 6. Total Row (Row following last data row)
        $totalRow = $currentRow;
        $sheet->mergeCells("A{$totalRow}:H{$totalRow}");
        $sheet->setCellValue("A{$totalRow}", 'TOTAL PENDAPATAN SELESAI');
        $sheet->setCellValue("I{$totalRow}", 'Rp' . number_format($totalRevenue, 0, ',', '.'));
        $sheet->setCellValue("J{$totalRow}", '');

        $totalRowStyle = [
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CBD5E1'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle("A{$totalRow}:J{$totalRow}")->applyFromArray($totalRowStyle);
        $sheet->getStyle("A{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("I{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getRowDimension($totalRow)->setRowHeight(22);

        // Column widths
        $columns = [
            'A' => 6,
            'B' => 14,
            'C' => 10,
            'D' => 12,
            'E' => 18,
            'F' => 16,
            'G' => 26,
            'H' => 16,
            'I' => 16,
            'J' => 14,
        ];
        foreach ($columns as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        $filename = "laporan-pendapatan_{$startDate}_{$endDate}.xlsx";

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Fallback for CSV format.
     */
    private function exportCsv($bookings, string $startDate, string $endDate): StreamedResponse
    {
        $filename = "laporan-transaksi-{$startDate}-sampai-{$endDate}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($bookings) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'No.',
                'Tanggal',
                'Waktu',
                'Jenis',
                'Pelanggan',
                'Kategori',
                'Layanan',
                'Staff',
                'Kasir',
                'Harga',
                'Status'
            ]);

            foreach ($bookings as $index => $booking) {
                fputcsv($file, [
                    $index + 1,
                    $booking->booking_date ? Carbon::parse($booking->booking_date)->format('d/m/Y') : '-',
                    $booking->booking_time ? substr($booking->booking_time, 0, 5) : '-',
                    ($booking->cashier_id || ($booking->notes && str_contains(strtolower($booking->notes), 'walk-in'))) ? 'Offline' : 'Online',
                    $booking->customer_name,
                    $booking->service_category === 'mua' ? 'MUA' : 'Barbershop',
                    $booking->items->map(fn($item) => $item->service->name ?? '')->filter()->join(', '),
                    $booking->staff->name ?? '-',
                    $booking->cashier->name ?? '-',
                    'Rp' . number_format($booking->total_price, 0, ',', '.'),
                    ucfirst($booking->status),
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

        $query = Booking::with(['user', 'cashier', 'staff', 'items.service'])
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
