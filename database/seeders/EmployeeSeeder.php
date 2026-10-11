<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ([
            ['name' => 'Budi Santoso', 'position' => 'Hair Stylist'],
            ['name' => 'Rina Andini', 'position' => 'MUA Artist'],
            ['name' => 'Dimas Rizky', 'position' => 'Hair Stylist'],
        ] as $employee) {
            Employee::firstOrCreate(
                ['name' => $employee['name']],
                ['position' => $employee['position']],
            );
        }
    }
}
