<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }

    public function test_pelanggan_can_upload_and_delete_avatar(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $user = User::factory()->create([
            'role' => 'pelanggan',
            'phone' => '081234567890',
        ]);

        $file = \Illuminate\Http\UploadedFile::fake()->image('customer_avatar.jpg', 300, 300);

        $response = $this
            ->actingAs($user)
            ->patch(route('pelanggan.profile.update'), [
                'name' => 'Pelanggan Foto',
                'email' => 'pelanggan_foto@test.com',
                'phone' => '081234567890',
                'avatar' => $file,
            ]);

        $response->assertSessionHasNoErrors()->assertRedirect(route('pelanggan.profile.edit'));

        $user->refresh();
        $this->assertNotNull($user->avatar);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($user->avatar);

        // Delete avatar test
        $responseDelete = $this
            ->actingAs($user)
            ->patch(route('pelanggan.profile.update'), [
                'name' => 'Pelanggan Foto',
                'email' => 'pelanggan_foto@test.com',
                'phone' => '081234567890',
                'delete_avatar' => 1,
            ]);

        $responseDelete->assertSessionHasNoErrors();
        $user->refresh();
        $this->assertNull($user->avatar);
    }

    public function test_cashier_can_upload_and_delete_avatar(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $cashier = User::factory()->create([
            'role' => 'kasir',
            'phone' => '08987654321',
        ]);

        $file = \Illuminate\Http\UploadedFile::fake()->image('kasir_avatar.png', 400, 400);

        $response = $this
            ->actingAs($cashier)
            ->put(route('kasir.reports.update-profile'), [
                'name' => 'Kasir Foto Profil',
                'phone' => '08987654321',
                'avatar' => $file,
            ]);

        $response->assertSessionHasNoErrors()->assertRedirect(route('kasir.reports.index'));

        $cashier->refresh();
        $this->assertNotNull($cashier->avatar);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($cashier->avatar);

        // Delete avatar test
        $responseDelete = $this
            ->actingAs($cashier)
            ->put(route('kasir.reports.update-profile'), [
                'name' => 'Kasir Foto Profil',
                'phone' => '08987654321',
                'delete_avatar' => 1,
            ]);

        $responseDelete->assertSessionHasNoErrors();
        $cashier->refresh();
        $this->assertNull($cashier->avatar);
    }

    public function test_admin_customer_list_renders_customer_avatar(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create([
            'role' => 'pelanggan',
            'avatar' => 'avatars/sample.jpg',
            'name' => 'Pelanggan Avatar Display',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.customers.index'));
        $response->assertOk();
        $response->assertSee('storage/avatars/sample.jpg');
        $response->assertSee('Pelanggan Avatar Display');
    }

    public function test_admin_cashier_list_renders_cashier_avatar(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $cashier = User::factory()->create([
            'role' => 'kasir',
            'avatar' => 'avatars/sample_cashier.jpg',
            'name' => 'Kasir Avatar Display',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.cashiers.index'));
        $response->assertOk();
        $response->assertSee('storage/avatars/sample_cashier.jpg');
        $response->assertSee('Kasir Avatar Display');
    }

    public function test_cashier_order_list_renders_customer_avatar(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $customer = User::factory()->create([
            'role' => 'pelanggan',
            'avatar' => 'avatars/booking_cust.jpg',
            'name' => 'Pelanggan Booking Avatar',
        ]);

        $service = \App\Models\Service::create([
            'name' => 'Haircut Test Avatar',
            'category' => 'barbershop',
            'price' => 50000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        $booking = \App\Models\Booking::create([
            'booking_code' => 'BK-TEST-001',
            'user_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone,
            'service_category' => 'barbershop',
            'booking_date' => \Illuminate\Support\Carbon::tomorrow()->toDateString(),
            'booking_time' => '10:00',
            'queue_number' => 'B-001',
            'total_price' => 50000,
            'status' => 'waiting',
        ]);
        $booking->items()->create([
            'service_id' => $service->id,
            'price' => 50000,
        ]);

        $response = $this->actingAs($cashier)->get(route('kasir.orders.index'));
        $response->assertOk();
        $response->assertSee('storage/avatars/booking_cust.jpg');
        $response->assertSee('Pelanggan Booking Avatar');
    }
}
