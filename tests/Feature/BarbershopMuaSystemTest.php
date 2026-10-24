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

        // Seed operating hours for barbershop and mua
        foreach (['barbershop', 'mua'] as $cat) {
            for ($day = 1; $day <= 6; $day++) {
                OperatingHour::create([
                    'category' => $cat,
                    'day_of_week' => $day,
                    'open_time' => '09:00',
                    'close_time' => '21:00',
                    'is_closed' => false,
                    'max_bookings_per_slot' => $cat === 'barbershop' ? 5 : 2,
                ]);
            }

            OperatingHour::create([
                'category' => $cat,
                'day_of_week' => 0,
                'open_time' => '09:00',
                'close_time' => '18:00',
                'is_closed' => true,
                'max_bookings_per_slot' => $cat === 'barbershop' ? 5 : 2,
            ]);
        }
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
        $this->assertNull($booking->user_id);
        $this->assertEquals('Siti Walkin', $booking->customer_name);
        $this->assertEquals('089911223344', $booking->customer_phone);
        $this->assertEquals(\Carbon\Carbon::now('Asia/Jakarta')->toDateString(), $booking->booking_date->toDateString());
        $this->assertNotEmpty($booking->booking_time);
        $this->assertMatchesRegularExpression('/^\d{2}:\d{2}(:\d{2})?$/', $booking->booking_time);
        $this->assertMatchesRegularExpression('/^\d{2}:\d{2}$/', $booking->formatted_booking_time);

        // Verify walk-in did NOT create a user account in users table
        $this->assertEquals(0, User::where('email', 'like', '%@walkin.local')->count());
        $this->assertEquals(0, User::where('name', 'Siti Walkin')->count());

        // Verify that Admin Customer management only lists registered users with accounts
        $admin = User::create([
            'name' => 'Admin Test Walkin',
            'email' => 'admintestwalkin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $adminRes = $this->actingAs($admin)->get('/admin/customers');
        $adminRes->assertStatus(200);
        $adminRes->assertDontSee('Siti Walkin');
    }

    /**
     * Test FR-ADM-06: Admin can access reports and export Excel and CSV.
     */
    public function test_admin_can_export_reports_csv(): void
    {
        $admin = User::create([
            'name' => 'Admin Report',
            'email' => 'adminreport@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Excel export (.xlsx)
        $response = $this->actingAs($admin)->get('/admin/reports/export-excel');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // CSV export
        $responseCsv = $this->actingAs($admin)->get('/admin/reports/export-excel?format=csv');
        $responseCsv->assertStatus(200);
        $responseCsv->assertHeader('content-type', 'text/csv; charset=utf-8');
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

    /**
     * Test operating hours can be managed separately for barbershop and MUA.
     */
    public function test_operating_hours_can_be_managed_separately_for_barbershop_and_mua(): void
    {
        $admin = User::create([
            'name' => 'Admin Operating',
            'email' => 'adminoperating@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // 1. Visit barbershop tab
        $resBarber = $this->actingAs($admin)->get('/admin/operating-hours?category=barbershop');
        $resBarber->assertStatus(200);
        $resBarber->assertSee('Layanan Barbershop');

        // 2. Visit MUA tab
        $resMua = $this->actingAs($admin)->get('/admin/operating-hours?category=mua');
        $resMua->assertStatus(200);
        $resMua->assertSee('Layanan MUA Studio');

        // 3. Update MUA Monday quota to 1
        $muaMonday = OperatingHour::where('category', 'mua')->where('day_of_week', 1)->first();
        $barberMonday = OperatingHour::where('category', 'barbershop')->where('day_of_week', 1)->first();

        $response = $this->actingAs($admin)->put('/admin/operating-hours', [
            'category' => 'mua',
            'hours' => [
                [
                    'id' => $muaMonday->id,
                    'open_time' => '10:00',
                    'close_time' => '20:00',
                    'max_bookings_per_slot' => 1,
                ]
            ],
        ]);
        $response->assertRedirect('/admin/operating-hours?category=mua');

        $muaMonday->refresh();
        $barberMonday->refresh();

        $this->assertEquals(1, $muaMonday->max_bookings_per_slot);
        $this->assertEquals('10:00', substr($muaMonday->open_time, 0, 5));
        // Verify barbershop quota was not changed
        $this->assertEquals(5, $barberMonday->max_bookings_per_slot);
    }

    /**
     * Test booking quota is evaluated per category.
     */
    public function test_booking_quota_is_evaluated_per_service_category(): void
    {
        $customer = User::create([
            'name' => 'Customer Quota',
            'email' => 'custquota@test.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);

        $serviceMua = Service::create([
            'name' => 'Bridal Makeup',
            'category' => 'mua',
            'price' => 500000,
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        // Set MUA quota for tomorrow to 1 and ensure barbershop is open
        $tomorrow = now()->addDay();
        $dayOfWeek = $tomorrow->dayOfWeek;
        $muaHour = OperatingHour::where('category', 'mua')->where('day_of_week', $dayOfWeek)->first();
        $muaHour->update(['max_bookings_per_slot' => 1, 'is_closed' => false]);
        OperatingHour::where('category', 'barbershop')->where('day_of_week', $dayOfWeek)->update(['is_closed' => false, 'max_bookings_per_slot' => 5]);

        // First MUA booking
        $res1 = $this->actingAs($customer)->post('/pelanggan/booking', [
            'category' => 'mua',
            'services' => [$serviceMua->id],
            'booking_date' => $tomorrow->format('Y-m-d'),
            'booking_time' => '10:00',
        ]);

        $res1->assertRedirect();
        $this->assertEquals(1, Booking::where('service_category', 'mua')->count());

        // Another customer tries to book same slot for MUA -> should be rejected because MUA quota = 1
        $customer2 = User::create([
            'name' => 'Customer 2',
            'email' => 'cust2@test.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);

        $res2 = $this->actingAs($customer2)->post('/pelanggan/booking', [
            'category' => 'mua',
            'services' => [$serviceMua->id],
            'booking_date' => $tomorrow->format('Y-m-d'),
            'booking_time' => '10:00',
        ]);

        $res2->assertSessionHasErrors('booking_time');

        // But customer 2 CAN book barbershop at the same date & time because Barbershop has separate quota!
        $serviceBarber = Service::create([
            'name' => 'Classic Cut',
            'category' => 'barbershop',
            'price' => 35000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        $res3 = $this->actingAs($customer2)->post('/pelanggan/booking', [
            'category' => 'barbershop',
            'services' => [$serviceBarber->id],
            'booking_date' => $tomorrow->format('Y-m-d'),
            'booking_time' => '10:00',
        ]);

        $res3->assertRedirect();
        $this->assertEquals(1, Booking::where('service_category', 'barbershop')->count());
    }

    /**
     * Test Excel export has exact required structure matching user's layout.
     */
    public function test_excel_export_has_exact_required_layout(): void
    {
        $admin = User::create([
            'name' => 'Admin Excel',
            'email' => 'adminexcel@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $customer = User::create([
            'name' => 'adam',
            'email' => 'adam@test.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);

        $service1 = Service::create([
            'name' => 'Fast Haircut',
            'category' => 'barbershop',
            'price' => 25000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        $service2 = Service::create([
            'name' => 'Makeup Only',
            'category' => 'mua',
            'price' => 250000,
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        $staff = \App\Models\Staff::create([
            'name' => 'adam',
            'role' => 'kapster',
            'phone' => '0812345678',
            'is_active' => true,
        ]);

        // Booking 1: Completed Barbershop
        $b1 = Booking::create([
            'booking_code' => 'BK-001',
            'user_id' => $customer->id,
            'staff_id' => $staff->id,
            'service_category' => 'barbershop',
            'booking_date' => '2026-10-08',
            'booking_time' => '13:00',
            'queue_number' => 'B-001',
            'total_price' => 25000,
            'status' => 'completed',
        ]);
        \App\Models\BookingItem::create([
            'booking_id' => $b1->id,
            'service_id' => $service1->id,
            'price' => 25000,
        ]);

        // Booking 2: Cancelled MUA
        $b2 = Booking::create([
            'booking_code' => 'BK-002',
            'user_id' => $customer->id,
            'service_category' => 'mua',
            'booking_date' => '2026-10-08',
            'booking_time' => '13:00',
            'queue_number' => 'M-001',
            'total_price' => 250000,
            'status' => 'cancelled',
        ]);
        \App\Models\BookingItem::create([
            'booking_id' => $b2->id,
            'service_id' => $service2->id,
            'price' => 250000,
        ]);

        $response = $this->actingAs($admin)->get('/admin/reports/export-excel?start_date=2026-10-08&end_date=2026-10-08');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // Read the streamed response content with PhpSpreadsheet
        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $tempFile = tempnam(sys_get_temp_dir(), 'test_excel_');
        file_put_contents($tempFile, $content);

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($tempFile);
        $sheet = $spreadsheet->getActiveSheet();

        $this->assertEquals('Worksheet', $sheet->getTitle());
        $this->assertEquals('LAPORAN PENDAPATAN', $sheet->getCell('A1')->getValue());
        $this->assertEquals('Periode: 08/10/2026 - 08/10/2026', $sheet->getCell('A2')->getValue());
        $this->assertEquals('RINGKASAN', $sheet->getCell('A4')->getValue());
        $this->assertEquals('Jumlah Transaksi (Semua)', $sheet->getCell('A5')->getValue());
        $this->assertEquals(2, $sheet->getCell('B5')->getValue());
        $this->assertEquals('Jumlah Transaksi (Selesai)', $sheet->getCell('A6')->getValue());
        $this->assertEquals(1, $sheet->getCell('B6')->getValue());
        $this->assertEquals('Pendapatan Barbershop', $sheet->getCell('A7')->getValue());
        $this->assertEquals('Rp25.000', $sheet->getCell('B7')->getValue());
        $this->assertEquals('Pendapatan MUA', $sheet->getCell('A8')->getValue());
        $this->assertEquals('Rp0', $sheet->getCell('B8')->getValue());
        $this->assertEquals('Total Pendapatan', $sheet->getCell('A9')->getValue());
        $this->assertEquals('Rp25.000', $sheet->getCell('B9')->getValue());

        // Header
        $this->assertEquals('No.', $sheet->getCell('A11')->getValue());
        $this->assertEquals('Tanggal', $sheet->getCell('B11')->getValue());
        $this->assertEquals('Waktu', $sheet->getCell('C11')->getValue());
        $this->assertEquals('Jenis', $sheet->getCell('D11')->getValue());
        $this->assertEquals('Pelanggan', $sheet->getCell('E11')->getValue());
        $this->assertEquals('Kategori', $sheet->getCell('F11')->getValue());
        $this->assertEquals('Layanan', $sheet->getCell('G11')->getValue());
        $this->assertEquals('Staff', $sheet->getCell('H11')->getValue());
        $this->assertEquals('Harga', $sheet->getCell('I11')->getValue());
        $this->assertEquals('Status', $sheet->getCell('J11')->getValue());

        // Row 12 (Booking 1: Barbershop Completed)
        $this->assertEquals(1, $sheet->getCell('A12')->getValue());
        $this->assertEquals('08/10/2026', $sheet->getCell('B12')->getValue());
        $this->assertEquals('13:00', $sheet->getCell('C12')->getValue());
        $this->assertEquals('Online', $sheet->getCell('D12')->getValue());
        $this->assertEquals('adam', $sheet->getCell('E12')->getValue());
        $this->assertEquals('Barbershop', $sheet->getCell('F12')->getValue());
        $this->assertEquals('Fast Haircut', $sheet->getCell('G12')->getValue());
        $this->assertEquals('adam', $sheet->getCell('H12')->getValue());
        $this->assertEquals('Rp25.000', $sheet->getCell('I12')->getValue());
        $this->assertEquals('Completed', $sheet->getCell('J12')->getValue());

        // Row 13 (Booking 2: MUA Cancelled)
        $this->assertEquals(2, $sheet->getCell('A13')->getValue());
        $this->assertEquals('08/10/2026', $sheet->getCell('B13')->getValue());
        $this->assertEquals('13:00', $sheet->getCell('C13')->getValue());
        $this->assertEquals('Online', $sheet->getCell('D13')->getValue());
        $this->assertEquals('adam', $sheet->getCell('E13')->getValue());
        $this->assertEquals('MUA', $sheet->getCell('F13')->getValue());
        $this->assertEquals('Makeup Only', $sheet->getCell('G13')->getValue());
        $this->assertEquals('-', $sheet->getCell('H13')->getValue());
        $this->assertEquals('Rp250.000', $sheet->getCell('I13')->getValue());
        $this->assertEquals('Cancelled', $sheet->getCell('J13')->getValue());

        // Row 14 (Total Row)
        $this->assertEquals('TOTAL PENDAPATAN SELESAI', $sheet->getCell('A14')->getValue());
        $this->assertEquals('Rp25.000', $sheet->getCell('I14')->getValue());

        @unlink($tempFile);
    }

    /**
     * Test selecting multiple services is rejected (only 1 service allowed).
     */
    public function test_booking_and_walkin_reject_multiple_services_selection(): void
    {
        $customer = User::create([
            'name' => 'Single Service Tester',
            'email' => 'singleservice@test.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);

        $cashier = User::create([
            'name' => 'Kasir Single',
            'email' => 'kasirsingle@test.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $s1 = Service::create([
            'name' => 'Cut 1',
            'category' => 'barbershop',
            'price' => 30000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        $s2 = Service::create([
            'name' => 'Cut 2',
            'category' => 'barbershop',
            'price' => 40000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        // 1. Online booking with 2 services -> should fail validation
        $responseOnline = $this->actingAs($customer)->post('/pelanggan/booking', [
            'category' => 'barbershop',
            'services' => [$s1->id, $s2->id],
            'booking_date' => now()->addDay()->format('Y-m-d'),
            'booking_time' => '10:00',
        ]);
        $responseOnline->assertSessionHasErrors('services');

        // 2. Walk-in booking with 2 services -> should fail validation
        $responseWalkin = $this->actingAs($cashier)->post('/kasir/walk-in', [
            'customer_name' => 'Walkin Multi',
            'customer_phone' => '081234567899',
            'category' => 'barbershop',
            'services' => [$s1->id, $s2->id],
        ]);
        $responseWalkin->assertSessionHasErrors('services');
    }

    /**
     * Test Customer can see upcoming booking ticket and queue number in Order menu and Home page.
     */
    public function test_customer_can_see_upcoming_booking_queue_number_in_order_menu_and_home(): void
    {
        $customer = User::create([
            'name' => 'Customer Upcoming',
            'email' => 'upcomingcust@test.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);

        $service = Service::create([
            'name' => 'Haircut Modern',
            'category' => 'barbershop',
            'price' => 50000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        $futureDate = now()->addDays(3);
        $dayOfWeek = $futureDate->dayOfWeek;
        $hour = OperatingHour::where('category', 'barbershop')->where('day_of_week', $dayOfWeek)->first();
        $hour->update(['is_closed' => false, 'max_bookings_per_slot' => 5]);

        // Customer books for 3 days in the future
        $bookResponse = $this->actingAs($customer)->post('/pelanggan/booking', [
            'category' => 'barbershop',
            'services' => [$service->id],
            'booking_date' => $futureDate->format('Y-m-d'),
            'booking_time' => '11:00',
        ]);
        $bookResponse->assertRedirect('/pelanggan/order');

        $booking = Booking::where('user_id', $customer->id)->first();
        $this->assertNotNull($booking);
        $this->assertEquals('B-001', $booking->queue_number);

        // 1. Visit /pelanggan/order -> customer MUST see their ticket and queue number
        $orderPage = $this->actingAs($customer)->get('/pelanggan/order');
        $orderPage->assertStatus(200);
        $orderPage->assertSee('B-001');
        $orderPage->assertSee('Haircut Modern');
        $orderPage->assertSee($futureDate->translatedFormat('d M Y'));

        // 2. Visit /pelanggan/home -> customer sees active booking card with queue number
        $homePage = $this->actingAs($customer)->get('/pelanggan/home');
        $homePage->assertStatus(200);
        $homePage->assertSee('B-001');
        $homePage->assertSee('Antrian Aktif Anda');
    }

    /**
     * Test Cashier can view upcoming orders, filter by upcoming tab, and filter by specific date.
     */
    public function test_cashier_can_view_and_filter_upcoming_orders_and_dates(): void
    {
        $cashier = User::create([
            'name' => 'Kasir Dashboard Test',
            'email' => 'kasirdash@test.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $customer = User::create([
            'name' => 'Pelanggan MUA Future',
            'email' => 'custmuafuture@test.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);

        $service = Service::create([
            'name' => 'Graduation MUA',
            'category' => 'mua',
            'price' => 300000,
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        $futureDate = now()->addDays(5);
        $dayOfWeek = $futureDate->dayOfWeek;
        $hour = OperatingHour::where('category', 'mua')->where('day_of_week', $dayOfWeek)->first();
        $hour->update(['is_closed' => false, 'max_bookings_per_slot' => 5]);

        // Customer books MUA for 5 days in the future
        $this->actingAs($customer)->post('/pelanggan/booking', [
            'category' => 'mua',
            'services' => [$service->id],
            'booking_date' => $futureDate->format('Y-m-d'),
            'booking_time' => '13:00',
        ]);

        $booking = Booking::where('user_id', $customer->id)->first();
        $this->assertNotNull($booking);
        $this->assertEquals('M-001', $booking->queue_number);

        // 1. Cashier default dashboard (today) notifies of upcoming reservation
        $resToday = $this->actingAs($cashier)->get('/kasir/dashboard');
        $resToday->assertStatus(200);
        $resToday->assertSee('Reservasi Mendatang');

        // 2. Cashier filters by upcoming in Order menu -> sees the booking!
        $resUpcoming = $this->actingAs($cashier)->get('/kasir/orders?view=upcoming');
        $resUpcoming->assertStatus(200);
        $resUpcoming->assertSee('M-001');
        $resUpcoming->assertSee('Pelanggan MUA Future');
        $resUpcoming->assertSee('Graduation MUA');
        $resUpcoming->assertSee($futureDate->translatedFormat('d M Y'));

        // 3. Cashier filters by specific date in Order menu -> sees the booking!
        $resDate = $this->actingAs($cashier)->get('/kasir/orders?date=' . $futureDate->format('Y-m-d'));
        $resDate->assertStatus(200);
        $resDate->assertSee('M-001');
        $resDate->assertSee('Pelanggan MUA Future');
    }

    /**
     * Test Customer Order page separates Barbershop and MUA into tabs and cancel booking works.
     */
    public function test_customer_order_page_separates_categories_into_tabs_and_supports_cancellation(): void
    {
        $customer = User::create([
            'name' => 'Tabs Customer',
            'email' => 'tabscust@test.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);

        $serviceBarber = Service::create([
            'name' => 'Haircut Fade',
            'category' => 'barbershop',
            'price' => 45000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        $serviceMua = Service::create([
            'name' => 'Bridal Glam',
            'category' => 'mua',
            'price' => 350000,
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        $tomorrow = now()->addDay();
        $dayAfterTomorrow = now()->addDays(2);
        OperatingHour::whereIn('day_of_week', [$tomorrow->dayOfWeek, $dayAfterTomorrow->dayOfWeek])->update(['is_closed' => false, 'max_bookings_per_slot' => 5]);

        // Customer books Barbershop for tomorrow, and MUA for day after tomorrow
        $this->actingAs($customer)->post('/pelanggan/booking', [
            'category' => 'barbershop',
            'services' => [$serviceBarber->id],
            'booking_date' => $tomorrow->format('Y-m-d'),
            'booking_time' => '10:00',
        ]);

        $this->actingAs($customer)->post('/pelanggan/booking', [
            'category' => 'mua',
            'services' => [$serviceMua->id],
            'booking_date' => $dayAfterTomorrow->format('Y-m-d'),
            'booking_time' => '14:00',
        ]);

        $barberBooking = Booking::where('user_id', $customer->id)->where('service_category', 'barbershop')->first();
        $muaBooking = Booking::where('user_id', $customer->id)->where('service_category', 'mua')->first();

        $this->assertNotNull($barberBooking);
        $this->assertNotNull($muaBooking);

        // Visit order page -> should see both tab buttons, cancel modal structure, and tickets
        $response = $this->actingAs($customer)->get('/pelanggan/order');
        $response->assertStatus(200);
        $response->assertSee('Barbershop');
        $response->assertSee('MUA Studio');
        $response->assertSee('Batalkan Reservasi?');
        $response->assertSee($barberBooking->queue_number);
        $response->assertSee($muaBooking->queue_number);

        // Cancel the MUA booking
        $cancelRes = $this->actingAs($customer)->post("/pelanggan/order/{$muaBooking->id}/cancel");
        $cancelRes->assertRedirect('/pelanggan/order');

        $muaBooking->refresh();
        $this->assertEquals('cancelled', $muaBooking->status);
    }

    /**
     * Test booking at opening time slot 09:00 is valid, and slots outside hours are rejected.
     */
    public function test_booking_at_opening_time_slot_09_00_is_valid_and_outside_hours_is_rejected(): void
    {
        $customer = User::create([
            'name' => 'Slot Test Customer',
            'email' => 'slottest@test.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);

        $service = Service::create([
            'name' => 'Party Makeup Slot',
            'category' => 'mua',
            'price' => 250000,
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        $futureDate = now()->addDays(2);
        OperatingHour::where('category', 'mua')
            ->where('day_of_week', $futureDate->dayOfWeek)
            ->update([
                'open_time' => '09:00:00',
                'close_time' => '21:00:00',
                'is_closed' => false,
                'max_bookings_per_slot' => 5,
            ]);

        // 1. Slot 09:00 (exact open time) must SUCCEED
        $res0900 = $this->actingAs($customer)->post('/pelanggan/booking', [
            'category' => 'mua',
            'services' => [$service->id],
            'booking_date' => $futureDate->format('Y-m-d'),
            'booking_time' => '09:00',
        ]);
        $res0900->assertSessionHasNoErrors();
        $res0900->assertRedirect('/pelanggan/order');
        $this->assertEquals(1, Booking::where('user_id', $customer->id)->count());

        // 2. Slot outside operating hours (e.g. 21:00 or 22:00) must FAIL
        $customer2 = User::create([
            'name' => 'Slot Test Customer 2',
            'email' => 'slottest2@test.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);

        $resOutside = $this->actingAs($customer2)->post('/pelanggan/booking', [
            'category' => 'mua',
            'services' => [$service->id],
            'booking_date' => $futureDate->format('Y-m-d'),
            'booking_time' => '21:00',
        ]);
        $resOutside->assertSessionHasErrors('booking_time');
    }

    /**
     * Test Customer can book max 1 Barbershop and max 1 MUA on the same date.
     */
    public function test_customer_can_book_one_barbershop_and_one_mua_on_same_day_but_not_two_of_same_category(): void
    {
        $customer = User::create([
            'name' => 'Dual Booking Customer',
            'email' => 'dualcust@test.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);

        $serviceBarber = Service::create([
            'name' => 'Cut Classic',
            'category' => 'barbershop',
            'price' => 40000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        $serviceMua = Service::create([
            'name' => 'Natural Glow',
            'category' => 'mua',
            'price' => 150000,
            'duration_minutes' => 45,
            'is_active' => true,
        ]);

        $targetDate = now()->addDays(3);
        OperatingHour::whereIn('category', ['barbershop', 'mua'])
            ->where('day_of_week', $targetDate->dayOfWeek)
            ->update([
                'open_time' => '09:00:00',
                'close_time' => '21:00:00',
                'is_closed' => false,
                'max_bookings_per_slot' => 5,
            ]);

        // 1. Book Barbershop at 10:00 -> SUCCESS
        $resBarber = $this->actingAs($customer)->post('/pelanggan/booking', [
            'category' => 'barbershop',
            'services' => [$serviceBarber->id],
            'booking_date' => $targetDate->format('Y-m-d'),
            'booking_time' => '10:00',
        ]);
        $resBarber->assertSessionHasNoErrors();
        $resBarber->assertRedirect('/pelanggan/order');
        $this->assertEquals(1, Booking::where('user_id', $customer->id)->where('service_category', 'barbershop')->count());

        // 2. Book MUA on the SAME date at 14:00 -> MUST ALSO SUCCEED!
        $resMua = $this->actingAs($customer)->post('/pelanggan/booking', [
            'category' => 'mua',
            'services' => [$serviceMua->id],
            'booking_date' => $targetDate->format('Y-m-d'),
            'booking_time' => '14:00',
        ]);
        $resMua->assertSessionHasNoErrors();
        $resMua->assertRedirect('/pelanggan/order');
        $this->assertEquals(1, Booking::where('user_id', $customer->id)->where('service_category', 'mua')->count());

        // 3. Customer tries to book a SECOND Barbershop on the same date -> MUST FAIL
        $resBarber2 = $this->actingAs($customer)->post('/pelanggan/booking', [
            'category' => 'barbershop',
            'services' => [$serviceBarber->id],
            'booking_date' => $targetDate->format('Y-m-d'),
            'booking_time' => '16:00',
        ]);
        $resBarber2->assertSessionHasErrors('booking_date');

        // 4. Customer tries to book a SECOND MUA on the same date -> MUST FAIL
        $resMua2 = $this->actingAs($customer)->post('/pelanggan/booking', [
            'category' => 'mua',
            'services' => [$serviceMua->id],
            'booking_date' => $targetDate->format('Y-m-d'),
            'booking_time' => '17:00',
        ]);
        $resMua2->assertSessionHasErrors('booking_date');

        // 5. Total active bookings for this user on target date is exactly 2 (1 Barbershop + 1 MUA)
        $this->assertEquals(2, Booking::where('user_id', $customer->id)->whereDate('booking_date', $targetDate->format('Y-m-d'))->count());
    }

    /**
     * Test cashier multi-tab dashboard, 3 queue actions (panggil, layani, batalkan), and bottom navigation menus.
     */
    public function test_cashier_dashboard_queue_actions_and_bottom_navigation_modules(): void
    {
        $cashier = User::create([
            'name' => 'Kasir Operasional',
            'email' => 'kasir.op@example.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $customer = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
            'phone' => '081233445566',
        ]);

        $serviceBarber = Service::create([
            'name' => 'Executive Cut',
            'category' => 'barbershop',
            'price' => 50000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        $serviceMua = Service::create([
            'name' => 'Glamour MUA',
            'category' => 'mua',
            'price' => 200000,
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        // 1. Create a Barbershop booking for today
        $bookingBarber = Booking::create([
            'booking_code' => 'GL-TEST-001',
            'user_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone,
            'booking_date' => now()->toDateString(),
            'booking_time' => '10:00:00',
            'service_category' => 'barbershop',
            'queue_number' => 'B-001',
            'status' => 'waiting',
            'total_price' => 50000,
        ]);
        $bookingBarber->items()->create(['service_id' => $serviceBarber->id, 'price' => 50000]);

        // Create a MUA booking for today
        $bookingMua = Booking::create([
            'booking_code' => 'GL-TEST-002',
            'user_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone,
            'booking_date' => now()->toDateString(),
            'booking_time' => '11:00:00',
            'service_category' => 'mua',
            'queue_number' => 'M-001',
            'status' => 'waiting',
            'total_price' => 200000,
        ]);
        $bookingMua->items()->create(['service_id' => $serviceMua->id, 'price' => 200000]);

        // 2. Test Barbershop category dashboard
        $resBarberDash = $this->actingAs($cashier)->get('/kasir/dashboard?category=barbershop');
        $resBarberDash->assertStatus(200);
        $resBarberDash->assertSee('B-001');
        $resBarberDash->assertDontSee('M-001');
        $resBarberDash->assertSee('Panggil');
        $resBarberDash->assertSee('Layani');
        $resBarberDash->assertSee('Batalkan');

        // 3. Test MUA category dashboard
        $resMuaDash = $this->actingAs($cashier)->get('/kasir/dashboard?category=mua');
        $resMuaDash->assertStatus(200);
        $resMuaDash->assertSee('M-001');
        $resMuaDash->assertDontSee('B-001');

        // 4. Action 1: PANGGIL (Dipanggil)
        $resCall = $this->actingAs($cashier)->post(route('kasir.queue.call', $bookingBarber));
        $resCall->assertRedirect();
        $this->assertEquals('called', $bookingBarber->fresh()->status);

        // Verify customer sees 'called' alert banner on their order view
        $resCustOrder = $this->actingAs($customer)->get('/pelanggan/order');
        $resCustOrder->assertStatus(200);
        $resCustOrder->assertSee('Nomor Antrian Anda Sedang Dipanggil!');

        // 5. Action 2: LAYANI (Sedang Dilayani / in_progress)
        $resProcess = $this->actingAs($cashier)->post(route('kasir.queue.process', $bookingBarber));
        $resProcess->assertRedirect();
        $this->assertEquals('in_progress', $bookingBarber->fresh()->status);

        // 6. Action 3: BATALKAN on waiting booking
        $resCancel = $this->actingAs($cashier)->post(route('kasir.queue.cancel', $bookingMua));
        $resCancel->assertRedirect();
        $this->assertEquals('cancelled', $bookingMua->fresh()->status);

        // 7. Verify Kasir Orders Menu
        $resOrders = $this->actingAs($cashier)->get('/kasir/orders');
        $resOrders->assertStatus(200);
        $resOrders->assertSee('MANAJEMEN ORDER');

        // 8. Verify Kasir Customers Menu
        $resCustomers = $this->actingAs($cashier)->get('/kasir/customers');
        $resCustomers->assertStatus(200);
        $resCustomers->assertSee('Budi Santoso');

        // 9. Verify Kasir Reports Menu & Export Constraints
        $resReports = $this->actingAs($cashier)->get('/kasir/reports');
        $resReports->assertStatus(200);
        $resReports->assertSee('LAPORAN PELAYANAN KASIR');

        // Export Daily Excel -> Success (200)
        $resExportDaily = $this->actingAs($cashier)->get(route('kasir.reports.export-excel', ['period' => 'daily']));
        $resExportDaily->assertStatus(200);

        // Export Weekly Excel -> Success (200)
        $resExportWeekly = $this->actingAs($cashier)->get(route('kasir.reports.export-excel', ['period' => 'weekly']));
        $resExportWeekly->assertStatus(200);

        // Export Monthly Excel -> Prohibited / Bad Request (400)
        $resExportMonthly = $this->actingAs($cashier)->get(route('kasir.reports.export-excel', ['period' => 'monthly']));
        $resExportMonthly->assertStatus(400);
    }

    /**
     * Test phone input restrictions: letters and special symbols rejected.
     */
    public function test_phone_input_rejects_alphabets_and_accepts_valid_numbers(): void
    {
        $cashier = User::create([
            'name' => 'Kasir Phone Test',
            'email' => 'kasirphone@test.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $service = Service::create([
            'name' => 'Shave Phone Test',
            'category' => 'barbershop',
            'price' => 35000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        // Attempt walkin with letters in phone
        $resInvalidPhone = $this->actingAs($cashier)->post('/kasir/walk-in', [
            'customer_name' => 'Budi Huruf',
            'customer_phone' => '0812abcd3456',
            'category' => 'barbershop',
            'services' => [$service->id],
        ]);
        $resInvalidPhone->assertSessionHasErrors('customer_phone');

        // Logout cashier to test guest registration with invalid phone
        auth()->logout();

        // Attempt register with letters in phone
        $resRegisterInvalid = $this->post('/register', [
            'name' => 'Budi Register',
            'email' => 'budiregphone@test.com',
            'phone' => '0812-PHONE-NO',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $resRegisterInvalid->assertSessionHasErrors('phone');
    }

    /**
     * Test multi-cashier concurrency: multiple cashiers can serve concurrently, each limited to 1 in_progress booking.
     */
    public function test_multiple_cashiers_can_serve_concurrently_and_each_limited_to_one(): void
    {
        $cashier1 = User::create([
            'name' => 'Kasir Satu',
            'email' => 'kasir1@test.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $cashier2 = User::create([
            'name' => 'Kasir Dua',
            'email' => 'kasir2@test.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $service = Service::create([
            'name' => 'Cut Multi Kasir',
            'category' => 'barbershop',
            'price' => 50000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        $today = \Carbon\Carbon::today('Asia/Jakarta');

        $b1 = Booking::create([
            'booking_code' => 'BK-MK-1',
            'service_category' => 'barbershop',
            'booking_date' => $today->toDateString(),
            'booking_time' => '10:00:00',
            'queue_number' => 'B-001',
            'status' => 'waiting',
            'total_price' => 50000,
            'customer_name' => 'Pelanggan 1',
            'customer_phone' => '0811111111',
        ]);

        $b2 = Booking::create([
            'booking_code' => 'BK-MK-2',
            'service_category' => 'barbershop',
            'booking_date' => $today->toDateString(),
            'booking_time' => '10:15:00',
            'queue_number' => 'B-002',
            'status' => 'waiting',
            'total_price' => 50000,
            'customer_name' => 'Pelanggan 2',
            'customer_phone' => '0822222222',
        ]);

        $b3 = Booking::create([
            'booking_code' => 'BK-MK-3',
            'service_category' => 'barbershop',
            'booking_date' => $today->toDateString(),
            'booking_time' => '10:30:00',
            'queue_number' => 'B-003',
            'status' => 'waiting',
            'total_price' => 50000,
            'customer_name' => 'Pelanggan 3',
            'customer_phone' => '0833333333',
        ]);

        // Kasir 1 starts serving B1 -> Success
        $resK1B1 = $this->actingAs($cashier1)->post(route('kasir.queue.process', $b1));
        $resK1B1->assertRedirect();
        $this->assertEquals('in_progress', $b1->fresh()->status);
        $this->assertEquals($cashier1->id, $b1->fresh()->cashier_id);

        // Kasir 2 starts serving B2 SIMULTANEOUSLY (without waiting for B1) -> Success!
        $resK2B2 = $this->actingAs($cashier2)->post(route('kasir.queue.process', $b2));
        $resK2B2->assertRedirect();
        $this->assertEquals('in_progress', $b2->fresh()->status);
        $this->assertEquals($cashier2->id, $b2->fresh()->cashier_id);

        // Kasir 1 attempts to serve B3 while B1 is still in progress by Kasir 1 -> Blocked!
        $resK1B3 = $this->actingAs($cashier1)->post(route('kasir.queue.process', $b3));
        $resK1B3->assertSessionHasErrors('error');
        $this->assertEquals('waiting', $b3->fresh()->status);

        // Kasir 1 completes B1
        $resComplete = $this->actingAs($cashier1)->post(route('kasir.queue.complete', $b1));
        $resComplete->assertRedirect();
        $this->assertEquals('completed', $b1->fresh()->status);

        // Now Kasir 1 is free and can serve B3 -> Success!
        $resK1B3Again = $this->actingAs($cashier1)->post(route('kasir.queue.process', $b3));
        $resK1B3Again->assertRedirect();
        $this->assertEquals('in_progress', $b3->fresh()->status);
        $this->assertEquals($cashier1->id, $b3->fresh()->cashier_id);
    }

    /**
     * Test kasir can access staff menu in read-only mode without edit buttons.
     */
    public function test_kasir_can_access_staff_menu_in_readonly_mode(): void
    {
        $cashier = User::create([
            'name' => 'Kasir Staff Test',
            'email' => 'kasirstaff@test.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $staff = Staff::create([
            'name' => 'Rahmat Kapster',
            'role' => 'kapster',
            'phone' => '0877889900',
            'is_active' => true,
        ]);

        // Kasir views staff list
        $response = $this->actingAs($cashier)->get(route('kasir.staffs.index'));
        $response->assertStatus(200);
        $response->assertSee('INFORMASI PEKERJA');
        $response->assertSee('Rahmat Kapster');
        $response->assertSee('0877889900');
        // Read-only: no toggle active buttons
        $response->assertDontSee('Set Nonaktif');
        $response->assertDontSee('Set Aktif');
    }

    /**
     * Test cashier cannot complete an order being processed by another cashier.
     */
    public function test_cashier_cannot_complete_order_handled_by_another_cashier(): void
    {
        $cashier1 = User::create([
            'name' => 'Kasir A',
            'email' => 'kasira@test.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $cashier2 = User::create([
            'name' => 'Kasir B',
            'email' => 'kasirb@test.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $today = \Carbon\Carbon::today('Asia/Jakarta');

        $booking = Booking::create([
            'booking_code' => 'BK-DIFF-1',
            'service_category' => 'barbershop',
            'booking_date' => $today->toDateString(),
            'booking_time' => '11:00:00',
            'queue_number' => 'B-099',
            'status' => 'in_progress',
            'cashier_id' => $cashier1->id,
            'total_price' => 50000,
            'customer_name' => 'Pelanggan X',
            'customer_phone' => '0812345678',
        ]);

        // Cashier 2 attempts to complete Cashier 1's order -> Blocked!
        $res = $this->actingAs($cashier2)->post(route('kasir.queue.complete', $booking));
        $res->assertSessionHasErrors('error');
        $this->assertEquals('in_progress', $booking->fresh()->status);
        $this->assertEquals($cashier1->id, $booking->fresh()->cashier_id);

        // Cashier 1 completes their own order -> Success!
        $resOwn = $this->actingAs($cashier1)->post(route('kasir.queue.complete', $booking));
        $resOwn->assertRedirect();
        $this->assertEquals('completed', $booking->fresh()->status);
    }

    /**
     * Test admin can register customer membership with discount and quota, and customer receives discount.
     */
    public function test_admin_can_register_member_and_customer_receives_discount(): void
    {
        $admin = User::create([
            'name' => 'Admin Member Test',
            'email' => 'adminmember@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $customer = User::create([
            'name' => 'Budi Member',
            'email' => 'budimember@test.com',
            'password' => bcrypt('password'),
            'phone' => '081299998888',
            'role' => 'pelanggan',
        ]);

        $service = Service::create([
            'name' => 'Haircut Member VIP',
            'category' => 'barbershop',
            'price' => 100000,
            'duration_minutes' => 45,
            'is_active' => true,
        ]);

        \App\Models\OperatingHour::updateOrCreate(
            ['category' => 'barbershop', 'day_of_week' => \Carbon\Carbon::tomorrow()->dayOfWeek],
            ['open_time' => '09:00', 'close_time' => '21:00', 'is_closed' => false]
        );

        // 1. Admin registers customer as Member with 20% discount and 3x quota
        $resMember = $this->actingAs($admin)->post(route('admin.customers.membership', $customer), [
            'is_member' => 1,
            'member_discount' => 20,
            'member_discount_type' => 'percent',
            'member_discount_quota' => 3,
        ]);
        $resMember->assertRedirect();

        $customer->refresh();
        $this->assertTrue($customer->is_member);
        $this->assertEquals(20, $customer->member_discount);
        $this->assertEquals('percent', $customer->member_discount_type);
        $this->assertEquals(3, $customer->member_discount_quota);
        $this->assertEquals(3, $customer->member_discount_remaining);
        $this->assertTrue($customer->hasActiveMemberDiscount());

        // 2. Customer books the 100.000 service -> Gets 20.000 discount (Total: 80.000), quota decrements to 2
        $resBooking = $this->actingAs($customer)->post(route('pelanggan.booking.store'), [
            'category' => 'barbershop',
            'services' => [$service->id],
            'booking_date' => \Carbon\Carbon::tomorrow()->toDateString(),
            'booking_time' => '10:00:00',
        ]);
        $resBooking->assertRedirect(route('pelanggan.order.index'));

        $booking = Booking::where('user_id', $customer->id)->latest()->first();
        $this->assertNotNull($booking);
        $this->assertEquals(20000, $booking->discount_amount);
        $this->assertEquals(80000, $booking->total_price);

        $customer->refresh();
        $this->assertEquals(2, $customer->member_discount_remaining);
    }
}

