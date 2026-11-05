<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Holiday;
use App\Models\OperatingHour;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use App\Services\HolidayService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashierScheduleAndHolidayTest extends TestCase
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
     * Test 1: Admin can configure cashier work schedule manually.
     */
    public function test_admin_can_set_cashier_schedule_manually(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Staff::create(['name' => 'Kasir Senin Jumat', 'role' => 'kapster', 'is_active' => true]);
        Staff::create(['name' => 'Kasir Sabtu Saja', 'role' => 'kapster', 'is_active' => true]);

        // 1. Create cashier with Senin-Jumat schedule (days 1, 2, 3, 4, 5)
        $response = $this->actingAs($admin)->post(route('admin.cashiers.store'), [
            'name' => 'Kasir Senin Jumat',
            'email' => 'kasir1@example.com',
            'phone' => '081234567891',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'work_days' => [1, 2, 3, 4, 5],
        ]);

        $response->assertRedirect(route('admin.cashiers.index'));
        $cashier1 = User::where('email', 'kasir1@example.com')->first();
        $this->assertNotNull($cashier1);
        $this->assertEquals([1, 2, 3, 4, 5], $cashier1->work_days);
        $this->assertEquals('Senin - Jumat', $cashier1->work_days_formatted);
        $this->assertTrue($cashier1->isAvailableOnDay(1)); // Senin
        $this->assertTrue($cashier1->isAvailableOnDay(5)); // Jumat
        $this->assertFalse($cashier1->isAvailableOnDay(6)); // Sabtu
        $this->assertFalse($cashier1->isAvailableOnDay(0)); // Minggu

        // 2. Create cashier with Sabtu schedule (day 6)
        $this->actingAs($admin)->post(route('admin.cashiers.store'), [
            'name' => 'Kasir Sabtu Saja',
            'email' => 'kasir2@example.com',
            'phone' => '081234567892',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'work_days' => [6],
        ]);

        $cashier2 = User::where('email', 'kasir2@example.com')->first();
        $this->assertNotNull($cashier2);
        $this->assertEquals([6], $cashier2->work_days);
        $this->assertEquals('Sabtu', $cashier2->work_days_formatted);
        $this->assertTrue($cashier2->isAvailableOnDay(6)); // Sabtu
        $this->assertFalse($cashier2->isAvailableOnDay(1)); // Senin

        // 3. Quick schedule update endpoint
        $resSchedule = $this->actingAs($admin)->put(route('admin.cashiers.schedule', $cashier2), [
            'work_days' => [6, 0], // Weekend
        ]);
        $resSchedule->assertSessionHas('success');
        $cashier2->refresh();
        $this->assertEquals([6, 0], $cashier2->work_days);
        $this->assertEquals('Sabtu & Minggu', $cashier2->work_days_formatted);
    }

    /**
     * Test 2: Unified navigation tabs present on both cashiers and staffs pages.
     */
    public function test_unified_navigation_tabs_present_on_cashiers_and_staffs(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['role' => 'kasir']);
        Staff::create(['name' => 'Budi Kapster', 'role' => 'kapster', 'is_active' => true]);

        // Cashiers page has tabs for both
        $resCashier = $this->actingAs($admin)->get(route('admin.cashiers.index'));
        $resCashier->assertStatus(200);
        $resCashier->assertSee('Akun &amp; Jadwal Kasir', false);
        $resCashier->assertSee('Data Staf (Kapster &amp; MUA)', false);

        // Staffs page has tabs for both
        $resStaff = $this->actingAs($admin)->get(route('admin.staffs.index'));
        $resStaff->assertStatus(200);
        $resStaff->assertSee('Akun &amp; Jadwal Kasir', false);
        $resStaff->assertSee('Data Staf (Kapster &amp; MUA)', false);
    }

    /**
     * Test 3: Tanggal Merah (National Holiday) automatically blocks customer booking.
     */
    public function test_tanggal_merah_automatically_blocks_booking(): void
    {
        $customer = User::factory()->create(['role' => 'pelanggan']);
        $service = Service::create([
            'category' => 'barbershop',
            'name' => 'Haircut Test',
            'price' => 50000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        // Pick 2026-08-17 (HUT RI - Proklamasi Kemerdekaan RI, which is a Monday)
        $holidayDate = '2026-08-17';
        $this->assertTrue(HolidayService::isHoliday($holidayDate));

        // 1. AJAX check-slots returns is_closed: true and is_holiday: true
        $resSlots = $this->actingAs($customer)->getJson(route('pelanggan.booking.check-slots', [
            'category' => 'barbershop',
            'date' => $holidayDate,
        ]));

        $resSlots->assertStatus(200);
        $resSlots->assertJson([
            'is_closed' => true,
            'is_holiday' => true,
        ]);
        $this->assertStringContainsString('Kemerdekaan', $resSlots->json('holiday_name'));

        // 2. Booking attempt on holiday date is rejected by backend validation
        $response = $this->actingAs($customer)->post(route('pelanggan.booking.store'), [
            'category' => 'barbershop',
            'services' => [$service->id],
            'booking_date' => $holidayDate,
            'booking_time' => '10:00',
        ]);

        $response->assertSessionHasErrors('booking_date');
        $this->assertEquals(0, Booking::whereDate('booking_date', $holidayDate)->count());
    }

    /**
     * Test 4: Admin can view holiday calendar and add custom holiday.
     */
    public function test_admin_can_view_holiday_calendar_and_add_custom_holiday(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // View calendar for August 2026
        $response = $this->actingAs($admin)->get(route('admin.operating-hours.index', [
            'month' => 8,
            'year' => 2026,
        ]));
        $response->assertStatus(200);
        $response->assertSee('Proklamasi Kemerdekaan');

        // Add custom holiday
        $resAdd = $this->actingAs($admin)->post(route('admin.operating-hours.holidays.store'), [
            'date' => '2026-08-20',
            'name' => 'Renovasi Studio Grooming',
            'description' => 'Toko tutup sementara',
        ]);

        $resAdd->assertRedirect(route('admin.operating-hours.index', ['month' => 8, 'year' => 2026]));
        $this->assertTrue(HolidayService::isHoliday('2026-08-20'));

        $customHoliday = Holiday::whereDate('date', '2026-08-20')->first();
        $this->assertNotNull($customHoliday);
        $this->assertEquals('Renovasi Studio Grooming', $customHoliday->name);

        // Delete custom holiday
        $resDelete = $this->actingAs($admin)->delete(route('admin.operating-hours.holidays.destroy', $customHoliday));
        $resDelete->assertSessionHas('success');
        $this->assertNull(Holiday::whereDate('date', '2026-08-20')->first());
    }

    /**
     * Test 5: Admin can update staff email and active status (radio buttons 1 or 0).
     */
    public function test_admin_can_update_staff_with_email_and_radio_active_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = Staff::create([
            'name' => 'Andi Pratama',
            'role' => 'kapster',
            'phone' => '08121111001',
            'email' => null,
            'is_active' => true,
        ]);

        // 1. Visit edit staff page
        $editRes = $this->actingAs($admin)->get(route('admin.staffs.edit', $staff));
        $editRes->assertStatus(200);
        $editRes->assertSee('Alamat Email');
        $editRes->assertSee('Status Keaktifan Staf');
        $editRes->assertSee('Aktif Bekerja');
        $editRes->assertSee('Cuti / Nonaktif');

        // 2. Update staff with email and set status to inactive (radio value '0')
        $updateRes = $this->actingAs($admin)->put(route('admin.staffs.update', $staff), [
            'name' => 'Andi Pratama Updated',
            'role' => 'kapster',
            'email' => 'andi.pratama@groominglab.com',
            'phone' => '081299998888',
            'is_active' => '0',
        ]);

        $updateRes->assertRedirect(route('admin.staffs.index'));
        $staff->refresh();
        $this->assertEquals('Andi Pratama Updated', $staff->name);
        $this->assertEquals('andi.pratama@groominglab.com', $staff->email);
        $this->assertEquals('081299998888', $staff->phone);
        $this->assertFalse($staff->is_active);

        // 3. Update staff status back to active (radio value '1')
        $activateRes = $this->actingAs($admin)->put(route('admin.staffs.update', $staff), [
            'name' => 'Andi Pratama Updated',
            'role' => 'kapster',
            'email' => 'andi.pratama@groominglab.com',
            'phone' => '081299998888',
            'is_active' => '1',
        ]);

        $activateRes->assertRedirect(route('admin.staffs.index'));
        $staff->refresh();
        $this->assertTrue($staff->is_active);

        // 4. Verify index page shows the email
        $indexRes = $this->actingAs($admin)->get(route('admin.staffs.index'));
        $indexRes->assertStatus(200);
        $indexRes->assertSee('andi.pratama@groominglab.com');
    }

    /**
     * Test 6: Cashier creation dropdown only shows available staff and hides assigned staff until deleted.
     */
    public function test_cashier_dropdown_filters_out_assigned_staff_and_restores_on_deletion(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff1 = Staff::create(['name' => 'Karyawan Satu', 'role' => 'kapster', 'is_active' => true]);
        $staff2 = Staff::create(['name' => 'Karyawan Dua', 'role' => 'mua', 'is_active' => true]);

        // 1. Initially both staff are available in create cashier dropdown
        $resCreate1 = $this->actingAs($admin)->get(route('admin.cashiers.create'));
        $resCreate1->assertStatus(200);
        $resCreate1->assertSee('Karyawan Satu');
        $resCreate1->assertSee('Karyawan Dua');

        // 2. Create cashier for Karyawan Satu
        $resStore = $this->actingAs($admin)->post(route('admin.cashiers.store'), [
            'name' => 'Karyawan Satu',
            'email' => 'karyawan1@example.com',
            'phone' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $resStore->assertRedirect(route('admin.cashiers.index'));

        // 3. Now only Karyawan Dua is in the create dropdown; Karyawan Satu is no longer selectable
        $resCreate2 = $this->actingAs($admin)->get(route('admin.cashiers.create'));
        $resCreate2->assertStatus(200);
        $resCreate2->assertDontSee('&gt;Karyawan Satu (', false);
        $resCreate2->assertSee('Karyawan Dua');

        // 4. Index shows Peran column header
        $resIndex = $this->actingAs($admin)->get(route('admin.cashiers.index'));
        $resIndex->assertStatus(200);
        $resIndex->assertSee('Peran');

        // 5. Delete cashier Karyawan Satu
        $cashier = User::where('email', 'karyawan1@example.com')->first();
        $this->assertNotNull($cashier);
        $resDelete = $this->actingAs($admin)->delete(route('admin.cashiers.destroy', $cashier));
        $resDelete->assertRedirect(route('admin.cashiers.index'));

        // 6. After cashier deletion, Karyawan Satu is available again in create cashier dropdown!
        $resCreate3 = $this->actingAs($admin)->get(route('admin.cashiers.create'));
        $resCreate3->assertStatus(200);
        $resCreate3->assertSee('Karyawan Satu');
    }

    /**
     * Test 7: Report search works without phone_number column error.
     */
    public function test_report_search_filters_without_error(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'pelanggan', 'name' => 'Alim Customer', 'phone' => '0812345678']);

        $res = $this->actingAs($admin)->get(route('admin.reports.index', ['search' => 'alim']));
        $res->assertStatus(200);
    }

    /**
     * Test 8: Booking page order, Indonesian day names, and staff selection.
     */
    public function test_booking_page_order_and_staff_selection(): void
    {
        $customer = User::factory()->create(['role' => 'pelanggan']);
        $service = Service::create([
            'category' => 'barbershop',
            'name' => 'Haircut Gentleman Cut',
            'price' => 35000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        $kapster = Staff::create([
            'name' => 'Kapster Budi',
            'role' => 'kapster',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        // 1. Visit booking form
        $res = $this->actingAs($customer)->get(route('pelanggan.booking.create', 'barbershop'));
        $res->assertStatus(200);

        // Verify order of sections in the view
        $content = $res->getContent();
        preg_match('/1\.\s*Pilih Hari\s*(&amp;|&)\s*Tanggal/i', $content, $m1, PREG_OFFSET_CAPTURE);
        preg_match('/2\.\s*Pilih Layanan/i', $content, $m2, PREG_OFFSET_CAPTURE);
        preg_match('/3\.\s*Pilih\s+Kapster/i', $content, $m3, PREG_OFFSET_CAPTURE);
        preg_match('/4\.\s*Pilih Sesi Jam/i', $content, $m4, PREG_OFFSET_CAPTURE);
        preg_match('/5\.\s*Catatan Khusus/i', $content, $m5, PREG_OFFSET_CAPTURE);

        $posStep1 = $m1[0][1] ?? false;
        $posStep2 = $m2[0][1] ?? false;
        $posStep3 = $m3[0][1] ?? false;
        $posStep4 = $m4[0][1] ?? false;
        $posStep5 = $m5[0][1] ?? false;

        $this->assertNotFalse($posStep1);
        $this->assertNotFalse($posStep2);
        $this->assertNotFalse($posStep3);
        $this->assertNotFalse($posStep4);
        $this->assertNotFalse($posStep5);

        $this->assertTrue($posStep1 < $posStep2, 'Step 1 (Hari & Tanggal) must precede Step 2 (Layanan)');
        $this->assertTrue($posStep2 < $posStep3, 'Step 2 (Layanan) must precede Step 3 (Kapster)');
        $this->assertTrue($posStep3 < $posStep4, 'Step 3 (Kapster) must precede Step 4 (Sesi Jam)');
        $this->assertTrue($posStep4 < $posStep5, 'Step 4 (Sesi Jam) must precede Step 5 (Catatan Khusus)');

        // Verify staff name appears in staff selection
        $res->assertSee('Kapster Budi');
        $res->assertSee('Siapa Saja yang Tersedia');

        // 2. Customer submits booking with selected kapster
        $tomorrow = \Illuminate\Support\Carbon::tomorrow()->toDateString();
        // Skip if tomorrow is holiday or closed
        $postRes = $this->actingAs($customer)->post(route('pelanggan.booking.store'), [
            'category' => 'barbershop',
            'services' => [$service->id],
            'staff_id' => $kapster->id,
            'booking_date' => $tomorrow,
            'booking_time' => '10:00',
            'notes' => 'Tolong potongan rapi',
        ]);

        $postRes->assertRedirect(route('pelanggan.order.index'));
        $booking = Booking::where('user_id', $customer->id)->latest()->first();
        $this->assertNotNull($booking);
        $this->assertEquals($kapster->id, $booking->staff_id);
        $this->assertEquals('Kapster Budi', $booking->staff->name);
    }
}
