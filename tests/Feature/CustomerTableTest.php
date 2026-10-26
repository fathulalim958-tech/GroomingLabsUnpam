<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTableTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_record_is_automatically_created_when_pelanggan_user_is_registered(): void
    {
        $user = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone' => '081234567899',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);

        $this->assertDatabaseHas('customers', [
            'user_id' => $user->id,
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone' => '081234567899',
        ]);

        $this->assertNotNull($user->customer);
        $this->assertEquals('Budi Santoso', $user->customer->name);
        $this->assertEquals($user->id, $user->customer->user->id);
    }

    public function test_customer_record_updates_when_user_is_updated(): void
    {
        $user = User::create([
            'name' => 'Siti Rahma',
            'email' => 'siti@example.com',
            'phone' => '081298765432',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);

        $user->update([
            'is_member' => true,
            'member_discount' => 15,
            'member_discount_type' => 'percent',
            'member_discount_quota' => 5,
            'member_discount_remaining' => 5,
        ]);

        $customer = Customer::where('user_id', $user->id)->first();
        $this->assertNotNull($customer);
        $this->assertTrue($customer->is_member);
        $this->assertEquals(15, $customer->member_discount);
        $this->assertEquals(5, $customer->member_discount_quota);
    }

    public function test_booking_automatically_links_to_customer_id(): void
    {
        $user = User::create([
            'name' => 'Doni Customer',
            'email' => 'doni@example.com',
            'phone' => '081333444555',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);

        $booking = Booking::create([
            'booking_code' => 'BK-TEST-001',
            'user_id' => $user->id,
            'service_category' => 'barbershop',
            'booking_date' => now()->toDateString(),
            'booking_time' => '10:00:00',
            'queue_number' => 'B-001',
            'total_price' => 35000,
            'status' => 'waiting',
        ]);

        $this->assertNotNull($booking->customer_id);
        $this->assertEquals($user->customer->id, $booking->customer_id);
        $this->assertEquals('Doni Customer', $booking->customer->name);
    }

    public function test_walk_in_booking_links_to_customer_table(): void
    {
        $booking = Booking::create([
            'booking_code' => 'BK-WALKIN-001',
            'user_id' => null,
            'customer_name' => 'Walkin Guest',
            'customer_phone' => '08999888777',
            'service_category' => 'barbershop',
            'booking_date' => now()->toDateString(),
            'booking_time' => '11:00:00',
            'queue_number' => 'B-002',
            'total_price' => 25000,
            'status' => 'waiting',
        ]);

        $this->assertNotNull($booking->customer_id);
        $this->assertDatabaseHas('customers', [
            'id' => $booking->customer_id,
            'name' => 'Walkin Guest',
            'phone' => '08999888777',
            'user_id' => null,
        ]);
    }
}
