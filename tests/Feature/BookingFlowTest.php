<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    public function test_customer_can_register_and_reach_dashboard(): void
    {
        $response = $this->post('/register', [
            'name' => 'Customer Test', 'email' => 'customer@test.test',
            'password' => 'password', 'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs(User::where('email', 'customer@test.test')->first());
    }

    public function test_customer_can_create_booking_but_not_change_status(): void
    {
        $customer = User::factory()->create();
        $service = Service::factory()->create();
        $this->actingAs($customer)->post('/bookings', [
            'service_id' => $service->id, 'appointment_at' => now()->addDay()->format('Y-m-d H:i'),
        ])->assertRedirect('/dashboard');
        $this->assertDatabaseHas('bookings', ['user_id' => $customer->id]);
        $this->actingAs($customer)->patch('/bookings/1/status', ['status' => 'confirmed'])->assertForbidden();
    }

    public function test_cashier_can_change_booking_status(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $booking = Booking::factory()->create();

        $this->actingAs($cashier)->patch("/bookings/{$booking->id}/status", ['status' => 'confirmed'])
            ->assertRedirect('/dashboard');
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'confirmed']);
    }
}
