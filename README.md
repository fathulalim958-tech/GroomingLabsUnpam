# Glowcut Barbershop

Aplikasi web berbasis Laravel untuk sistem barbershop dan beauty service dengan fitur login multi-role, dashboard admin, dashboard kasir, dan dashboard pelanggan.

## Fitur Utama

- Login multi-role: admin, kasir, pelanggan
- Dashboard terpisah untuk setiap role
- Middleware pembatas akses antar role
- Tema light dan dark untuk tampilan halaman
- UI landing login dan dashboard modern
- Seeder akun demo untuk testing

## Teknologi

- Laravel
- PHP
- MySQL / SQLite untuk testing
- Bootstrap 5
- Blade template

## Struktur Project

- `app/Http/Controllers` : controller aplikasi
- `app/Http/Middleware` : middleware akses per role
- `resources/views` : tampilan login dan dashboard
- `routes/web.php` : routing aplikasi
- `database/seeders` : data akun contoh
- `tests/Feature` : pengujian fitur

## Persiapan

Pastikan Anda sudah memiliki PHP, Composer, dan database yang siap.

1. Clone project
2. Install dependency

```bash
composer install
```

3. Siapkan environment

```bash
cp .env.example .env
php artisan key:generate
```

4. Konfigurasi database di file `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=groominglabs
DB_USERNAME=root
DB_PASSWORD=
```

5. Buat database jika belum ada

```sql
CREATE DATABASE groominglabs;
```

6. Jalankan migrasi dan seed data

```bash
php artisan migrate --seed
```

Atau jika ingin reset database dari awal:

```bash
php artisan migrate:fresh --seed
```

## Menjalankan Aplikasi

```bash
php artisan serve
```

Buka URL:

```text
http://127.0.0.1:8000
```

## Akun Demo

Akun yang tersedia setelah seeding:

| Role | Email | Password |
| --- | --- | --- |
| Admin | admin@example.com | password |
| Kasir | kasir@example.com | password |
| Pelanggan | pelanggan@example.com | password |

## Route Utama

- `/` : halaman login
- `/dashboard/admin` : dashboard admin
- `/dashboard/kasir` : dashboard kasir
- `/dashboard/pelanggan` : dashboard pelanggan
- `/logout` : logout

## Testing

```bash
php artisan test
```

## Catatan

Project ini masih dalam tahap pengembangan fitur, namun struktur dasar autentikasi, role access, dan tema UI sedang dikembangkan lebih lanjut.

## Lisensi

Project ini dibuat untuk kebutuhan pembelajaran dan pengembangan internal.
