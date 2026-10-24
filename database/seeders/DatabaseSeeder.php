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
        // Barbershop Operating Hours
        for ($day = 1; $day <= 6; $day++) {
            OperatingHour::create([
                'category'              => 'barbershop',
                'day_of_week'           => $day,
                'open_time'             => '09:00',
                'close_time'            => '21:00',
                'is_closed'             => false,
                'max_bookings_per_slot' => 5,
            ]);
        }
        OperatingHour::create([
            'category'              => 'barbershop',
            'day_of_week'           => 0,
            'open_time'             => '09:00',
            'close_time'            => '18:00',
            'is_closed'             => true,
            'max_bookings_per_slot' => 5,
        ]);

        // MUA Operating Hours (tends to have fewer slots per hour than barbershop)
        for ($day = 1; $day <= 6; $day++) {
            OperatingHour::create([
                'category'              => 'mua',
                'day_of_week'           => $day,
                'open_time'             => '09:00',
                'close_time'            => '21:00',
                'is_closed'             => false,
                'max_bookings_per_slot' => 2,
            ]);
        }
        OperatingHour::create([
            'category'              => 'mua',
            'day_of_week'           => 0,
            'open_time'             => '09:00',
            'close_time'            => '18:00',
            'is_closed'             => true,
            'max_bookings_per_slot' => 2,
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

        // ── Category Contents (Explanation & Information) ────────
        \App\Models\CategoryContent::create([
            'category'      => 'barbershop',
            'title'         => 'Grooming Lab Barbershop',
            'tagline'       => 'Perawatan Rambut & Grooming Pria Maskulin Modern',
            'description'   => "Grooming Lab Barbershop menghadirkan pengalaman potong rambut pria bintang lima dengan sentuhan kapster profesional berpengalaman. Kami memadukan teknik gunting presisi, fade bertingkat, hot towel treatment, dan styling pomade berkualitas tinggi untuk memastikan penampilan maskulin terbaik Anda setiap saat.\n\nSetiap sesi layanan dirancang untuk kenyamanan maksimal dengan ruang ber-AC dingin, kursi barbershop ergonomis, serta fasilitas relaksasi pijat kepala dan bahu.",
            'facilities'    => "Kapster Profesional & Berpengalaman\nKursi Barber Ergonomis & Nyaman\nHot Towel Treatment & Pijat Relaksasi\nProduk Styling Pomade & Shaving Impor Bebas Alkohol\nRuangan Full AC & Free Wi-Fi Berkecepatan Tinggi\nGratis Minuman Kopi & Mineral Water",
            'terms'         => "Disarankan datang 10 menit sebelum slot jam reservasi Anda.\nJika nomor antrian Anda terlewat, nomor antrian tidak hangus namun status akan diubah sementara menjadi Dilewati.\nHarap sampaikan referensi model rambut atau preferensi gaya sebelum kapster memulai pemotongan.",
            'is_active'     => true,
        ]);

        \App\Models\CategoryContent::create([
            'category'      => 'mua',
            'title'         => 'Grooming Lab MUA Studio',
            'tagline'       => 'Tata Rias Cantik, Flawless, & Elegan untuk Hari Spesial',
            'description'   => "Grooming Lab MUA Studio adalah studio tata rias profesional yang siap menyempurnakan penampilan Anda di setiap momen berharga. Mulai dari riasan wisuda yang fresh dan tahan seharian, pesta formal yang glamor, pre-wedding outdoor yang awet, hingga riasan pengantin (Bridal) yang memukau.\n\nTim MUA bersertifikat kami hanya menggunakan produk kosmetik premium original (high-end brands) yang ramah untuk semua jenis kulit, termasuk kulit sensitif dan acne-prone.",
            'facilities'    => "Makeup Artist (MUA) Profesional Bersertifikat\nKosmetik Premium 100% Original & Hipoalergenik\nPrivate Makeup Room dengan Pencahayaan Ring Light Standar Studio\nKonsultasi Tone Kulit & Gaya Riasan Sebelum Dimulai\nFree Touch-Up Kit Sederhana untuk Acara Penting\nRuangan Rias Nyaman & Full AC",
            'terms'         => "Harap hadir dengan wajah bersih bebas makeup tebal.\nDisarankan membawa foto referensi riasan atau pakaian yang akan dikenakan.\nBeri tahu MUA mengenai alergi atau sensitivitas kulit tertentu sebelum aplikasi produk.",
            'is_active'     => true,
        ]);
    }
}
