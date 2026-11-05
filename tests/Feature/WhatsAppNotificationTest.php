<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\OperatingHour;
use App\Models\Service;
use App\Models\User;
use App\Services\WhatsAppNotificationService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WhatsAppNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create operating hours
        for ($i = 0; $i <= 6; $i++) {
            OperatingHour::create([
                'category' => 'barbershop',
                'day_of_week' => $i,
                'open_time' => '09:00',
                'close_time' => '21:00',
                'is_closed' => false,
                'max_bookings_per_slot' => 5,
            ]);
        }
    }

    public function test_whatsapp_notification_is_sent_to_admin_when_booking_is_created(): void
    {
        Http::fake([
            'https://api.fonnte.com/send' => Http::response([
                'status' => true,
                'target' => ['085975455515'],
                'detail' => 'success',
            ], 200),
        ]);

        $customer = User::factory()->create([
            'role' => 'pelanggan',
            'name' => 'Fathul Customer',
            'phone' => '081234567899',
        ]);

        $service = Service::create([
            'name' => 'Gentlemen Cut VIP',
            'category' => 'barbershop',
            'price' => 50000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        $bookingDate = Carbon::today()->addDays(2)->format('Y-m-d');

        $response = $this->actingAs($customer)->post(route('pelanggan.booking.store'), [
            'category' => 'barbershop',
            'services' => [$service->id],
            'booking_date' => $bookingDate,
            'booking_time' => '10:00',
            'notes' => 'Tolong potongan rapi untuk acara',
        ]);

        $response->assertRedirect(route('pelanggan.order.index'));

        // Assert that HTTP request to Fonnte was sent
        Http::assertSent(function ($request) use ($service) {
            $isFonnteUrl = str_contains($request->url(), 'api.fonnte.com/send');
            $hasAuthHeader = $request->hasHeader('Authorization');
            $data = $request->data();

            $targetMatches = isset($data['target']) && str_contains($data['target'], '085975455515');
            $messageContainsService = isset($data['message']) && str_contains($data['message'], $service->name);
            $messageContainsCustomer = isset($data['message']) && str_contains($data['message'], 'Fathul Customer');
            $messageContainsNotes = isset($data['message']) && str_contains($data['message'], 'Tolong potongan rapi untuk acara');

            return $isFonnteUrl && $hasAuthHeader && $targetMatches && $messageContainsService && $messageContainsCustomer && $messageContainsNotes;
        });
    }

    public function test_booking_still_succeeds_even_if_fonnte_api_fails(): void
    {
        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => false, 'message' => 'device disconnected'], 500),
        ]);

        $customer = User::factory()->create([
            'role' => 'pelanggan',
            'name' => 'Budi Testing',
            'phone' => '081234567800',
        ]);

        $service = Service::create([
            'name' => 'Hair Wash Only',
            'category' => 'barbershop',
            'price' => 20000,
            'duration_minutes' => 15,
            'is_active' => true,
        ]);

        $bookingDate = Carbon::today()->addDays(2)->format('Y-m-d');

        $response = $this->actingAs($customer)->post(route('pelanggan.booking.store'), [
            'category' => 'barbershop',
            'services' => [$service->id],
            'booking_date' => $bookingDate,
            'booking_time' => '11:00',
        ]);

        // Customer booking should still be created and redirected successfully
        $response->assertRedirect(route('pelanggan.order.index'));
        $this->assertDatabaseHas('bookings', [
            'user_id' => $customer->id,
            'service_category' => 'barbershop',
        ]);
    }
}

