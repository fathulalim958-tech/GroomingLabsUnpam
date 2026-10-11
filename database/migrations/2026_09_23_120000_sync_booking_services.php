<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $barberServices = [
            ['name' => 'Fast Hair Cut', 'description' => 'Potongan rambut cepat dan rapi.', 'duration_minutes' => 30, 'price' => 25000],
            ['name' => 'Rileks Ganteng', 'description' => 'Perawatan rambut dan styling santai.', 'duration_minutes' => 45, 'price' => 35000],
            ['name' => 'Full Grooming', 'description' => 'Paket grooming lengkap untuk tampilan terbaik.', 'duration_minutes' => 60, 'price' => 50000],
        ];

        $legacyBarberNames = ['Classic Cut', 'Skin Fade', 'Beard Sculpt'];

        foreach ($barberServices as $index => $service) {
            DB::table('services')
                ->where('name', $legacyBarberNames[$index])
                ->update($service);
        }

        foreach ([
            ['name' => 'Make Up Only', 'description' => 'Makeup dasar untuk tampilan natural.', 'duration_minutes' => 60, 'price' => 250000],
            ['name' => 'Make Up + Soft Lens', 'description' => 'Makeup dengan tambahan soft lens.', 'duration_minutes' => 75, 'price' => 300000],
            ['name' => 'Make Up + Hair Do', 'description' => 'Makeup dan penataan rambut.', 'duration_minutes' => 90, 'price' => 320000],
        ] as $service) {
            DB::table('services')->updateOrInsert(
                ['name' => $service['name']],
                [...$service, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }

    public function down(): void
    {
        DB::table('services')->whereIn('name', [
            'Make Up Only',
            'Make Up + Soft Lens',
            'Make Up + Hair Do',
        ])->delete();

        DB::table('services')->where('name', 'Fast Hair Cut')->update(['name' => 'Classic Cut', 'description' => 'Potongan klasik yang bersih dan timeless.', 'duration_minutes' => 30, 'price' => 45000]);
        DB::table('services')->where('name', 'Rileks Ganteng')->update(['name' => 'Skin Fade', 'description' => 'Fade presisi dengan transisi halus.', 'duration_minutes' => 45, 'price' => 65000]);
        DB::table('services')->where('name', 'Full Grooming')->update(['name' => 'Beard Sculpt', 'description' => 'Rapikan dan bentuk janggut sesuai wajah.', 'duration_minutes' => 30, 'price' => 35000]);
    }
};
