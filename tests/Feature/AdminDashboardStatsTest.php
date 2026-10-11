<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardStatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_displays_current_database_statistics(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kasir = User::factory()->create(['role' => 'kasir']);
        $employee = Employee::create(['name' => 'Test Hair Stylist', 'position' => 'Hair Stylist']);

        $this->createBooking($employee->name, 100000, now(), 'completed');
        $this->createBooking('Other Staff', 700000, now(), 'pending');
        $this->createBooking($employee->name, 50000, now(), 'completed', $kasir->id);
        $this->createBooking($employee->name, 25000, now()->subDay(), 'completed');
        $this->createBooking($employee->name, 900000, now()->startOfMonth()->subDay(), 'completed');

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Total Pendapatan')
            ->assertSee('Rp 175.000')
            ->assertSee('Booking Hari Ini')
            ->assertSee('3')
            ->assertSee('Walk-in Hari Ini')
            ->assertSee('Test Hair Stylist')
            ->assertSee('4 layanan selesai')
            ->assertSee(now()->locale('id')->translatedFormat('l, d F Y'))
            ->assertDontSee('Rp 18.4jt')
            ->assertDontSee('Ahmad Rizky');
    }

    private function createBooking(string $staff, int $price, \DateTimeInterface $date, string $status, ?int $cashierId = null): Booking
    {
        return Booking::create([
            'customer_name' => 'Dashboard Customer',
            'category' => 'barber',
            'service_name' => 'Fast Haircut',
            'service_price' => $price,
            'staff_name' => $staff,
            'cashier_id' => $cashierId,
            'appointment_at' => $date,
            'status' => $status,
        ]);
    }
}
