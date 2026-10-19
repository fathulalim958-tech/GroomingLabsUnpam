# Grooming Lab & MUA Studio — Sistem E-Booking & Manajemen Antrian

Aplikasi web terintegrasi untuk reservasi jadwal dan manajemen nomor antrian *real-time* layanan perawatan rambut pria (**Barbershop**) dan tata rias (**Make-Up Artist / MUA**). 

Aplikasi ini menggabungkan modul landing page katalog publik, modul pemesanan mandiri oleh pelanggan (*mobile-friendly*), modul operasional meja depan (*front-desk cashier*), dan modul manajemen data master & laporan oleh administrator dengan dukungan penuh **Mode Gelap (Dark Mode)** dan **Mode Terang (Light Mode)**.

---

## 📑 Daftar Isi
- [Ikhtisar & Teknologi](#-ikhtisar--teknologi)
- [Fitur Unggulan](#-fitur-unggulan)
  - [🌙 Mode Gelap & Terang (Dark / Light Mode)](#-mode-gelap--terang-dark--light-mode)
  - [✨ Halaman Info Kategori Layanan (CMS Konten Dinamis)](#-halaman-info-kategori-layanan-cms-konten-dinamis)
- [Akun Demo & Akses Cepat](#-akun-demo--akses-cepat)
- [Struktur Database & Model Data](#-struktur-database--model-data)
- [Siklus Status Antrian (State Machine)](#-siklus-status-antrian-state-machine)
- [Spesifikasi Fungsional Per Role](#-spesifikasi-fungsional-per-role)
  - [1. Modul Autentikasi & Halaman Informasi Publik](#1-modul-autentikasi--halaman-informasi-publik)
  - [2. Modul Pelanggan (Customer Mobile-First)](#2-modul-pelanggan-customer-mobile-first)
  - [3. Modul Kasir (Front-Desk Queue Control)](#3-modul-kasir-front-desk-queue-control)
  - [4. Modul Admin (Back-Office & Reporting)](#4-modul-admin-back-office--reporting)
- [Panduan Instalasi & Menjalankan Proyek](#-panduan-instalasi--menjalankan-proyek)
- [Pengujian Otomatis (Automated Testing)](#-pengujian-otomatis-automated-testing)
- [Struktur Direktori Utama](#-struktur-direktori-utama)

---

## 🚀 Ikhtisar & Teknologi

- **Backend Framework:** Laravel 12 (PHP 8.2+)
- **Frontend Stack:** Tailwind CSS (dengan `darkMode: 'class'`) + Alpine.js + Laravel Blade Components
- **Asset Bundler:** Vite
- **Database:** SQLite / MySQL Ready
- **Real-Time Synchronization:** Polling interval 5–7 detik untuk sinkronisasi tiket antrian pelanggan dan meja kasir
- **Desain UI Dual-Theme:** 
  - *Dark Mode:* Nuansa mewah (*luxury dark*) dengan latar slate gelap dan aksen amber/gold untuk Barbershop serta rose/pink untuk MUA.
  - *Light Mode:* Tampilan bersih, profesional, dengan latar putih/slate lembut dan kontras teks tajam (*high-contrast*).

---

## 🌟 Fitur Unggulan

### 🌙 Mode Gelap & Terang (Dark / Light Mode)
- **Komponen Toggle:** Tombol beralih tema (`<x-theme-toggle />`) tersedia di semua layout (Guest, Pelanggan, Kasir, Admin, dan Halaman Info Publik).
- **Penyimpanan Preferensi:** Pilihan tema pengguna disimpan di `localStorage.theme` sehingga preferensi tetap aktif di semua sesi.
- **Anti-FOUC (Flash of Unstyled Content):** Skrip inline di `<head>` mendeteksi preferensi tema sebelum render halaman, mencegah kedipan warna putih/gelap.
- **Kontras Tinggi:** Teks, label, border, badge, formulir input, dan tabel dirancang khusus agar tetap sangat terbaca di mode terang maupun mode gelap.

### ✨ Halaman Info Kategori Layanan (CMS Konten Dinamis)
- Pelanggan dan publik dapat melihat detail mendalam tentang masing-masing kategori layanan sebelum melakukan login/booking melalui rute:
  - `/layanan/barbershop` (Informasi lengkap fasilitas, paket pria, dan aturan Barbershop)
  - `/layanan/mua` (Informasi tata rias wisuda, pesta, bridal, fasilitas studio, dan konsultasi)
- **Kontrol Penuh Admin:** Judul hero, deskripsi, daftar fasilitas, aturan/kebijakan, dan banner promosi dapat diubah sewaktu-waktu oleh Admin melalui menu **Konten Kategori** (`/admin/category-contents`).

---

## 🔑 Akun Demo & Akses Cepat

Setelah database di-seed (`php artisan db:seed`), tersedia 3 akun default untuk setiap peran. Pada halaman Login juga disediakan tombol **"Login Cepat"** untuk kemudahan demonstrasi:

| Peran (Role) | Email | Password | Halaman Awal | Hak Akses Utama |
| :--- | :--- | :--- | :--- | :--- |
| **Admin** | `admin@barbershop.com` | `password` | `/admin/dashboard` | Akses penuh: Kasir, Pelanggan, Layanan, Jam Kerja, Staf, Konten Kategori, Laporan |
| **Kasir** | `kasir@barbershop.com` | `password` | `/kasir/dashboard` | Kontrol antrian harian (Panggil, Lewati, Selesai), Input Walk-In Order |
| **Pelanggan** | `customer@example.com` | `password` | `/pelanggan/home` | Katalog layanan, booking tanggal & jam, tiket antrian aktif, riwayat |

---

## 🗄️ Struktur Database & Model Data

```
┌──────────────────────┐
│  category_contents   │  (CMS Konten Dinamis Info Kategori)
│(category, hero, etc.)│
└──────────────────────┘

┌──────────────┐       1:N       ┌──────────────┐
│    users     ├────────────────►│   bookings   │
│ (role, phone)│                 │(queue_number)│
└──────────────┘                 └──────┬───────┘
                                        │
                                        │ 1:N
                                        ▼
┌──────────────┐       1:N       ┌──────────────┐
│   services   ├────────────────►│booking_items │
│(price, durasi│                 │ (price snap) │
└──────────────┘                 └──────────────┘
```

1. **`users`**: `id`, `name`, `email`, `phone`, `password`, `role` (`admin`, `kasir`, `pelanggan`), `avatar`, `is_active`, `timestamps`.
2. **`services`**: `id`, `category` (`barbershop`, `mua`), `name`, `description`, `price`, `duration_minutes`, `image`, `is_active`, `timestamps`.
3. **`staffs`**: `id`, `name`, `role` (`kapster`, `mua`), `phone`, `is_active`, `timestamps`.
4. **`operating_hours`**: `id`, `day_of_week` (0=Minggu, 1=Senin, ..., 6=Sabtu), `open_time`, `close_time`, `is_closed`, `max_bookings_per_slot`, `timestamps`.
5. **`category_contents`**: `id`, `category` (`barbershop`, `mua`), `title`, `subtitle`, `description`, `facilities` (JSON), `rules` (JSON), `banner_url`, `timestamps`.
6. **`bookings`**: `id`, `booking_code` (e.g. `BK-20261019-XXXX`), `user_id`, `cashier_id` (nullable), `staff_id` (nullable), `service_category` (`barbershop`, `mua`), `booking_date`, `booking_time`, `queue_number` (e.g. `B-001`, `M-001`), `notes`, `total_price`, `status`, `timestamps`.
7. **`booking_items`**: `id`, `booking_id`, `service_id`, `price`, `timestamps`.

---

## 🔄 Siklus Status Antrian (State Machine)

Sistem menerapkan aturan transisi status antrian yang ketat agar integritas pemanggilan nomor antrian tetap terjaga:

| Status Saat Ini | Aksi Kasir / Pelanggan | Status Berikutnya | Dampak Pada Sistem |
| :--- | :--- | :--- | :--- |
| *(Submit Form)* | Pelanggan membuat pesanan | **`waiting`** (Menunggu) | Tiket muncul di menu **Order** pelanggan & tabel kasir. Nomor antrian digenerate (`B-XXX` / `M-XXX`). |
| **`waiting`** | Kasir klik "Panggil / Proses" | **`in_progress`** (Proses) | Nomor tampil sebagai **"Antrian Sedang Berlangsung"** di layar pelanggan secara *live*. |
| **`waiting`** | Kasir klik "Lewati" *(Pelanggan belum datang)* | **`skipped`** (Dilewati) | Status berubah dilewati, nomor antrian selanjutnya dapat dipanggil. **Tiket tidak hangus/dibatalkan**. |
| **`skipped`** | Kasir klik "Panggil Ulang" *(Pelanggan telah hadir)* | **`in_progress`** (Proses) | Nomor antrian yang terlambat dimasukkan kembali ke antrian aktif saat ini. |
| **`in_progress`** | Kasir klik "Selesai & Bayar" | **`completed`** (Selesai) | Layanan tuntas & pembayaran divalidasi. Tiket berpindah ke menu **Riwayat** pelanggan dan rekap laporan admin. |
| **`waiting` / `skipped`** | Dibatalkan oleh pelanggan atau kasir | **`cancelled`** (Dibatalkan) | Tiket dibatalkan dan masuk ke riwayat dengan label Dibatalkan. |

> **Catatan Auto-Reset:** Nomor antrian (`B-001`, `M-001`) secara otomatis mereset kembali ke nomor `001` setiap pergantian tanggal baru (`00:00` WIB).

---

## 🎯 Spesifikasi Fungsional Per Role

### 1. Modul Autentikasi & Halaman Informasi Publik
- **FR-AUTH-01 (Login Page):** Akses `/` langsung diarahkan ke halaman login yang dilengkapi tombol login cepat dan switcher mode gelap/terang. Sistem mendeteksi role (`admin`, `kasir`, `pelanggan`) dan mengalihkan ke dashboard terkait.
- **Halaman Info Kategori Publik:** Tautan di halaman login & registrasi menuju `/layanan/barbershop` dan `/layanan/mua` yang menjelaskan profil layanan, katalog harga, daftar fasilitas, dan tombol langsung reservasi.
- **FR-AUTH-02 (Register Page):** Pendaftaran mandiri pelanggan dengan input Nama Lengkap, Nomor Telepon/WhatsApp, Email, dan Password. Otomatis dialihkan ke `/pelanggan/home`.
- **FR-AUTH-03 (Lupa Password & Logout):** Fitur reset kata sandi melalui email dan tombol keluar sesi yang aman.

---

### 2. Modul Pelanggan (Customer Mobile-First)
Antarmuka pelanggan dioptimalkan untuk kenyamanan di perangkat mobile (*smartphone*) dengan bilah navigasi bawah (*Bottom Navigation Bar*):

- **Menu Home:**
  - **FR-CUST-01 (Katalog Layanan):** Banner fasilitas & promo, daftar layanan unggulan Barbershop & MUA (nama, gambar, harga Rp, estimasi menit, deskripsi).
  - Tautan langsung ke halaman info detail kategori (`/layanan/barbershop` & `/layanan/mua`).
  - **FR-CUST-02 (Tombol CTA):** Tombol **"Booking Sekarang"** yang kontras dan mencolok.
  - **Active Ticket Notice:** Jika pelanggan telah memiliki antrian aktif hari ini, muncul notifikasi status antrian di bagian teratas.
- **Alur Reservasi (Booking Flow):**
  - **FR-CUST-03 (Pilih Kategori):** Pemilihan visual kategori utama: **Layanan Barbershop** vs **Layanan MUA Studio**.
  - **FR-CUST-04 (Formulir Booking):**
    - Multi-select checklist layanan dengan kalkulasi live harga & durasi via Alpine.js.
    - Pemilihan tanggal (7 hari ke depan, hari libur toko dinonaktifkan otomatis).
    - Pemilihan slot jam operasional (cek kuota maksimum per slot waktu).
    - Catatan opsional (request kapster/MUA, jenis rambut, kondisi kulit sensitif).
    - Ringkasan biaya dan tombol konfirmasi pesanan.
  - **FR-CUST-05 (Generate Nomor Antrian):** Pembuatan otomatis kode booking unik (`BK-YYYYMMDD-XXXX`) dan nomor antrian (`B-001`, `M-001`).
- **Menu Order (FR-CUST-06 - Pantau Antrian Real-Time):**
  - Nomor antrian pelanggan berukuran besar dan jelas.
  - **Current Serving Queue:** Menampilkan nomor antrian yang sedang berada di kursi Barbershop dan studio MUA.
  - Sinkronisasi otomatis (*auto-polling*) setiap 5 detik tanpa refresh manual.
  - Tombol batalkan pesanan jika masih berstatus `waiting` atau `skipped`.
- **Menu Riwayat (FR-CUST-07 & FR-CUST-08):**
  - Daftar transaksi yang telah `completed` atau `cancelled`.
  - Tab filter kategori: **Semua**, **Barbershop**, dan **MUA Studio**.
- **Menu Profil (FR-CUST-09):**
  - Pembaruan nama, nomor WhatsApp/telepon, email, serta formulir penggantian kata sandi.

---

### 3. Modul Kasir (Front-Desk Queue Control)
Dirancang untuk kecepatan operasional staf front-desk pada layar tablet maupun desktop:

- **FR-CASH-01 (Daftar Order Hari Berjalan):** Tabel seluruh pesanan hari ini dengan filter kategori layanan dan status antrian.
- **FR-CASH-02 (Update Status Antrian):**
  - Mengubah antrian dari **Menunggu** ke **Proses** (mengupdate layar pelanggan secara instan).
  - Mengubah antrian dari **Proses** ke **Selesai** setelah pembayaran diselesaikan.
- **FR-CASH-03 (Mekanisme Lewati & Panggil Ulang / Skip & Recall):**
  - Kasir dapat menekan tombol **"Lewati"** jika pelanggan belum berada di lokasi saat dipanggil.
  - Menekan Lewati tidak menghanguskan pesanan.
  - Antrian yang dilewati dapat dipanggil kembali kapan saja via tombol **"Panggil Ulang"** saat pelanggan hadir.
- **FR-CASH-04 (Input Pesanan Walk-In):**
  - Formulir khusus pemesanan tamu langsung (*offline/walk-in*) yang datang ke tempat tanpa reservasi online sebelumnya.
  - Nomor antrian tetap berurutan dan sinkron dengan antrian pelanggan online.
- **Auto-Sync:** Layar dashboard kasir melakukan polling berkala untuk memunculkan booking online baru secara otomatis.

---

### 4. Modul Admin (Back-Office & Reporting)
Dashboard komprehensif bagi pemilik salon/barbershop:

- **Dashboard Ringkasan:** Kartu metrik total pelanggan, layanan aktif, booking hari ini, pendapatan hari ini, pendapatan bulan ini, dan 10 riwayat booking terkini.
- **Konten Kategori (CMS Info Layanan):** Mengedit judul, subjudul, deskripsi, fasilitas, aturan/kebijakan, dan banner untuk halaman publik Barbershop dan MUA.
- **FR-ADM-01 (Kelola Data Kasir):** Tambah, lihat, edit, hapus akun kasir, serta toggle status akun aktif/nonaktif.
- **FR-ADM-02 (Kelola Data Pelanggan):** Pencarian pelanggan (nama, email, no. HP), melihat detail & seluruh histori transaksi pelanggan, toggle blokir akun, dan opsi reset password ke default.
- **FR-ADM-03 (Kelola Katalog Layanan):** CRUD katalog Barbershop dan MUA (Nama, Kategori, Foto/URL, Deskripsi, Harga, Estimasi Durasi menit, dan Status Aktif).
- **FR-ADM-04 (Kelola Jadwal & Jam Operasional):** Pengaturan jam buka-tutup per hari (Senin–Minggu), kuota pemesanan maksimal per slot waktu, dan status libur/tutup toko.
- **FR-ADM-05 (Kelola Staf Kapster & MUA):** CRUD data pekerja (Kapster/Barber pria dan MUA wanita) beserta nomor telepon dan status keaktifan kerja.
- **FR-ADM-06 (Laporan Transaksi & Pendapatan):**
  - Filter periode tanggal (Mulai s/d Selesai) dan kategori (Semua, Barbershop, MUA).
  - Rekapitulasi total pendapatan kotor, pendapatan Barbershop, dan pendapatan MUA.
  - **Ekspor CSV / Excel:** Format berkas Excel dengan UTF-8 BOM agar rapi saat dibuka di Microsoft Excel.
  - **Ekspor Cetak / PDF:** Tampilan struk rekapitulasi resmi dengan styling cetak siap print atau unduh PDF.

---

## 💻 Panduan Instalasi & Menjalankan Proyek

### 1. Prasyarat Sistem
- PHP >= 8.2 (dengan ekstensi `pdo_sqlite` atau `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`)
- Composer >= 2.0
- Node.js >= 18.x & NPM

### 2. Langkah Instalasi

```bash
# 1. Masuk ke direktori proyek
cd c:\Users\fathu\Music\GLtes

# 2. Install dependensi PHP
composer install

# 3. Salin file environment jika belum ada
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Jalankan migrasi database dan pengisian data awal (seeder)
php artisan migrate:fresh --seed

# 6. Install dependensi JavaScript & kompilasi aset frontend
npm install
npm run build
```

### 3. Menjalankan Server Lokal

Jalankan server Laravel:
```bash
php artisan serve
```

Akses aplikasi di browser melalui URL:
👉 **`http://127.0.0.1:8000`**

*(Halaman akan langsung diarahkan ke `/login` sesuai spesifikasi FR-AUTH-01).*

---

## 🧪 Pengujian Otomatis (Automated Testing)

Aplikasi telah dilengkapi unit & feature tests menyeluruh yang mencakup:
- Alur registrasi mandiri pelanggan dan nomor telepon
- Redirect login berbasis peran pengguna
- Pembuatan booking dan pembentukan nomor antrian (`B-001`, `M-001`)
- Siklus hidup antrian kasir (*Waiting &rarr; Skipped &rarr; In Progress &rarr; Completed*)
- Pembuatan walk-in order kasir
- Ekspor laporan CSV admin
- Halaman informasi kategori publik (`/layanan/{category}`)
- Pengelolaan CMS Konten Kategori oleh Admin

Jalankan seluruh pengujian dengan perintah:
```bash
php artisan test
```

**Hasil Verifikasi:**
```text
   PASS  Tests\Feature\BarbershopMuaSystemTest
  ✓ customer can self register with phone
  ✓ role based login redirect
  ✓ customer can create booking and generate queue number
  ✓ cashier queue lifecycle
  ✓ cashier can create walkin order
  ✓ admin can export reports csv
  ✓ public category information page is accessible and displays content
  ✓ admin can update category content

   PASS  Tests\Feature\Auth\... (Authentication, Registration, Password, Profile)
  ---------------------------------------------------------------------------------
  Tests:    33 passed (100 assertions)
  Duration: 2.91s
```

---

## 📁 Struktur Direktori Utama

```text
GLtes/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                  # Controller Admin (Dashboard, Kasir, Layanan, Staf, Jam, Laporan, CategoryContent)
│   │   │   ├── Kasir/                  # Controller Kasir (Dashboard, QueueController, Walk-In)
│   │   │   ├── Pelanggan/              # Controller Pelanggan (Home, Booking, Order, Riwayat)
│   │   │   ├── CategoryPageController.php # Controller Halaman Info Publik Kategori
│   │   │   └── ProfileController.php
│   │   └── Middleware/
│   │       └── CheckRole.php           # Middleware otorisasi multi-role
│   ├── Models/
│   │   ├── Booking.php                 # Model booking + generator kode & nomor antrian
│   │   ├── BookingItem.php             # Model item layanan terpilih
│   │   ├── CategoryContent.php         # Model konten CMS halaman kategori
│   │   ├── OperatingHour.php           # Model jam operasional & hari libur
│   │   ├── Service.php                 # Model katalog layanan Barbershop & MUA
│   │   ├── Staff.php                   # Model kapster & penata rias
│   │   └── User.php                    # Model pengguna dengan role helper
│   └── View/Components/                # Blade components (ThemeToggle, Layouts)
├── database/
│   ├── migrations/                     # Migration users, services, staffs, hours, bookings, category_contents
│   └── seeders/DatabaseSeeder.php      # Seeder akun demo, 10 layanan, jam kerja, 4 staf, 2 category contents
├── resources/
│   ├── css/app.css                     # Tailwind CSS v3 dengan darkMode class
│   └── views/
│       ├── admin/                      # Blade views modul Admin + Category Contents
│       ├── kasir/                      # Blade views modul Kasir & Walk-In
│       ├── pelanggan/                  # Blade views modul Pelanggan (Mobile-First)
│       ├── categories/                 # Blade views Info Kategori Layanan Publik
│       ├── components/theme-toggle.blade.php # Tombol pengalih Dark/Light mode
│       └── layouts/                    # Master layouts per role (Guest, Pelanggan, Kasir, Admin)
├── routes/
│   ├── web.php                         # Rute ber-prefix pelanggan, kasir, admin, layanan
│   └── auth.php                        # Rute autentikasi Breeze
└── tests/
    └── Feature/
        └── BarbershopMuaSystemTest.php # Automated feature tests PRD & fitur kustom
```

---

## 📄 Lisensi
Aplikasi ini dikembangkan sebagai solusi reservasi dan antrian modern untuk Barbershop & Make-Up Artist Studio di bawah lisensi open-source [MIT](https://opensource.org/licenses/MIT).
