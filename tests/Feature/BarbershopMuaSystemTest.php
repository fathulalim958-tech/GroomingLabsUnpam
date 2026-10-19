<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\OperatingHour;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BarbershopMuaSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed operating hours (Monday - Sunday)
        for ($day = 1; $day <= 6; $day++) {
            OperatingHour::create([
                'day_of_week' => $day,
                'open_time' => '09:00',
                'close_time' => '21:00',
                'is_closed' => false,
                'max_bookings_per_slot' => 5,
            ]);
        }

        OperatingHour::create([
            'day_of_week' => 0,
            'open_time' => '09:00',
            'close_time' => '18:00',
            'is_closed' => true,
            'max_bookings_per_slot' => 5,
        ]);
    }

    /**
     * Test FR-AUTH-01 & FR-AUTH-02: Self-registration as Pelanggan with Phone.
     */
    public function test_customer_can_self_register_with_phone(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Customer',
            'phone' => '081234567899',
            'email' => 'testcustomer@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/pelanggan/home');
        $this->assertAuthenticated();

        $user = User::where('email', 'testcustomer@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('pelanggan', $user->role);
        $this->assertEquals('081234567899', $user->phone);
    }

    /**
     * Test role redirects upon login.
     */
    public function test_role_based_login_redirect(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin/dashboard');
    }

    /**
     * Test FR-CUST-04 & FR-CUST-05: Customer can book and get queue number (e.g. B-001).
     */
    public function test_customer_can_create_booking_and_generate_queue_number(): void
    {
        $customer = User::create([
            'name' => 'Jane Pelanggan',
            'email' => 'jane@test.com',
            'phone' => '081211112222',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);

        $service = Service::create([
            'name' => 'Gentlemen Cut',
            'category' => 'barbershop',
            'price' => 35000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        // Find next valid open date (not Sunday)
        $date = now();
        if ($date->dayOfWeek === 0) {
            $date = $date->addDay();
        }
        $bookingDate = $date->format('Y-m-d');

        $response = $this->actingAs($customer)->post('/pelanggan/booking', [
            'category' => 'barbershop',
            'services' => [$service->id],
            'booking_date' => $bookingDate,
            'booking_time' => '10:00',
            'notes' => 'Tolong potongan fade rapi',
        ]);

        $response->assertRedirect('/pelanggan/order');
        $response->assertSessionHas('success');

        $booking = Booking::where('user_id', $customer->id)->first();
        $this->assertNotNull($booking);
        $this->assertEquals('B-001', $booking->queue_number);
        $this->assertEquals('waiting', $booking->status);
        $this->assertEquals(35000, $booking->total_price);
    }

    /**
     * Test FR-CASH-02 & FR-CASH-03: Cashier Queue control (Process, Skip, Recall, Complete).
     */
    public function test_cashier_queue_lifecycle(): void
    {
        $cashier = User::create([
            'name' => 'Kasir Staf',
            'email' => 'kasirstaf@test.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $customer = User::create([
            'name' => 'Antrian Customer',
            'email' => 'antrian@test.com',
            'phone' => '081233334444',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);

        $booking = Booking::create([
            'booking_code' => 'BK-TEST-0001',
            'user_id' => $customer->id,
            'service_category' => 'barbershop',
            'booking_date' => now()->format('Y-m-d'),
            'booking_time' => '11:00',
            'queue_number' => 'B-001',
            'total_price' => 35000,
            'status' => 'waiting',
        ]);

        // 1. Cashier skips the customer because they are not present
        $this->actingAs($cashier)->post("/kasir/queue/{$booking->id}/skip");
        $booking->refresh();
        $this->assertEquals('skipped', $booking->status);

        // 2. Customer arrives, cashier recalls and processes
        $this->actingAs($cashier)->post("/kasir/queue/{$booking->id}/process");
        $booking->refresh();
        $this->assertEquals('in_progress', $booking->status);

        // 3. Service completed and payment collected
        $this->actingAs($cashier)->post("/kasir/queue/{$booking->id}/complete");
        $booking->refresh();
        $this->assertEquals('completed', $booking->status);
    }

    /**
     * Test FR-CASH-04: Cashier Walk-in booking.
     */
    public function test_cashier_can_create_walkin_order(): void
    {
        $cashier = User::create([
            'name' => 'Kasir Walkin',
            'email' => 'kasirwalkin@test.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $service = Service::create([
            'name' => 'Party Make Up',
            'category' => 'mua',
            'price' => 250000,
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        $response = $this->actingAs($cashier)->post('/kasir/walk-in', [
            'customer_name' => 'Siti Walkin',
            'customer_phone' => '089911223344',
            'category' => 'mua',
            'services' => [$service->id],
            'notes' => 'Walkin makeup',
        ]);

        $response->assertRedirect('/kasir/dashboard');

        $booking = Booking::where('service_category', 'mua')->first();
        $this->assertNotNull($booking);
        $this->assertEquals('M-001', $booking->queue_number);
        $this->assertEquals('waiting', $booking->status);
    }

    /**
     * Test FR-ADM-06: Admin can access reports and export CSV.
     */
    public function test_admin_can_export_reports_csv(): void
    {
        $admin = User::create([
            'name' => 'Admin Report',
            'email' => 'adminreport@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/admin/reports/export-excel');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=utf-8');
    }

    /**
     * Test Category Explanation Page: DOES NOT redirect to login, displays content & services.
     */
    public function test_category_explanation_page_is_accessible_without_login_redirect(): void
    {
        // 1. Unauthenticated guest can access barbershop explanation page without login redirect
        $response = $this->get('/kategori/barbershop');
        $response->assertStatus(200);
        $response->assertSee('Grooming Lab Barbershop');

        // 2. Unauthenticated guest can access mua explanation page without login redirect
        $muaResponse = $this->get('/kategori/mua');
        $muaResponse->assertStatus(200);
        $muaResponse->assertSee('MUA Studio');

        // 3. Logged in customer can access and see booking CTA
        $customer = User::create([
            'name' => 'Budi Customer',
            'email' => 'budicust@test.com',
            'phone' => '081299998888',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);

        $custResponse = $this->actingAs($customer)->get('/kategori/barbershop');
        $custResponse->assertStatus(200);
        $custResponse->assertSee('Booking Sekarang');
    }

    /**
     * Test Admin can manage and update Category Contents.
     */
    public function test_admin_can_update_category_content(): void
    {
        $admin = User::create([
            'name' => 'Admin Content',
            'email' => 'admincontent@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $content = \App\Models\CategoryContent::create([
            'category' => 'barbershop',
            'title' => 'Original Barbershop Title',
            'tagline' => 'Original Tagline',
            'description' => 'Original Description',
            'facilities' => "Facility 1\nFacility 2",
            'terms' => "Term 1\nTerm 2",
            'is_active' => true,
        ]);

        // Admin updates the category content
        $response = $this->actingAs($admin)->put("/admin/category-contents/{$content->id}", [
            'title' => 'Updated Barbershop VIP',
            'tagline' => 'New Premium Experience',
            'description' => 'Updated new description about barbershop services.',
            'facilities' => "VIP Lounge\nEspresso Bar\nFree Wi-Fi",
            'terms' => "Please arrive 15 minutes early.",
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/category-contents');

        $content->refresh();
        $this->assertEquals('Updated Barbershop VIP', $content->title);
        $this->assertEquals('New Premium Experience', $content->tagline);
        $this->assertCount(3, $content->facilities_list);
    }
}

