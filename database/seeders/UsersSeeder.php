<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userData = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ],
            [
                'name' => 'kasir',
                'email' => 'kasir@example.com',
                'password' => bcrypt('password'),
                'role' => 'kasir'
            ],
            [
                'name' => 'pelanggan',
                'email' => 'pelanggan@example.com',
                'password' => bcrypt('password'),
                'role' => 'pelanggan'
            ]

        ];

        foreach ($userData as $user) {
            User::create($user);
        }

        
    }
}
