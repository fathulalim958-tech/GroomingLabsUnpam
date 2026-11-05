<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    /**
     * Send new booking notification message to Admin WhatsApp via Fonnte API.
     */
    public static function sendNewBookingNotification(Booking $booking): array
    {
        $token = config('services.fonnte.token');
        if (empty($token)) {
            Log::warning('WhatsAppNotificationService: FONNTE_TOKEN is not configured.');
            return ['success' => false, 'message' => 'FONNTE_TOKEN belum dikonfigurasi'];
        }

        $targetPhone = self::getAdminTargetPhone();
        if (empty($targetPhone)) {
            Log::warning('WhatsAppNotificationService: Admin target phone number is missing.');
            return ['success' => false, 'message' => 'Nomor WA Admin tujuan belum diatur'];
        }

        // Format message
        $message = self::buildBookingMessage($booking);

        try {
            $apiUrl = config('services.fonnte.url', 'https://api.fonnte.com/send');

            $response = Http::timeout(12)
                ->withHeaders([
                    'Authorization' => $token,
                ])
                ->post($apiUrl, [
                    'target' => $targetPhone,
                    'message' => $message,
                    'countryCode' => '62',
                ]);

            if ($response->successful()) {
                Log::info('WhatsAppNotificationService: Berhasil mengirim notifikasi order ke ' . $targetPhone . ' (Booking: ' . $booking->booking_code . ')');
                return [
                    'success' => true,
                    'status' => $response->status(),
                    'body' => $response->json() ?? $response->body(),
                ];
            }

            Log::error('WhatsAppNotificationService: Gagal mengirim pesan. Status: ' . $response->status() . ' Body: ' . $response->body());
            return [
                'success' => false,
                'status' => $response->status(),
                'error' => $response->body(),
            ];
        } catch (\Throwable $e) {
            Log::error('WhatsAppNotificationService Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get the designated admin recipient phone number.
     */
    public static function getAdminTargetPhone(): ?string
    {
        $phone = config('services.fonnte.admin_phone');

        // Fallback to database admin user phone if config is empty
        if (empty($phone)) {
            $admin = User::where('role', 'admin')->whereNotNull('phone')->first();
            $phone = $admin?->phone;
        }

        if (empty($phone)) {
            return null;
        }

        // Clean up: only keep numbers
        return preg_replace('/[^0-9]/', '', (string) $phone);
    }

    /**
     * Build structured and aesthetic WhatsApp notification message.
     */
    public static function buildBookingMessage(Booking $booking): string
    {
        $categoryLabel = $booking->service_category === 'mua' ? '💄 MUA Studio' : '💈 Barbershop';

        // Load items & staff if not loaded
        if (!$booking->relationLoaded('items')) {
            $booking->load('items.service');
        }
        if (!$booking->relationLoaded('staff')) {
            $booking->load('staff');
        }

        $serviceNames = $booking->items->map(function ($item) {
            return $item->service?->name ?? 'Layanan';
        })->join(', ');
        if (empty($serviceNames)) {
            $serviceNames = 'Layanan ' . ucfirst($booking->service_category);
        }

        $customerName = $booking->user?->name ?? $booking->customer_name ?? 'Pelanggan';
        $customerPhone = $booking->user?->phone ?? $booking->customer_phone ?? '-';

        $bDate = Carbon::parse($booking->booking_date);
        $dayNames = [
            0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'
        ];
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $formattedDate = ($dayNames[$bDate->dayOfWeek] ?? '') . ', ' . $bDate->format('d') . ' ' . ($monthNames[$bDate->month] ?? '') . ' ' . $bDate->format('Y');

        $staffName = $booking->staff?->name ?? 'Siapa Saja yang Tersedia';

        $totalFormatted = 'Rp ' . number_format($booking->total_price, 0, ',', '.');
        $discountFormatted = $booking->discount_amount > 0 ? 'Rp ' . number_format($booking->discount_amount, 0, ',', '.') : null;

        $msg = "🔔 *PESANAN BARU MASUK!*\n";
        $msg .= "*LPKKS GROOMING LAB*\n";
        $msg .= "────────────────────────\n\n";

        $msg .= "📋 *DETAIL RESERVASI:*\n";
        $msg .= "• *No. Antrian* : *" . $booking->queue_number . "*\n";
        $msg .= "• *Kategori*    : " . $categoryLabel . "\n";
        $msg .= "• *Layanan*     : *" . $serviceNames . "*\n";
        if ($discountFormatted) {
            $msg .= "• *Potongan*    : -" . $discountFormatted . " (VIP Member)\n";
        }
        $msg .= "• *Total Bayar* : *" . $totalFormatted . "*\n\n";

        $msg .= "👤 *DATA PELANGGAN:*\n";
        $msg .= "• *Nama*   : " . $customerName . "\n";
        $msg .= "• *Kontak* : " . $customerPhone . "\n\n";

        $msg .= "📅 *JADWAL & PETUGAS:*\n";
        $msg .= "• *Tanggal* : " . $formattedDate . "\n";
        $msg .= "• *Sesi Jam*: " . substr((string) $booking->booking_time, 0, 5) . " WIB\n";
        $msg .= "• *Petugas* : " . $staffName . "\n";

        if (!empty($booking->notes)) {
            $msg .= "\n📝 *Catatan Khusus:*\n";
            $msg .= '"' . trim($booking->notes) . '"' . "\n";
        }

        $msg .= "\n────────────────────────\n";
        $msg .= "_Notifikasi otomatis sistem reservasi online Grooming Lab._";

        return $msg;
    }
}

