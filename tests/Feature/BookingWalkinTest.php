<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Database\Seeders\EmployeeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingWalkinTest extends TestCase
{
    use RefreshDatabase;

    public function test_walkin_form_submits_category_and_service_fields(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $this->seed(EmployeeSeeder::class);

        $this->actingAs($kasir)
            ->get(route('kasir.booking.walkin'))
            ->assertOk()
            ->assertSee('name="category"', false)
            ->assertSee('name="service_name"', false)
            ->assertSee('name="customer_name"', false)
            ->assertSee('name="staff_name"', false)
            ->assertSee('HAIR STYLIST / MUA ARTIST')
            ->assertSee('Budi Santoso')
            ->assertSee('Rina Andini')
            ->assertSee('Hair Stylist')
            ->assertSee('MUA Artist')
            ->assertSee('updateWalkInStaff', false);
    }

    public function test_kasir_can_store_a_walkin_booking_with_server_price(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $this->seed(EmployeeSeeder::class);

        $this->actingAs($kasir)
            ->post(route('kasir.booking.walkin.store'), [
                'customer_name' => 'Pelanggan Walk-in',
                'category' => 'barber',
                'service_name' => 'Fast Haircut',
                'staff_name' => 'Budi Santoso',
                'service_price' => 1,
            ])
            ->assertRedirect(route('kasir.booking.walkin'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('bookings', [
            'customer_name' => 'Pelanggan Walk-in',
            'category' => 'barber',
            'service_name' => 'Fast Haircut',
            'service_price' => 25000,
            'staff_name' => 'Budi Santoso',
            'cashier_id' => $kasir->id,
            'status' => 'pending',
        ]);

        $this->actingAs($kasir)
            ->get(route('kasir.dashboard'))
            ->assertOk()
            ->assertSee('Pelanggan Walk-in')
            ->assertSee('Fast Haircut')
            ->assertSee('Rp 25.000');
    }

    public function test_kasir_cannot_store_a_service_from_another_category(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $this->seed(EmployeeSeeder::class);

        $this->actingAs($kasir)
            ->from(route('kasir.booking.walkin'))
            ->post(route('kasir.booking.walkin.store'), [
                'customer_name' => 'Pelanggan Walk-in',
                'category' => 'barber',
                'service_name' => 'Makeup Only',
                'staff_name' => 'Budi',
            ])
            ->assertSessionHasErrors('service_name');

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_kasir_cannot_assign_a_staff_member_from_another_category(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $this->seed(EmployeeSeeder::class);

        $this->actingAs($kasir)
            ->from(route('kasir.booking.walkin'))
            ->post(route('kasir.booking.walkin.store'), [
                'customer_name' => 'Pelanggan Walk-in',
                'category' => 'barber',
                'service_name' => 'Fast Haircut',
                'staff_name' => 'Rina Andini',
            ])
            ->assertSessionHasErrors('staff_name');

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_non_kasir_cannot_store_a_walkin_booking(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('kasir.booking.walkin.store'), [
                'customer_name' => 'Pelanggan Walk-in',
                'category' => 'barber',
                'service_name' => 'Fast Haircut',
                'staff_name' => 'Budi',
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_kasir_can_complete_online_and_walkin_bookings(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $bookings = collect([
            Booking::create([
                'customer_name' => 'Walk-in Customer',
                'category' => 'barber',
                'service_name' => 'Fast Haircut',
                'service_price' => 25000,
                'staff_name' => 'Budi',
                'cashier_id' => $kasir->id,
                'appointment_at' => now(),
                'status' => 'pending',
            ]),
            Booking::create([
                'customer_name' => 'Online Customer',
                'category' => 'mua',
                'service_name' => 'Makeup Only',
                'service_price' => 250000,
                'staff_name' => 'Rina',
                'appointment_at' => now(),
                'status' => 'pending',
            ]),
        ]);

        $this->actingAs($kasir);

        foreach ($bookings as $booking) {
            $this->patch(route('kasir.booking.complete', $booking))
                ->assertRedirect(route('kasir.dashboard'))
                ->assertSessionHas('success');

            $this->assertDatabaseHas('bookings', [
                'id' => $booking->id,
                'status' => 'completed',
            ]);
        }
    }

    public function test_dashboard_displays_only_persisted_bookings_as_clickable_cards(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $booking = Booking::create([
            'customer_name' => 'Pelanggan Tersimpan',
            'category' => 'barber',
            'service_name' => 'Fast Haircut',
            'service_price' => 25000,
            'staff_name' => 'Budi',
            'cashier_id' => $kasir->id,
            'appointment_at' => now(),
            'status' => 'pending',
        ]);

        $this->actingAs($kasir)
            ->get(route('kasir.dashboard'))
            ->assertOk()
            ->assertSee('Pelanggan Tersimpan')
            ->assertSee(route('kasir.booking.complete', $booking), false)
            ->assertSee('bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', false)
            ->assertDontSee('Ahmad Rizky')
            ->assertDontSee('Kevin Pratama')
            ->assertDontSee('Bagas W.');
    }

    public function test_non_kasir_cannot_complete_a_booking(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $booking = Booking::create([
            'customer_name' => 'Pelanggan Tersimpan',
            'category' => 'barber',
            'service_name' => 'Fast Haircut',
            'service_price' => 25000,
            'appointment_at' => now(),
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->patch(route('kasir.booking.complete', $booking))
            ->assertForbidden();

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'pending',
        ]);
    }
}
