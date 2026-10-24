<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_request_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
        $response->assertSee('Lupa Kata Sandi?');
    }

    public function test_verification_code_can_be_requested(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        $response->assertRedirect(route('password.verify.form'));
        $this->assertEquals($user->email, session('password_reset_email'));

        // Ensure token record exists in DB
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $user->email,
        ]);
    }

    public function test_verification_code_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $this->withSession(['password_reset_email' => $user->email]);

        $response = $this->get('/forgot-password/verify');

        $response->assertStatus(200);
        $response->assertSee('Masukkan Kode Verifikasi');
        $response->assertSee($user->email);
    }

    public function test_password_can_be_reset_with_valid_code(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
        ]);

        $code = '123456';
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($code), 'created_at' => now()]
        );

        // 1. Submit valid code
        $verifyResponse = $this->withSession(['password_reset_email' => $user->email])
            ->post('/forgot-password/verify', ['code' => $code]);

        $verifyResponse->assertRedirect(route('password.reset.form'));
        $this->assertTrue(session('password_reset_verified'));

        // 2. Submit new password
        $resetResponse = $this->withSession([
            'password_reset_email' => $user->email,
            'password_reset_verified' => true,
        ])->post('/forgot-password/reset', [
            'password' => 'new-password123',
            'password_confirmation' => 'new-password123',
        ]);

        $resetResponse->assertRedirect(route('login'));

        $user->refresh();
        $this->assertTrue(Hash::check('new-password123', $user->password));
    }

    public function test_invalid_verification_code_is_rejected(): void
    {
        $user = User::factory()->create();

        $code = '123456';
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($code), 'created_at' => now()]
        );

        $response = $this->withSession(['password_reset_email' => $user->email])
            ->post('/forgot-password/verify', ['code' => '999999']);

        $response->assertSessionHasErrors('code');
    }
}
