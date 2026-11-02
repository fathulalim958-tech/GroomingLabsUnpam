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

class StaffController extends Controller
{
    /**
     * Display the daily history of customers who have been served (status: completed).
     * Filterable per day / specific date.
     */
    public function index(Request $request)
    {
        $selectedDate = $request->get('date', Carbon::today()->format('Y-m-d'));
        $category = $request->get('category', 'all');
        $search = $request->get('search');

        // Query only completed bookings for the selected date
        $query = Booking::with(['user', 'items.service', 'staff', 'cashier'])
            ->whereDate('booking_date', $selectedDate)
            ->where('status', 'completed');

        // Category filter
        if ($category !== 'all' && in_array($category, ['barbershop', 'mua'])) {
            $query->where('service_category', $category);
        }

        // Search filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('queue_number', 'like', "%{$search}%");
            });
        }

        // Sorted by time of completion / booking time desc
        $bookings = $query->orderBy('booking_time', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Calculate daily summary metrics for the selected date
        $allDailyCompleted = Booking::whereDate('booking_date', $selectedDate)
            ->where('status', 'completed')
            ->get();

        $stats = [
            'total_served'       => $allDailyCompleted->count(),
            'barbershop_served'  => $allDailyCompleted->where('service_category', 'barbershop')->count(),
            'mua_served'         => $allDailyCompleted->where('service_category', 'mua')->count(),
            'total_revenue'      => $allDailyCompleted->sum('total_price'),
        ];

        return view('kasir.staffs.index', compact(
            'bookings',
            'selectedDate',
            'category',
            'search',
            'stats'
        ));
    }

    /**
     * Export the daily history of served customers to Excel (.xlsx)
     * Format matches the Admin Excel report format, restricted strictly to per-day data.
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        // Strictly per day
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $category = $request->get('category', 'all');

        $query = Booking::with(['user', 'cashier', 'staff', 'items.service'])
            ->whereDate('booking_date', $date)
            ->where('status', 'completed');

        if ($category !== 'all' && in_array($category, ['barbershop', 'mua'])) {
            $query->where('service_category', $category);
        }

        $bookings = $query->orderBy('booking_time', 'asc')->get();

        // Summary metrics
        $totalCompletedOrders = $bookings->count();
        $barbershopRevenue = $bookings->where('service_category', 'barbershop')->sum('total_price');
        $muaRevenue = $bookings->where('service_category', 'mua')->sum('total_price');
        $totalRevenue = $bookings->sum('total_price');

        // Create Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Riwayat Pelanggan');

        $spreadsheet->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);

        // 1. Title Block
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'RIWAYAT PELAYANAN PELANGGAN HARIAN');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // 2. Period (Single Day)
        $dateFormatted = Carbon::parse($date)->translatedFormat('l, d F Y');
        $sheet->mergeCells('A2:J2');
        $sheet->setCellValue('A2', "Tanggal Pelayanan: {$dateFormatted}");
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // 3. Ringkasan
        $sheet->setCellValue('A4', 'RINGKASAN HARIAN');
        $sheet->getStyle('A4')->getFont()->setBold(true);

        $sheet->setCellValue('A5', 'Jumlah Pelanggan Dilayani');
        $sheet->setCellValue('B5', $totalCompletedOrders);
        $sheet->getStyle('B5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->setCellValue('A6', 'Total Barbershop');
        $sheet->setCellValue('B6', $bookings->where('service_category', 'barbershop')->count());
        $sheet->getStyle('B6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->setCellValue('A7', 'Total MUA Studio');
        $sheet->setCellValue('B7', $bookings->where('service_category', 'mua')->count());
        $sheet->getStyle('B7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->setCellValue('A8', 'Pendapatan Barbershop');
        $sheet->setCellValue('B8', 'Rp' . number_format($barbershopRevenue, 0, ',', '.'));
        $sheet->getStyle('B8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->setCellValue('A9', 'Pendapatan MUA');
        $sheet->setCellValue('B9', 'Rp' . number_format($muaRevenue, 0, ',', '.'));
        $sheet->getStyle('B9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->setCellValue('A10', 'Total Pendapatan Harian');
        $sheet->setCellValue('B10', 'Rp' . number_format($totalRevenue, 0, ',', '.'));
        $sheet->getStyle('B10')->getFont()->setBold(true);
        $sheet->getStyle('B10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // 4. Table Header (Row 12)
        $headers = [
            'A12' => 'No.',
            'B12' => 'Tanggal',
            'C12' => 'Waktu',
            'D12' => 'Jenis',
            'E12' => 'Pelanggan',
            'F12' => 'Kategori',
            'G12' => 'Layanan',
            'H12' => 'Kasir / Staff',
            'I12' => 'Harga',
            'J12' => 'Status',
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
        $sheet->getStyle('A12:J12')->applyFromArray($headerStyle);
        $sheet->getRowDimension(12)->setRowHeight(24);

        // 5. Data Rows
        $currentRow = 13;
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
            $jenis = $booking->is_walk_in ? 'Offline' : 'Online';
            $pelanggan = $booking->customer_name;
            $kategori = $booking->service_category === 'mua' ? 'MUA' : 'Barbershop';
            $layanan = $booking->items->map(fn($item) => $item->service->name ?? '')->filter()->join(', ');
            if (empty($layanan)) {
                $layanan = '-';
            }
            $staff = $booking->staff->name ?? $booking->cashier->name ?? 'Kasir';
            $harga = 'Rp' . number_format($booking->total_price, 0, ',', '.');
            $status = 'Completed';

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
            $sheet->getStyle("F{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("I{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("J{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle("A{$currentRow}:J{$currentRow}")->applyFromArray($cellBorderStyle);
            $sheet->getRowDimension($currentRow)->setRowHeight(20);

            $currentRow++;
        }

        // Auto-fit column widths
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Riwayat_Pelanggan_Dilayani_' . Carbon::parse($date)->format('Y-m-d') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
