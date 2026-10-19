<?php

namespace Database\Seeders;

use App\Models\OperatingHour;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── Users ──────────────────────────────────────────────
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@barbershop.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'phone'    => '081234567890',
            'is_active'=> true,
        ]);

        User::create([
            'name'     => 'Kasir Utama',
            'email'    => 'kasir@barbershop.com',
            'password' => Hash::make('password'),
            'role'     => 'kasir',
            'phone'    => '081234567891',
            'is_active'=> true,
        ]);

        User::create([
            'name'     => 'John Customer',
            'email'    => 'customer@example.com',
            'password' => Hash::make('password'),
            'role'     => 'pelanggan',
            'phone'    => '081234567892',
            'is_active'=> true,
        ]);

        // ── Barbershop Services ────────────────────────────────
        Service::create([
            'name'             => 'Gentlemen Cut',
            'category'         => 'barbershop',
            'price'            => 35000,
            'duration_minutes' => 30,
            'description'      => 'Potongan rambut pria klasik dan modern dengan teknik fade, pompadour, atau undercut sesuai selera.',
            'is_active'        => true,
        ]);

        Service::create([
            'name'             => 'Hair Wash & Styling',
            'category'         => 'barbershop',
            'price'            => 20000,
            'duration_minutes' => 15,
            'description'      => 'Cuci rambut dengan shampoo premium, pijat kepala relaksasi, dan penataan rambut dengan pomade.',
            'is_active'        => true,
        ]);

        Service::create([
            'name'             => 'Gentlemen Shaving',
            'category'         => 'barbershop',
            'price'            => 25000,
            'duration_minutes' => 20,
            'description'      => 'Cukur jenggot dan kumis presisi dengan handuk hangat dan aftershave menyegarkan.',
            'is_active'        => true,
        ]);

        Service::create([
            'name'             => 'Hair Coloring',
            'category'         => 'barbershop',
            'price'            => 150000,
            'duration_minutes' => 60,
            'description'      => 'Pewarnaan rambut trendi dengan cat berkualitas tinggi bebas amonia.',
            'is_active'        => true,
        ]);

        Service::create([
            'name'             => 'Hair Spa & Treatment',
            'category'         => 'barbershop',
            'price'            => 75000,
            'duration_minutes' => 45,
            'description'      => 'Perawatan rambut mendalam dengan vitamin, creambath, dan pijat punggung relaksasi.',
            'is_active'        => true,
        ]);

        // ── MUA Services ───────────────────────────────────────
        Service::create([
            'name'             => 'Party Make Up',
            'category'         => 'mua',
            'price'            => 250000,
            'duration_minutes' => 60,
            'description'      => 'Tata rias glamor dan elegan untuk pesta pernikahan, gala dinner, atau event formal.',
            'is_active'        => true,
        ]);

        Service::create([
            'name'             => 'Graduation Make Up',
            'category'         => 'mua',
            'price'            => 200000,
            'duration_minutes' => 45,
            'description'      => 'Tata rias fresh dan tahan lama sepanjang hari wisuda serta sesi foto akademik.',
            'is_active'        => true,
        ]);

        Service::create([
            'name'             => 'Bridal Make Up',
            'category'         => 'mua',
            'price'            => 500000,
            'duration_minutes' => 90,
            'description'      => 'Tata rias pengantin lengkap (Traditional / Modern) termasuk retouch dan soft touch up.',
            'is_active'        => true,
        ]);

        Service::create([
            'name'             => 'Natural Daily Make Up',
            'category'         => 'mua',
            'price'            => 150000,
            'duration_minutes' => 30,
            'description'      => 'Riasan natural glowing (No-Makeup Makeup Look) yang memancarkan kecantikan alami.',
            'is_active'        => true,
        ]);

        Service::create([
            'name'             => 'Pre-Wedding Make Up',
            'category'         => 'mua',
            'price'            => 350000,
            'duration_minutes' => 75,
            'description'      => 'Tata rias khusus sesi pemotretan indoor maupun outdoor dengan ketahanan ekstra terhadap cuaca.',
            'is_active'        => true,
        ]);

        // ── Operating Hours (0 = Sunday, 1 = Monday, ..., 6 = Saturday) ──────
        // Monday (1) to Saturday (6)
        for ($day = 1; $day <= 6; $day++) {
            OperatingHour::create([
                'day_of_week'           => $day,
                'open_time'             => '09:00',
                'close_time'            => '21:00',
                'is_closed'             => false,
                'max_bookings_per_slot' => 5,
            ]);
        }

        // Sunday (0) - Closed
        OperatingHour::create([
            'day_of_week'           => 0,
            'open_time'             => '09:00',
            'close_time'            => '18:00',
            'is_closed'             => true,
            'max_bookings_per_slot' => 5,
        ]);

        // ── Staff ──────────────────────────────────────────────
        Staff::create([
            'name'      => 'Andi Pratama',
            'role'      => 'kapster',
            'phone'     => '08121111001',
            'is_active' => true,
        ]);

        Staff::create([
            'name'      => 'Budi Santoso',
            'role'      => 'kapster',
            'phone'     => '08121111002',
            'is_active' => true,
        ]);

        Staff::create([
            'name'      => 'Citra Dewi',
            'role'      => 'mua',
            'phone'     => '08121111003',
            'is_active' => true,
        ]);

        Staff::create([
            'name'      => 'Diana Putri',
            'role'      => 'mua',
            'phone'     => '08121111004',
            'is_active' => true,
        ]);
    }
}
