<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\CategoryContent;
use App\Models\OperatingHour;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
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
        $date = Carbon::tomorrow();
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
        $this->assertEquals(Carbon::now('Asia/Jakarta')->toDateString(), $booking->booking_date->toDateString());
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

        $content = CategoryContent::create([
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
            'terms' => 'Please arrive 15 minutes early.',
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/category-contents');

        $content->refresh();
        $this->assertEquals('Updated Barbershop VIP', $content->title);
        $this->assertEquals('New Premium Experience', $content->tagline);
        $this->assertCount(3, $content->facilities_list);
    }

    /**
     * Test admin can manage login page content and it reflects on login page.
     */
    public function test_admin_can_manage_login_page_content_and_it_reflects_on_login(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        // 1. Visit index - auto-creates login record if not present
        $indexResponse = $this->actingAs($admin)->get('/admin/category-contents');
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Halaman Login');

        $loginContent = CategoryContent::where('category', 'login')->firstOrFail();

        // 2. Visit edit page
        $editResponse = $this->actingAs($admin)->get("/admin/category-contents/{$loginContent->id}/edit");
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Edit Konten &amp; Tampilan Halaman Login', false);
        $editResponse->assertSee('Judul Sambutan Banner (Kiri)');

        // 3. Update login content with custom text and fake banner image
        $fakeImage = \Illuminate\Http\UploadedFile::fake()->image('barber-chair.jpg', 800, 600);

        $updateResponse = $this->actingAs($admin)->put("/admin/category-contents/{$loginContent->id}", [
            'title' => 'Selamat Bergabung',
            'tagline' => 'Eksklusif Barbershop',
            'description' => 'Silakan masuk untuk reservasi kapster terbaik kami',
            'facilities' => 'Grooming Bersama',
            'terms' => 'Silakan gunakan email terdaftar Anda',
            'banner_image' => $fakeImage,
            'is_active' => '1',
        ]);

        $updateResponse->assertRedirect('/admin/category-contents');

        $loginContent->refresh();
        $this->assertEquals('Selamat Bergabung', $loginContent->title);
        $this->assertEquals('Eksklusif Barbershop', $loginContent->tagline);
        $this->assertEquals('Grooming Bersama', $loginContent->facilities);
        $this->assertNotNull($loginContent->banner_image);
        $this->assertTrue(\Illuminate\Support\Facades\Storage::disk('public')->exists($loginContent->banner_image));

        // 4. Admin visits preview login route without being redirected by guest middleware
        $previewResponse = $this->actingAs($admin)->get('/admin/category-contents/preview-login');
        $previewResponse->assertStatus(200);
        $previewResponse->assertSee('Pratinjau Halaman Login');
        $previewResponse->assertSee('Kembali ke Kelola Konten');
        $previewResponse->assertSee('Selamat');
        $previewResponse->assertSee('Bergabung');
        $previewResponse->assertSee('Silakan masuk untuk reservasi kapster terbaik kami');

        // 5. Verify public login page displays the updated content for guests
        $this->post('/logout');

        $guestLoginResponse = $this->get('/login');
        $guestLoginResponse->assertStatus(200);
        $guestLoginResponse->assertSee('Selamat');
        $guestLoginResponse->assertSee('Bergabung');
        $guestLoginResponse->assertSee('Eksklusif Barbershop');
        $guestLoginResponse->assertSee('Grooming Bersama');
        $guestLoginResponse->assertSee('Silakan masuk untuk reservasi kapster terbaik kami');
        $guestLoginResponse->assertSee('Silakan gunakan email terdaftar Anda');
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
                ],
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

        // Customer 2 views MUA booking page: 10:00 is marked full in slotsAvailability and rendered
        $resViewMua = $this->actingAs($customer2)->get('/pelanggan/booking/mua');
        $resViewMua->assertOk();
        $resViewMua->assertSee('x-data="bookingForm()"', false);
        $resViewMua->assertSee('serverSlotsAvailability', false);
        $resViewMua->assertViewHas('slotsAvailability', function ($slots) use ($tomorrow) {
            $dateKey = $tomorrow->format('Y-m-d');
            return isset($slots[$dateKey]['10:00']) && $slots[$dateKey]['10:00']['is_full'] === true;
        });

        // AJAX check slots endpoint also confirms 10:00 is full for MUA
        $resAjaxMua = $this->actingAs($customer2)->get(route('pelanggan.booking.check-slots', [
            'category' => 'mua',
            'date' => $tomorrow->format('Y-m-d'),
        ]));
        $resAjaxMua->assertOk();
        $resAjaxMua->assertJsonPath('slots.10:00.is_full', true);
        $resAjaxMua->assertJsonPath('slots.10:00.remaining', 0);

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

        $staff = Staff::create([
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
        BookingItem::create([
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
        BookingItem::create([
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

        $spreadsheet = IOFactory::load($tempFile);
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
     * Test Excel export populates serving cashier name in Staff column when served by cashier.
     */
    public function test_excel_export_displays_serving_cashier_when_staff_is_not_assigned(): void
    {
        $admin = User::create([
            'name' => 'Admin Excel Cashier',
            'email' => 'adminexcelcashier@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $cashier = User::create([
            'name' => 'Kasir Budi',
            'email' => 'kasirbudi@test.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $customer = User::create([
            'name' => 'Pelanggan Walkin',
            'email' => 'walkin@test.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);

        $service = Service::create([
            'name' => 'Classic Cut',
            'category' => 'barbershop',
            'price' => 35000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        $booking = Booking::create([
            'booking_code' => 'BK-CASHIER-01',
            'user_id' => $customer->id,
            'cashier_id' => $cashier->id,
            'staff_id' => null,
            'service_category' => 'barbershop',
            'booking_date' => '2026-10-15',
            'booking_time' => '10:00',
            'queue_number' => 'B-015',
            'total_price' => 35000,
            'status' => 'completed',
        ]);
        BookingItem::create([
            'booking_id' => $booking->id,
            'service_id' => $service->id,
            'price' => 35000,
        ]);

        // Walk-in booking without user account
        $walkinBooking = Booking::create([
            'booking_code' => 'BK-WALKIN-01',
            'user_id' => null,
            'customer_name' => 'Bambang Walkin',
            'cashier_id' => $cashier->id,
            'staff_id' => null,
            'service_category' => 'barbershop',
            'booking_date' => '2026-10-15',
            'booking_time' => '11:00',
            'queue_number' => 'B-016',
            'notes' => 'Walk-in customer',
            'total_price' => 35000,
            'status' => 'completed',
        ]);
        BookingItem::create([
            'booking_id' => $walkinBooking->id,
            'service_id' => $service->id,
            'price' => 35000,
        ]);

        $response = $this->actingAs($admin)->get('/admin/reports/export-excel?start_date=2026-10-15&end_date=2026-10-15');
        $response->assertStatus(200);

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $tempFile = tempnam(sys_get_temp_dir(), 'test_excel_cashier_');
        file_put_contents($tempFile, $content);

        $spreadsheet = IOFactory::load($tempFile);
        $sheet = $spreadsheet->getActiveSheet();

        // Row 12: Online customer booking served by cashier
        $this->assertEquals('Online', $sheet->getCell('D12')->getValue());
        $this->assertEquals('Kasir Budi', $sheet->getCell('H12')->getValue());

        // Row 13: Walk-in booking without account
        $this->assertEquals('Offline', $sheet->getCell('D13')->getValue());
        $this->assertEquals('Kasir Budi', $sheet->getCell('H13')->getValue());

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
        $resDate = $this->actingAs($cashier)->get('/kasir/orders?date='.$futureDate->format('Y-m-d'));
        $resDate->assertStatus(200);
        $resDate->assertSee('M-001');
        $resDate->assertSee('Pelanggan MUA Future');

        // 4. Default order page loads tomorrow's orders without shortcut pills
        $resDefault = $this->actingAs($cashier)->get('/kasir/orders');
        $resDefault->assertStatus(200);
        $resDefault->assertViewHas('selectedDate', Carbon::tomorrow()->format('Y-m-d'));
        $resDefault->assertDontSee('Semua Mendatang');
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

        // 9. Verify Kasir Reports Menu (Profil Akun Kasir & Data Diri Karyawan Murni Tanpa Akses Password) & Export Constraints
        $resReports = $this->actingAs($cashier)->get('/kasir/reports');
        $resReports->assertStatus(200);
        $resReports->assertSee('DATA DIRI KARYAWAN');
        $resReports->assertDontSee('Total Pendapatan');
        $resReports->assertDontSee('Riwayat Pelayanan Terakhir yang Ditangani');
        $resReports->assertDontSee('Password Baru');

        // Test update profile (nama & no. hp saja)
        $resUpdateProfile = $this->actingAs($cashier)->put(route('kasir.reports.update-profile'), [
            'name' => 'Kasir Diperbarui',
            'phone' => '081234567890',
        ]);
        $resUpdateProfile->assertRedirect(route('kasir.reports.index'));
        $this->assertEquals('Kasir Diperbarui', $cashier->fresh()->name);

        // Verify cashier is strictly forbidden from changing password
        $resPassword = $this->actingAs($cashier)->put('/password', [
            'current_password' => 'password',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);
        $resPassword->assertStatus(403);

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
        $this->app['auth']->logout();

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

        $today = Carbon::today('Asia/Jakarta');

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
     * Test kasir riwayat menu displays served customers history and daily excel export.
     */
    public function test_kasir_can_access_staff_menu_in_readonly_mode(): void
    {
        $cashier = User::create([
            'name' => 'Kasir Staff Test',
            'email' => 'kasirstaff@test.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $today = Carbon::today()->format('Y-m-d');
        $booking = Booking::create([
            'booking_code' => 'BK-TEST-SERVED',
            'queue_number' => 'B-099',
            'customer_name' => 'Pelanggan Dilayani',
            'customer_phone' => '0877889900',
            'booking_date' => $today,
            'booking_time' => '10:00:00',
            'service_category' => 'barbershop',
            'status' => 'completed',
            'cashier_id' => $cashier->id,
            'total_price' => 75000,
            'payment_status' => 'paid',
            'payment_method' => 'cash',
        ]);

        // Kasir views served customer history
        $response = $this->actingAs($cashier)->get(route('kasir.staffs.index', ['date' => $today]));
        $response->assertStatus(200);
        $response->assertSee('RIWAYAT PELANGGAN YANG SUDAH DILAYANI');
        $response->assertSee('Pelanggan Dilayani');
        $response->assertSee('0877889900');

        // Test daily excel export
        $exportResponse = $this->actingAs($cashier)->get(route('kasir.staffs.export-excel', ['date' => $today]));
        $exportResponse->assertStatus(200);
        $exportResponse->assertHeader('content-disposition');
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

        $today = Carbon::today('Asia/Jakarta');

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

        OperatingHour::updateOrCreate(
            ['category' => 'barbershop', 'day_of_week' => Carbon::tomorrow()->dayOfWeek],
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
            'booking_date' => Carbon::tomorrow()->toDateString(),
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

    public function test_cashier_and_admin_can_toggle_block_and_reset_customer_password_with_custom_input(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create([
            'role' => 'pelanggan',
            'is_active' => true,
            'password' => bcrypt('initial-pass-123'),
        ]);

        // 1. Kasir can block customer
        $resKasirBlock = $this->actingAs($cashier)->post(route('kasir.customers.toggle-block', $customer));
        $resKasirBlock->assertSessionHas('block_status_updated');
        $customer->refresh();
        $this->assertFalse($customer->is_active);

        // 2. Kasir can unblock customer
        $resKasirUnblock = $this->actingAs($cashier)->post(route('kasir.customers.toggle-block', $customer));
        $resKasirUnblock->assertSessionHas('block_status_updated');
        $customer->refresh();
        $this->assertTrue($customer->is_active);

        // 3. Kasir resets password with custom input
        $resKasirReset = $this->actingAs($cashier)->post(route('kasir.customers.reset-password', $customer), [
            'password' => 'KasirNewPass#2026',
            'password_confirmation' => 'KasirNewPass#2026',
        ]);
        $resKasirReset->assertSessionHas('password_reset_success');
        $customer->refresh();
        $this->assertTrue(Hash::check('KasirNewPass#2026', $customer->password));

        // 4. Kasir reset password fails on confirmation mismatch
        $resKasirFail = $this->actingAs($cashier)->post(route('kasir.customers.reset-password', $customer), [
            'password' => 'ValidPass123',
            'password_confirmation' => 'Mismatch123',
        ]);
        $resKasirFail->assertSessionHasErrors('password');

        // 5. Admin can block customer
        $resAdminBlock = $this->actingAs($admin)->post(route('admin.customers.toggle-block', $customer));
        $resAdminBlock->assertSessionHas('block_status_updated');
        $customer->refresh();
        $this->assertFalse($customer->is_active);

        // 5b. Admin customer show page renders modals for block and member
        $resShow = $this->actingAs($admin)->get(route('admin.customers.show', $customer));
        $resShow->assertOk();
        $resShow->assertSee('openBlockModal');
        $resShow->assertSee('openMemberModal');
        $resShow->assertSee('Konfirmasi Blokir Pelanggan');
        $resShow->assertSee('Atur Member');

        // 6. Admin can update customer biodata and reset password via edit modal
        $resAdminUpdate = $this->actingAs($admin)->put(route('admin.customers.update', $customer), [
            'name' => 'Chelsey Updated',
            'email' => $customer->email,
            'phone' => '08999888777',
            'password' => 'AdminNewPass#2026',
            'password_confirmation' => 'AdminNewPass#2026',
        ]);
        $resAdminUpdate->assertSessionHas('success');
        $customer->refresh();
        $this->assertEquals('Chelsey Updated', $customer->name);
        $this->assertEquals('08999888777', $customer->phone);
        $this->assertTrue(Hash::check('AdminNewPass#2026', $customer->password));
    }

    public function test_admin_can_update_cashier_active_status_in_edit_and_delete_cashier(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $cashier = User::factory()->create([
            'role' => 'kasir',
            'is_active' => true,
            'name' => 'Kasir Lama',
            'email' => 'kasirlama@test.com',
            'phone' => '08123456789',
        ]);

        // 1. Admin can view cashier index (doesn't fail)
        $resIndex = $this->actingAs($admin)->get(route('admin.cashiers.index'));
        $resIndex->assertOk();
        $resIndex->assertSee('Kasir Lama');

        // 2. Admin can edit cashier and set is_active to 0
        $resUpdate = $this->actingAs($admin)->put(route('admin.cashiers.update', $cashier), [
            'name' => 'Kasir Diedit',
            'email' => 'kasiredit@test.com',
            'phone' => '081299998888',
            'is_active' => 0,
        ]);
        $resUpdate->assertRedirect(route('admin.cashiers.index'));
        $cashier->refresh();
        $this->assertEquals('Kasir Diedit', $cashier->name);
        $this->assertEquals('kasiredit@test.com', $cashier->email);
        $this->assertFalse($cashier->is_active);

        // 3. Admin can delete cashier
        $resDelete = $this->actingAs($admin)->delete(route('admin.cashiers.destroy', $cashier));
        $resDelete->assertRedirect(route('admin.cashiers.index'));
        $this->assertDatabaseMissing('users', ['id' => $cashier->id]);
    }

    /**
     * Test booking time slots that have already passed their 1-hour range for today cannot be selected or booked.
     * E.g. for 10.00-11.00, at 10.59 it is still selectable. At 11.00 it is disabled.
     */
    public function test_passed_booking_time_slots_cannot_be_booked_for_today(): void
    {
        // Mock current time to 10:59 AM WIB on Monday 2026-11-02
        Carbon::setTestNow(Carbon::parse('2026-11-02 10:59:00', 'Asia/Jakarta'));

        $customer = User::create([
            'name' => 'John Customer',
            'email' => 'johncust@test.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);

        $service = Service::create([
            'name' => 'Haircut Biasa',
            'category' => 'barbershop',
            'price' => 30000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        OperatingHour::updateOrCreate(
            ['category' => 'barbershop', 'day_of_week' => 1], // Monday
            ['open_time' => '09:00:00', 'close_time' => '21:00:00', 'is_closed' => false, 'max_bookings_per_slot' => 5]
        );

        // 1. At 10:59 AM:
        // - 09:00 (09.00 - 10.00) has passed 10:00 -> is_past = true
        // - 10:00 (10.00 - 11.00) has NOT passed 11:00 -> is_past = false (still selectable)
        // - 11:00 (11.00 - 12.00) -> is_past = false
        $resAjax = $this->actingAs($customer)->getJson('/pelanggan/booking/check-slots?category=barbershop&date=2026-11-02');
        $resAjax->assertOk();
        $slots = $resAjax->json('slots');
        $this->assertTrue($slots['09:00']['is_past']);
        $this->assertFalse($slots['10:00']['is_past']);
        $this->assertFalse($slots['11:00']['is_past']);

        // 2. Booking 09:00 (range ended at 10:00) on today must FAIL with error
        $resPost0900 = $this->actingAs($customer)->post('/pelanggan/booking', [
            'category' => 'barbershop',
            'services' => [$service->id],
            'booking_date' => '2026-11-02',
            'booking_time' => '09:00',
        ]);
        $resPost0900->assertSessionHasErrors('booking_time');

        // 3. Booking 10:00 at 10:59 (range ends at 11:00) on today must SUCCEED
        $resPost1000 = $this->actingAs($customer)->post('/pelanggan/booking', [
            'category' => 'barbershop',
            'services' => [$service->id],
            'booking_date' => '2026-11-02',
            'booking_time' => '10:00',
        ]);
        $resPost1000->assertSessionHasNoErrors();
        $resPost1000->assertRedirect('/pelanggan/order');
        $this->assertEquals(1, Booking::where('user_id', $customer->id)->count());

        // 4. Advance time to 11:00 AM (range 10.00 - 11.00 has now completely passed)
        Carbon::setTestNow(Carbon::parse('2026-11-02 11:00:00', 'Asia/Jakarta'));

        $customer2 = User::create([
            'name' => 'Second Customer',
            'email' => 'secondcust@test.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);

        $resAjax1100 = $this->actingAs($customer2)->getJson('/pelanggan/booking/check-slots?category=barbershop&date=2026-11-02');
        $slots1100 = $resAjax1100->json('slots');
        $this->assertTrue($slots1100['10:00']['is_past']);

        $resPost1000At1100 = $this->actingAs($customer2)->post('/pelanggan/booking', [
            'category' => 'barbershop',
            'services' => [$service->id],
            'booking_date' => '2026-11-02',
            'booking_time' => '10:00',
        ]);
        $resPost1000At1100->assertSessionHasErrors('booking_time');

        Carbon::setTestNow(); // Clear mocked time
    }

    /**
     * Test admin can log in using username, access account settings menu, and update Gmail and password.
     */
    public function test_admin_can_login_with_username_and_update_gmail_and_password(): void
    {
        $admin = User::create([
            'name' => 'Owner Admin',
            'username' => 'adminutama',
            'email' => 'adminlama@gmail.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        // 1. Admin can log in using username 'adminutama' (without entering email)
        $responseLogin = $this->post('/login', [
            'email' => 'adminutama',
            'password' => 'password123',
        ]);
        $responseLogin->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);

        // 2. Admin can view account settings page
        $resEdit = $this->actingAs($admin)->get(route('admin.profile.edit'));
        $resEdit->assertOk();
        $resEdit->assertSee('Pengaturan Akun Administrator');
        $resEdit->assertSee('adminutama');
        $resEdit->assertSee('adminlama@gmail.com');

        // 3. Admin can update Gmail and username
        $resUpdate = $this->actingAs($admin)->put(route('admin.profile.update'), [
            'name' => 'Super Admin Baru',
            'username' => 'adminbaru',
            'email' => 'adminbaru@gmail.com',
            'phone' => '081234567899',
        ]);
        $resUpdate->assertRedirect();
        $resUpdate->assertSessionHas('success');

        $admin->refresh();
        $this->assertEquals('adminbaru', $admin->username);
        $this->assertEquals('adminbaru@gmail.com', $admin->email);
        $this->assertEquals('Super Admin Baru', $admin->name);

        // 4. Admin can update password
        $resPass = $this->actingAs($admin)->put(route('admin.profile.password'), [
            'current_password' => 'password123',
            'password' => 'passwordBaru2026',
            'password_confirmation' => 'passwordBaru2026',
        ]);
        $resPass->assertRedirect();
        $resPass->assertSessionHas('success');

        // 5. Admin can log in with new username and new password
        $this->post('/logout');
        $this->assertGuest();

        $responseLoginNew = $this->post('/login', [
            'email' => 'adminbaru',
            'password' => 'passwordBaru2026',
        ]);
        $responseLoginNew->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_reports_auto_submit_and_can_destroy_all_transaction_records(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // 1. Visit report page, verify buttons "Terapkan Filter" and "Reset" are gone
        $res = $this->actingAs($admin)->get(route('admin.reports.index'));
        $res->assertOk();
        $res->assertDontSee('Terapkan Filter');
        $res->assertSee('Hapus Semua Data');
        $res->assertSee('onchange="this.form.submit()"', false);

        // 2. Admin calls destroy-all to clear transactions
        $resDeleteAll = $this->actingAs($admin)->delete(route('admin.reports.destroy-all'));
        $resDeleteAll->assertRedirect(route('admin.reports.index'));
        $resDeleteAll->assertSessionHas('success');
        $this->assertEquals(0, \App\Models\Booking::count());
        $this->assertEquals(0, \App\Models\BookingItem::count());
    }

    public function test_admin_reports_search_feature_filters_transactions_properly(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer1 = User::factory()->create(['name' => 'Budi Santoso', 'email' => 'budi@example.com']);
        $customer2 = User::factory()->create(['name' => 'Siti Rahma', 'email' => 'siti@example.com']);

        Booking::create([
            'booking_code' => 'BK-BUDI-001',
            'user_id' => $customer1->id,
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081234567890',
            'service_category' => 'barbershop',
            'booking_date' => Carbon::today()->format('Y-m-d'),
            'booking_time' => '10:00:00',
            'queue_number' => 'B-001',
            'total_price' => 35000,
            'status' => 'completed',
        ]);

        Booking::create([
            'booking_code' => 'BK-SITI-002',
            'user_id' => $customer2->id,
            'customer_name' => 'Siti Rahma',
            'customer_phone' => '089876543210',
            'service_category' => 'mua',
            'booking_date' => Carbon::today()->format('Y-m-d'),
            'booking_time' => '11:00:00',
            'queue_number' => 'M-001',
            'total_price' => 150000,
            'status' => 'completed',
        ]);

        // 1. Visit reports with search query 'Budi'
        $response = $this->actingAs($admin)->get(route('admin.reports.index', [
            'start_date' => Carbon::today()->startOfMonth()->format('Y-m-d'),
            'end_date' => Carbon::today()->format('Y-m-d'),
            'search' => 'Budi',
        ]));

        $response->assertOk();
        $response->assertSee('Cari Transaksi');
        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Siti Rahma');

        // 2. Visit reports with search query by booking code
        $responseCode = $this->actingAs($admin)->get(route('admin.reports.index', [
            'start_date' => Carbon::today()->startOfMonth()->format('Y-m-d'),
            'end_date' => Carbon::today()->format('Y-m-d'),
            'search' => 'BK-BUDI-001',
        ]));

        $responseCode->assertOk();
        $responseCode->assertSee('Budi Santoso');
        $responseCode->assertDontSee('Siti Rahma');

        // 3. Visit reports with search query 'M-001'
        $responseQueue = $this->actingAs($admin)->get(route('admin.reports.index', [
            'start_date' => Carbon::today()->startOfMonth()->format('Y-m-d'),
            'end_date' => Carbon::today()->format('Y-m-d'),
            'search' => 'M-001',
        ]));

        $responseQueue->assertOk();
        $responseQueue->assertSee('Siti Rahma');
        $responseQueue->assertDontSee('Budi Santoso');

        // 4. Search with non-existent keyword
        $responseNotFound = $this->actingAs($admin)->get(route('admin.reports.index', [
            'start_date' => Carbon::today()->startOfMonth()->format('Y-m-d'),
            'end_date' => Carbon::today()->format('Y-m-d'),
            'search' => 'KeywordTidakAda123',
        ]));

        $responseNotFound->assertOk();
        $responseNotFound->assertSee('Tidak ditemukan transaksi dengan kata kunci');
    }

    public function test_admin_can_assign_services_to_cashier_by_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Create barbershop & MUA services
        $cut = Service::create([
            'name' => 'Cukur Ganteng',
            'category' => 'barbershop',
            'price' => 35000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        $shave = Service::create([
            'name' => 'Gentlemen Shaving',
            'category' => 'barbershop',
            'price' => 25000,
            'duration_minutes' => 20,
            'is_active' => true,
        ]);

        $makeup = Service::create([
            'name' => 'Bridal Glam Makeup',
            'category' => 'mua',
            'price' => 450000,
            'duration_minutes' => 90,
            'is_active' => true,
        ]);

        $cashier = User::factory()->create([
            'name' => 'Kasir Satu',
            'role' => 'kasir',
            'service_category' => 'barbershop',
        ]);

        // 1. Visit edit cashier page
        $editResponse = $this->actingAs($admin)->get(route('admin.cashiers.edit', $cashier));
        $editResponse->assertOk();
        $editResponse->assertSee('Kategori Akun Kasir / Staf');
        $editResponse->assertSee('Barberman');
        $editResponse->assertSee('Cukur Ganteng');
        $editResponse->assertSee('Gentlemen Shaving');

        // 2. Admin updates cashier as Barberman with 2 barbershop services (attempting to also sneak an MUA service)
        $updateResponse = $this->actingAs($admin)->put(route('admin.cashiers.update', $cashier), [
            'name' => 'Kasir Satu Barber',
            'email' => $cashier->email,
            'phone' => '081234567890',
            'service_category' => 'barbershop',
            'services' => [$cut->id, $shave->id, $makeup->id], // $makeup should be filtered out
            'is_active' => 1,
            'work_days' => [1, 2, 3, 4, 5],
        ]);

        $updateResponse->assertRedirect(route('admin.cashiers.index'));
        $cashier->refresh();

        $this->assertEquals('barbershop', $cashier->service_category);
        $this->assertEquals(2, $cashier->services()->count());
        $this->assertTrue($cashier->services->contains($cut));
        $this->assertTrue($cashier->services->contains($shave));
        $this->assertFalse($cashier->services->contains($makeup));

        // 3. Admin switches cashier to MUA and assigns Bridal Glam Makeup
        $updateToMuaResponse = $this->actingAs($admin)->put(route('admin.cashiers.update', $cashier), [
            'name' => 'Kasir Satu MUA',
            'email' => $cashier->email,
            'phone' => '081234567890',
            'service_category' => 'mua',
            'services' => [$makeup->id, $cut->id], // $cut should be filtered out
            'is_active' => 1,
            'work_days' => [6, 0],
        ]);

        $updateToMuaResponse->assertRedirect(route('admin.cashiers.index'));
        $cashier->refresh();

        $this->assertEquals('mua', $cashier->service_category);
        $this->assertEquals(1, $cashier->services()->count());
        $this->assertTrue($cashier->services->contains($makeup));
        $this->assertFalse($cashier->services->contains($cut));

        // 4. Verify cashier index shows MUA badge
        $indexResponse = $this->actingAs($admin)->get(route('admin.cashiers.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('✨ MUA');

        // Verify edit page has the assigned service
        $editResponse = $this->actingAs($admin)->get(route('admin.cashiers.edit', $cashier));
        $editResponse->assertOk();
        $editResponse->assertSee($makeup->name);
    }

    public function test_booking_page_renders_clean_single_date_number_chips(): void
    {
        $customer = User::factory()->create(['role' => 'pelanggan']);

        $response = $this->actingAs($customer)->get('/pelanggan/booking/barbershop');
        $response->assertOk();

        $today = Carbon::today();
        $doubleDatePattern = $today->format('d') . ' ' . $today->format('d');
        // Ensure no doubled numbers like "05 05" in rendered view
        $response->assertDontSee($doubleDatePattern);

        // Verify "Tersedia" and "Tidak Tersedia" labels and dark mode classes
        $response->assertSee('Tersedia');
        $response->assertSee('Tidak Tersedia');
        $response->assertSee('dark:bg-sky-950/70');
    }

    public function test_customer_booking_respects_staff_assigned_services(): void
    {
        $customer = User::factory()->create(['role' => 'pelanggan']);

        $serviceCut = Service::create([
            'name' => 'Cukur Ganteng Khusus',
            'category' => 'barbershop',
            'price' => 35000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        $serviceShave = Service::create([
            'name' => 'Shaving Khusus',
            'category' => 'barbershop',
            'price' => 25000,
            'duration_minutes' => 20,
            'is_active' => true,
        ]);

        $staffAndi = Staff::create([
            'name' => 'Andi Spesialis Cukur',
            'role' => 'kapster',
            'phone' => '08129999001',
            'is_active' => true,
        ]);

        $staffBudi = Staff::create([
            'name' => 'Budi Spesialis Shave',
            'role' => 'kapster',
            'phone' => '08129999002',
            'is_active' => true,
        ]);

        $cashierAndi = User::factory()->create([
            'name' => 'Andi Spesialis Cukur',
            'role' => 'kasir',
            'service_category' => 'barbershop',
            'work_days' => [1, 2, 3, 4, 5, 6, 0],
        ]);
        $cashierAndi->services()->sync([$serviceCut->id]);

        $cashierBudi = User::factory()->create([
            'name' => 'Budi Spesialis Shave',
            'role' => 'kasir',
            'service_category' => 'barbershop',
            'work_days' => [1, 2, 3, 4, 5, 6, 0],
        ]);
        $cashierBudi->services()->sync([$serviceShave->id]);

        // 1. Visit booking create page: staffList contains service_ids
        $resPage = $this->actingAs($customer)->get('/pelanggan/booking/barbershop');
        $resPage->assertOk();
        $resPage->assertViewHas('staffList', function ($list) use ($serviceCut, $serviceShave) {
            $andi = collect($list)->firstWhere('name', 'Andi Spesialis Cukur');
            $budi = collect($list)->firstWhere('name', 'Budi Spesialis Shave');
            return in_array($serviceCut->id, $andi['service_ids'])
                && !in_array($serviceShave->id, $andi['service_ids'])
                && in_array($serviceShave->id, $budi['service_ids'])
                && !in_array($serviceCut->id, $budi['service_ids']);
        });

        // Pick next Monday for booking
        $bookingDate = Carbon::today()->next(Carbon::MONDAY)->format('Y-m-d');

        // 2. Customer attempts to book Cukur Cut with Budi (who only does Shaving) -> fails with error
        $resInvalid = $this->actingAs($customer)->post(route('pelanggan.booking.store'), [
            'category' => 'barbershop',
            'services' => [$serviceCut->id],
            'staff_id' => $staffBudi->id,
            'booking_date' => $bookingDate,
            'booking_time' => '13:00',
        ]);

        $resInvalid->assertSessionHasErrors('staff_id');

        // 3. Customer books Cukur Cut with Andi (who does Cukur Cut) -> succeeds!
        $resValid = $this->actingAs($customer)->post(route('pelanggan.booking.store'), [
            'category' => 'barbershop',
            'services' => [$serviceCut->id],
            'staff_id' => $staffAndi->id,
            'booking_date' => $bookingDate,
            'booking_time' => '14:00',
        ]);

        $resValid->assertRedirect(route('pelanggan.order.index'));
        $this->assertDatabaseHas('bookings', [
            'user_id' => $customer->id,
            'staff_id' => $staffAndi->id,
            'service_category' => 'barbershop',
            'booking_date' => $bookingDate . ' 00:00:00',
        ]);
    }

    public function test_history_and_profile_page_ui_updates(): void
    {
        $customer = User::factory()->create([
            'role' => 'pelanggan',
            'name' => 'Budi Pelanggan',
        ]);

        $booking = Booking::create([
            'booking_code' => 'BK-TEST999',
            'user_id' => $customer->id,
            'service_category' => 'barbershop',
            'booking_date' => '2026-11-01',
            'booking_time' => '10:00',
            'queue_number' => 'B-001',
            'total_price' => 35000,
            'status' => 'completed',
        ]);

        // 1. Riwayat order page assertions
        $resHistory = $this->actingAs($customer)->get(route('pelanggan.history.index'));
        $resHistory->assertOk();
        $resHistory->assertSee('Riwayat Order');
        $resHistory->assertDontSee('Riwayat Transaksi');
        // Booking code should not be displayed on card
        $resHistory->assertDontSee('#BK-TEST999');

        // 2. Profile page assertions
        $resProfile = $this->actingAs($customer)->get(route('pelanggan.profile.edit'));
        $resProfile->assertOk();
        // ID akun pelanggan (#ID-...) should not be displayed
        $idPattern = '#ID-' . str_pad($customer->id, 4, '0', STR_PAD_LEFT);
        $resProfile->assertDontSee($idPattern);
        // Smooth accordion transition classes should be present
        $resProfile->assertSee('grid-rows-[1fr]');
        $resProfile->assertSee('grid-rows-[0fr]');
        $resProfile->assertSee('transition-all duration-300 ease-in-out');
    }
}
