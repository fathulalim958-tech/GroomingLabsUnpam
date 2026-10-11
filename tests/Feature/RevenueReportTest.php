<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RevenueReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_filters_bookings_and_calculates_completed_revenue(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $today = now()->toDateString();
        $included = $this->createBooking([
            'customer_name' => 'Included Customer',
            'service_price' => 100000,
            'appointment_at' => $today.' 11:00:00',
            'status' => 'completed',
        ]);
        $this->createBooking([
            'customer_name' => 'Pending Customer',
            'service_price' => 250000,
            'appointment_at' => $today.' 12:00:00',
            'status' => 'pending',
        ]);
        $this->createBooking([
            'customer_name' => 'Outside Customer',
            'service_price' => 50000,
            'appointment_at' => now()->subDays(40)->toDateTimeString(),
            'status' => 'completed',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.laporan', ['start_date' => $today, 'end_date' => $today]))
            ->assertOk()
            ->assertSee('Included Customer')
            ->assertSee('Pending Customer')
            ->assertDontSee('Outside Customer')
            ->assertSee('Rp 100.000');
    }

    public function test_admin_can_download_filtered_excel_compatible_csv_for_31_days(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $startDate = now()->subDays(30)->toDateString();
        $endDate = now()->toDateString();
        $this->createBooking([
            'customer_name' => 'Included Export Customer',
            'appointment_at' => $startDate.' 10:00:00',
            'status' => 'completed',
        ]);
        $this->createBooking([
            'customer_name' => 'Outside Export Customer',
            'appointment_at' => now()->subDays(40)->toDateTimeString(),
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.laporan.export', ['start_date' => $startDate, 'end_date' => $endDate]));

        $response->assertOk()
            ->assertStreamed()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $csv = $response->streamedContent();
        $this->assertStringContainsString('LAPORAN PENDAPATAN GLOWCUT', $csv);
        $this->assertStringContainsString('RINGKASAN', $csv);
        $this->assertStringContainsString('Harga (Rp)', $csv);
        $this->assertStringContainsString('Included Export Customer', $csv);
        $this->assertStringNotContainsString('Outside Export Customer', $csv);
        $this->assertStringContainsString('TOTAL PENDAPATAN SELESAI', $csv);
    }

    public function test_export_rejects_date_ranges_longer_than_31_days(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('admin.laporan'))
            ->get(route('admin.laporan.export', [
                'start_date' => now()->subDays(31)->toDateString(),
                'end_date' => now()->toDateString(),
            ]))
            ->assertRedirect(route('admin.laporan'))
            ->assertSessionHasErrors('end_date');
    }

    public function test_non_admin_cannot_export_revenue_report(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($kasir)
            ->get(route('admin.laporan.export', [
                'start_date' => now()->toDateString(),
                'end_date' => now()->toDateString(),
            ]))
            ->assertForbidden();
    }

    private function createBooking(array $overrides = []): Booking
    {
        return Booking::create(array_merge([
            'customer_name' => 'Report Customer',
            'category' => 'barber',
            'service_name' => 'Fast Haircut',
            'service_price' => 25000,
            'staff_name' => 'Budi Santoso',
            'appointment_at' => now(),
            'status' => 'pending',
        ], $overrides));
    }
}
