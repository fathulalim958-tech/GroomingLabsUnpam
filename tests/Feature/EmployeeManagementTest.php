<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Employee;
use App\Models\User;
use Database\Seeders\EmployeeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_existing_employees_are_seeded_once_with_current_positions(): void
    {
        $this->seed(EmployeeSeeder::class);
        $this->seed(EmployeeSeeder::class);

        $this->assertDatabaseCount('employees', 3);
        $this->assertDatabaseHas('employees', ['name' => 'Budi Santoso', 'position' => 'Hair Stylist']);
        $this->assertDatabaseHas('employees', ['name' => 'Rina Andini', 'position' => 'MUA Artist']);
        $this->assertDatabaseHas('employees', ['name' => 'Dimas Rizky', 'position' => 'Hair Stylist']);
    }

    public function test_admin_can_create_an_employee_account_and_profile(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.karyawan.store'), [
                'name' => 'Maya Putri',
                'username' => 'maya.putri',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'position' => 'MUA Artist',
            ])
            ->assertRedirect(route('admin.karyawan'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Maya Putri',
            'username' => 'maya.putri',
            'role' => 'kasir',
        ]);
        $this->assertDatabaseHas('employees', [
            'name' => 'Maya Putri',
            'position' => 'MUA Artist',
        ]);
    }

    public function test_admin_can_update_an_existing_employee_profile(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $employee = Employee::create(['name' => 'Budi Santoso', 'position' => 'Hair Stylist']);

        $this->actingAs($admin)
            ->put(route('admin.karyawan.update', $employee), [
                'name' => 'Budi Santoso Updated',
                'position' => 'MUA Artist',
            ])
            ->assertRedirect(route('admin.karyawan'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'name' => 'Budi Santoso Updated',
            'position' => 'MUA Artist',
        ]);
    }

    public function test_kasir_cannot_manage_employee_profiles(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($kasir)
            ->get(route('admin.karyawan'))
            ->assertForbidden();
    }

    public function test_employee_can_log_in_with_username(): void
    {
        $employee = User::factory()->create([
            'username' => 'staff.login',
            'email' => 'staff.login@ruangrias.local',
            'password' => 'password123',
            'role' => 'kasir',
        ]);

        $this->post(route('login'), [
            'email' => 'staff.login',
            'password' => 'password123',
        ])
            ->assertRedirect('/dashboard/kasir');

        $this->assertAuthenticatedAs($employee);
    }

    public function test_admin_can_delete_employee_account_without_deleting_bookings(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $employeeUser = User::factory()->create(['role' => 'kasir']);
        $employee = Employee::create([
            'user_id' => $employeeUser->id,
            'name' => $employeeUser->name,
            'position' => 'Hair Stylist',
        ]);
        $booking = Booking::create([
            'customer_name' => 'Pelanggan Lama',
            'category' => 'barber',
            'service_name' => 'Fast Haircut',
            'service_price' => 25000,
            'staff_name' => $employee->name,
            'cashier_id' => $employeeUser->id,
            'appointment_at' => now(),
            'status' => 'completed',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.karyawan.destroy', $employee))
            ->assertRedirect(route('admin.karyawan'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
        $this->assertDatabaseMissing('users', ['id' => $employeeUser->id]);
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'cashier_id' => null,
            'status' => 'completed',
        ]);
    }

    public function test_non_admin_cannot_delete_an_employee(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $employee = Employee::create(['name' => 'Rina Andini', 'position' => 'MUA Artist']);

        $this->actingAs($kasir)
            ->delete(route('admin.karyawan.destroy', $employee))
            ->assertForbidden();

        $this->assertDatabaseHas('employees', ['id' => $employee->id]);
    }
}
