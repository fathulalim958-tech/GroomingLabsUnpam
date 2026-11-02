<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Display cashier account profile & employee personal data (Murni Menu Profile Karyawan Tanpa Laporan Transaksi).
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        return view('kasir.reports.index', compact('user'));
    }

    /**
     * Update cashier personal data (nama, no. WhatsApp/HP, dan foto profil).
     * Akses ganti password ditiadakan untuk akun kasir.
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+]+$/'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'phone.regex' => 'Nomor WhatsApp / telepon hanya boleh berisi angka.',
            'avatar.image' => 'File foto profil harus berupa gambar.',
            'avatar.mimes' => 'Format foto harus berupa JPG, JPEG, PNG, atau WEBP.',
            'avatar.max' => 'Ukuran foto profil maksimal 2 MB.',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        } elseif ($request->boolean('delete_avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = null;
        }

        $user->name = $validated['name'];
        $user->phone = $validated['phone'];
        $user->save();

        return redirect()->route('kasir.reports.index')->with('success', 'Data diri dan foto profil kasir berhasil diperbarui.');
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
            $sheet->setCellValue('H' . $row, $b->staff->name ?? $b->cashier->name ?? '-');
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
