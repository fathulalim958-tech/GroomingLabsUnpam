<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_access_kasir_dashboard(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($admin)
            ->get('/dashboard/kasir')
            ->assertForbidden();
    }

    public function test_kasir_cannot_access_admin_dashboard(): void
    {
        $kasir = User::factory()->create([
            'name' => 'Kasir',
            'email' => 'kasir@example.com',
            'role' => 'kasir',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($kasir)
            ->get('/dashboard/admin')
            ->assertForbidden();
    }

    public function test_pelanggan_cannot_access_admin_dashboard(): void
    {
        $pelanggan = User::factory()->create([
            'name' => 'Pelanggan',
            'email' => 'pelanggan@example.com',
            'role' => 'pelanggan',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($pelanggan)
            ->get('/dashboard/admin')
            ->assertForbidden();
    }
}
