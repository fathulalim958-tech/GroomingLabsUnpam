<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_from_the_staff_form_and_reach_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@test.test',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.test',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);
        $this->get('/admin')->assertOk()->assertSee('Dashboard Admin');
    }

    public function test_cashier_cannot_open_admin_dashboard(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);

        $this->actingAs($cashier)->get('/admin')->assertForbidden();
    }

    public function test_admin_can_create_a_cashier_account_that_logs_in_with_username(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post('/admin/employees', [
            'name' => 'Budi Santoso',
            'username' => 'budi.santoso',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect('/admin');

        $this->assertDatabaseHas('users', [
            'name' => 'Budi Santoso',
            'username' => 'budi.santoso',
            'role' => 'cashier',
        ]);

        $this->post('/logout');
        $this->post('/login', ['login' => 'budi.santoso', 'password' => 'password'])
            ->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs(User::where('username', 'budi.santoso')->first());
    }
}
