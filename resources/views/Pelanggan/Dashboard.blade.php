<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelanggan - Barber & MUA</title>

    <!-- Bootstrap 5.3 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5.3 JS Bundle loaded early without integrity restrictions -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/bootstrap.bundle.min.js"></script>

    <style>
        :root {
            --bg-dark: #0f0f12;
            --card-bg: #18181c;
            --card-hover: #22222a;
            --border-color: #2b2b36;
            --gold-primary: #e2b955;
            --gold-secondary: #c99b33;
            --text-muted: #9e9ea7;
        }

        body {
            background-color: var(--bg-dark);
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding-bottom: 70px;
            /* Space for mobile bottom nav */
        }

        @media (min-width: 768px) {
            body {
                padding-bottom: 0;
            }
        }

        /* Custom Gold Utilities */
        .text-gold {
            color: var(--gold-primary) !important;
        }

        .bg-gold {
            background-color: var(--gold-primary) !important;
            color: #000000 !important;
        }

        .btn-gold {
            background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-secondary) 100%);
            color: #000000;
            font-weight: 700;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-gold:hover,
        .btn-gold:focus {
            background: linear-gradient(135deg, #ebd178 0%, var(--gold-primary) 100%);
            color: #000000;
            box-shadow: 0 4px 15px rgba(226, 185, 85, 0.3);
        }

        .btn-outline-gold {
            border: 1px solid var(--gold-primary);
            color: var(--gold-primary);
            background: transparent;
        }

        .btn-outline-gold:hover {
            background-color: var(--gold-primary);
            color: #000000;
        }

        .bg-card-custom {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            transition: transform 0.2s ease, border-color 0.2s ease;
        }

        .bg-card-custom:hover {
            border-color: rgba(226, 185, 85, 0.5);
        }

        /* Horizontal Scroll for Mobile Hero Cards */
        .horizontal-scroll-container {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            gap: 1rem;
            padding-bottom: 0.5rem;
            -webkit-overflow-scrolling: touch;
        }

        .horizontal-scroll-container::-webkit-scrollbar {
            display: none;
        }

        .scroll-card {
            flex: 0 0 85%;
            scroll-snap-align: center;
        }

        @media (min-width: 768px) {
            .horizontal-scroll-container {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                overflow-x: visible;
            }

            .scroll-card {
                flex: auto;
            }
        }

        /* Hero Overlay */
        .hero-card {
            position: relative;
            height: 180px;
            border-radius: 1rem;
            overflow: hidden;
        }

        .hero-card img {
            object-fit: cover;
            width: 100%;
            height: 100%;
            opacity: 0.4;
            transition: transform 0.5s ease;
        }

        .hero-card:hover img {
            transform: scale(1.05);
        }

        .hero-card-content {
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(0, 0, 0, 0.85) 20%, transparent 100%);
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 2;
        }

        /* Mobile Bottom Nav */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(15, 15, 18, 0.95);
            backdrop-filter: blur(10px);
            border-top: 1px solid var(--border-color);
            z-index: 1030;
        }

        .bottom-nav .nav-link {
            color: var(--text-muted);
            font-size: 0.75rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.5rem 0;
        }

        .bottom-nav .nav-link.active,
        .bottom-nav .nav-link:hover {
            color: var(--gold-primary);
        }

        .bottom-nav .nav-link i {
            font-size: 1.15rem;
            margin-bottom: 2px;
        }

        .badge-barber {
            background-color: rgba(226, 185, 85, 0.15);
            color: var(--gold-primary);
            border: 1px solid rgba(226, 185, 85, 0.3);
        }

        .badge-mua {
            background-color: rgba(236, 72, 153, 0.15);
            color: #f472b6;
            border: 1px solid rgba(236, 72, 153, 0.3);
        }
    </style>

    <style>
        html {
            color-scheme: dark;
        }

        html[data-theme="light"] {
            color-scheme: light;
            --bg-app: #f3f6fb;
            --surface: #ffffff;
            --surface-2: #edf3fa;
            --surface-3: #e6edf6;
            --text: #101828;
            --text-muted: #5f6c7a;
            --border: #dfe7f1;
            --gold-primary: #d4a63a;
            --gold-secondary: #bb8b26;
            --shadow: rgba(15, 23, 42, 0.08);
        }

        html[data-theme="dark"] {
            color-scheme: dark;
            --bg-app: #0f0f12;
            --surface: #18181c;
            --surface-2: #1d1d24;
            --surface-3: #272730;
            --text: #ffffff;
            --text-muted: #9e9ea7;
            --border: #2b2b36;
            --gold-primary: #e2b955;
            --gold-secondary: #c99b33;
            --shadow: rgba(0, 0, 0, 0.7);
        }

        body {
            background: var(--bg-app);
            color: var(--text);
            transition: background 0.25s ease, color 0.25s ease;
        }

        .bg-card-custom,
        .bottom-nav,
        .hero-card,
        .card,
        .nav-link,
        .btn,
        .form-control,
        .form-select,
        .modal-content,
        .btn-outline-gold,
        .btn-gold,
        .theme-toggle-btn {
            transition: all 0.25s ease;
        }

        .bg-card-custom,
        .card,
        .bottom-nav,
        .modal-content,
        .form-control,
        .form-select,
        .hero-card {
            background-color: var(--surface);
            border-color: var(--border);
            color: var(--text);
        }

        .text-white,
        .fw-bold,
        .h4,
        .h5,
        .h6,
        .modal-title,
        .nav-link {
            color: var(--text) !important;
        }

        .text-muted,
        .small,
        .text-white-50,
        .nav-link {
            color: var(--text-muted) !important;
        }

        .bg-gold {
            background-color: var(--gold-primary) !important;
            color: #000000 !important;
        }

        .btn-gold {
            background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-secondary) 100%);
        }

        .theme-toggle-btn {
            position: fixed;
            right: 20px;
            bottom: 20px;
            z-index: 999;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--text);
            box-shadow: 0 8px 20px var(--shadow);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-md navbar-dark bg-dark border-bottom border-secondary border-opacity-25 sticky-top d-none d-md-block"
        style="background-color: var(--bg-dark) !important;">
        <div class="container-xl">
            <a class="navbar-brand d-flex items-center gap-2" href="#">
                <span class="badge bg-gold p-2 rounded-3 text-dark fw-bold me-1">B</span>
                <span class="fw-bold tracking-wide">GROOMINGLABS <span class="text-gold">BARBER & MUA</span></span>
            </a>
            <div class="collapse navbar-collapse" id="desktopNavbar">
                <ul class="navbar-menu navbar-nav mx-auto mb-2 mb-lg-0 gap-3">
                    <li class="nav-item">
                        <a class="nav-link active text-gold fw-semibold" href="#"><i
                                class="fa-solid fa-house me-1"></i> Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#" onclick="openModal()"><i
                                class="fa-solid fa-calendar-check me-1"></i> Booking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#"><i
                                class="fa-solid fa-clock-rotate-left me-1"></i> Riwayat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#"><i class="fa-solid fa-user me-1"></i>
                            Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="/logout"><i class="fa-solid fa-sign-out-alt me-1"></i>
                            Keluar</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3 ps-3 border-start border-secondary border-opacity-25">
                    <div class="text-end">
                        <div class="small text-muted">Selamat Datang,</div>
                        <div class="fw-bold text-white">Pelanggan 👋</div>
                    </div>
                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80"
                        alt="Profile" class="rounded-circle border border-2 border-warning" width="42"
                        height="42" style="object-fit: cover;">
                </div>
            </div>
        </div>
    </nav>

    <main class="container-xl pt-4 flex-grow-1">

        <!-- Mobile Header (Visible on mobile only) -->
        <div class="d-flex d-md-none justify-content-between align-items-center mb-4">
            <div>
                <span class="text-uppercase text-muted fw-bold small tracking-wider" style="font-size: 0.7rem;">Selamat
                    datang kembali</span>
                <h2 class="h4 fw-extrabold text-white mb-0">Ahmad Rizky <span
                        class="d-inline-block animate-bounce">👋</span></h2>
            </div>
            <div class="position-relative">
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80"
                    alt="Profile" class="rounded-circle border border-2 border-warning" width="48" height="48"
                    style="object-fit: cover;">
                <span
                    class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-dark rounded-circle"></span>
            </div>
        </div>

        <section class="mb-4">
            <div class="horizontal-scroll-container">
                <!-- Hero Card 1: Barbershop -->
                <div class="scroll-card">
                    <a href="/dashboard/pelanggan/Barbershop" class="text-decoration-none">
                        <div class="hero-card bg-card-custom">
                            <img src="https://images.unsplash.com/photo-1503951914875-452162b0f3f1?w=600&auto=format&fit=crop&q=80"
                                alt="Barbershop">
                            <div class="hero-card-content">
                                <div>
                                    <span class="badge bg-gold px-2.5 py-1 rounded-pill fw-bold text-uppercase"
                                        style="font-size: 0.65rem;">
                                        <i class="fa-solid fa-scissors me-1"></i> BARBERSHOP
                                    </span>
                                </div>
                                <div>
                                    <h3 class="h4 fw-bold text-white mb-1">Premium Haircut</h3>
                                    <p class="small text-white-50 mb-0">Fade · Classic · Coloring · Beard Trim</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Hero Card 2: MUA Wisuda -->
                <div class="scroll-card">
                    <a href="/dashboard/pelanggan/MUA" class="text-decoration-none">
                        <div class="hero-card bg-card-custom">
                            <img src="https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?w=600&auto=format&fit=crop&q=80"
                                alt="MUA Wisuda">
                            <div class="hero-card-content">
                                <div>
                                    <span
                                        class="badge bg-danger bg-opacity-75 text-white px-2.5 py-1 rounded-pill fw-bold text-uppercase"
                                        style="font-size: 0.65rem;">
                                        <i class="fa-solid fa-wand-magic-sparkles me-1"></i> MUA WISUDA
                                    </span>
                                </div>
                                <div>
                                    <h3 class="h4 fw-bold text-white mb-1">Makeup & Styling</h3>
                                    <p class="small text-white-50 mb-0">Natural Look · Glamour · Hijab Do</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <section class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-white mb-0">Layanan Kami</h5>
                <span class="small text-muted">Pilih kategori layanan</span>
            </div>

            <div class="row g-3">
                <!-- Category 1: Barbershop -->
                <div class="col-6">
                    <div onclick="filterCategory('barbershop')" id="cat-barbershop"
                        class="card bg-card-custom p-3 rounded-4 cursor-pointer h-100 border border-secondary border-opacity-25"
                        role="button">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 rounded-3 text-gold" style="background-color: rgba(226, 185, 85, 0.15);">
                                <i class="fa-solid fa-scissors fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-white mb-0">Barbershop</h6>
                                <p class="small text-muted mb-0" style="font-size: 0.75rem;">Haircut & Styling</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category 2: MUA Wisuda -->
                <div class="col-6">
                    <div onclick="filterCategory('mua')" id="cat-mua"
                        class="card bg-card-custom p-3 rounded-4 cursor-pointer h-100 border border-secondary border-opacity-25"
                        role="button">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 rounded-3 text-pink"
                                style="background-color: rgba(236, 72, 153, 0.15); color: #f472b6;">
                                <i class="fa-solid fa-spray-can-sparkles fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-white mb-0">MUA Wisuda</h6>
                                <p class="small text-muted mb-0" style="font-size: 0.75rem;">Makeup & Styling</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-4">
            <a href="/dashboard/pelanggan/booking"
                class="btn btn-gold btn-lg w-100 py-3 rounded-4 shadow-lg d-flex align-items-center justify-content-center gap-2 text-decoration-none">
                <i class="fa-solid fa-calendar-plus fs-5"></i>
                <span class="fw-bold fs-6">Booking Sekarang</span>
            </a>
        </section>

        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-white mb-0">Paket Terpopuler</h5>
                <a href="#" onclick="resetFilter(); return false;"
                    class="text-gold small text-decoration-none fw-semibold">
                    Lihat semua <i class="fa-solid fa-chevron-right ms-1" style="font-size: 0.65rem;"></i>
                </a>
            </div>

            <div class="d-flex flex-column gap-3" id="packageList">

                <!-- Package Item 1 -->
                <div
                    class="package-item barbershop card bg-card-custom p-3 rounded-4 border border-secondary border-opacity-25">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1622286342621-4bd786c2447c?w=150&auto=format&fit=crop&q=80"
                                alt="Haircut Classic" class="rounded-3" width="65" height="65"
                                style="object-fit: cover;">
                            <div class="text-truncate">
                                <div class="d-flex gap-1 mb-1">
                                    <span class="badge badge-barber" style="font-size: 0.6rem;">Barbershop</span>
                                    <span class="badge bg-gold text-dark fw-bold"
                                        style="font-size: 0.6rem;">TERPOPULER</span>
                                </div>
                                <h6 class="fw-bold text-white mb-1 text-truncate">Haircut Classic</h6>
                                <p class="small text-muted mb-0 d-flex align-items-center gap-1"
                                    style="font-size: 0.75rem;">
                                    <i class="fa-regular fa-clock"></i> 45 mnt
                                </p>
                            </div>
                        </div>
                        <div class="text-end ps-2 border-start border-secondary border-opacity-25 ms-2">
                            <div class="fw-extrabold text-gold fs-5">Rp 75k</div>
                            <button onclick="openModal('Haircut Classic', '75000')"
                                class="btn btn-sm btn-outline-gold rounded-3 mt-1 fw-bold px-3 py-1"
                                style="font-size: 0.75rem;">
                                Pilih <i class="fa-solid fa-chevron-right ms-1" style="font-size: 0.6rem;"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Package Item 2 -->
                <div
                    class="package-item barbershop card bg-card-custom p-3 rounded-4 border border-secondary border-opacity-25">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1599351431202-1e0f0137899a?w=150&auto=format&fit=crop&q=80"
                                alt="Fade Cut" class="rounded-3" width="65" height="65"
                                style="object-fit: cover;">
                            <div class="text-truncate">
                                <div class="d-flex gap-1 mb-1">
                                    <span class="badge badge-barber" style="font-size: 0.6rem;">Barbershop</span>
                                </div>
                                <h6 class="fw-bold text-white mb-1 text-truncate">Fade Cut</h6>
                                <p class="small text-muted mb-0 d-flex align-items-center gap-1"
                                    style="font-size: 0.75rem;">
                                    <i class="fa-regular fa-clock"></i> 60 mnt
                                </p>
                            </div>
                        </div>
                        <div class="text-end ps-2 border-start border-secondary border-opacity-25 ms-2">
                            <div class="fw-extrabold text-gold fs-5">Rp 90k</div>
                            <button onclick="openModal('Fade Cut', '90000')"
                                class="btn btn-sm btn-outline-gold rounded-3 mt-1 fw-bold px-3 py-1"
                                style="font-size: 0.75rem;">
                                Pilih <i class="fa-solid fa-chevron-right ms-1" style="font-size: 0.6rem;"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Package Item 3 -->
                <div
                    class="package-item mua card bg-card-custom p-3 rounded-4 border border-secondary border-opacity-25">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1512496015851-a90fb38ba796?w=150&auto=format&fit=crop&q=80"
                                alt="Makeup Natural Wisuda" class="rounded-3" width="65" height="65"
                                style="object-fit: cover;">
                            <div class="text-truncate">
                                <div class="d-flex gap-1 mb-1">
                                    <span class="badge badge-mua" style="font-size: 0.6rem;">MUA Wisuda</span>
                                    <span class="badge bg-danger text-white fw-bold"
                                        style="font-size: 0.6rem;">TERLARIS</span>
                                </div>
                                <h6 class="fw-bold text-white mb-1 text-truncate">Makeup Natural Wisuda</h6>
                                <p class="small text-muted mb-0 d-flex align-items-center gap-1"
                                    style="font-size: 0.75rem;">
                                    <i class="fa-regular fa-clock"></i> 90 mnt
                                </p>
                            </div>
                        </div>
                        <div class="text-end ps-2 border-start border-secondary border-opacity-25 ms-2">
                            <div class="fw-extrabold text-gold fs-5">Rp 350k</div>
                            <button onclick="openModal('Makeup Natural Wisuda', '350000')"
                                class="btn btn-sm btn-outline-gold rounded-3 mt-1 fw-bold px-3 py-1"
                                style="font-size: 0.75rem;">
                                Pilih <i class="fa-solid fa-chevron-right ms-1" style="font-size: 0.6rem;"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main>

    <nav class="bottom-nav d-md-none">
        <div class="container d-flex justify-content-around">
            <a href="#" class="nav-link active">
                <i class="fa-solid fa-house-chimney"></i>
                <span>Beranda</span>
            </a>
            <a href="#" class="nav-link" onclick="openModal(); return false;">
                <i class="fa-solid fa-calendar-day"></i>
                <span>Booking</span>
            </a>
            <a href="#" class="nav-link">
                <i class="fa-solid fa-file-invoice"></i>
                <span>Riwayat</span>
            </a>
            <a href="#" class="nav-link">
                <i class="fa-solid fa-user"></i>
                <span>Profil</span>
            </a>
            <a href="/logout" class="nav-link">
                <i class="fa-solid fa-user"></i>
                <span>Keluar</span>
            </a>
        </div>
    </nav>

    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-card-custom border-secondary rounded-4 shadow-lg">
                <div class="modal-header border-bottom border-secondary border-opacity-25">
                    <div>
                        <h5 class="modal-title fw-bold text-white" id="bookingModalLabel">Formulir Booking</h5>
                        <p class="small text-muted mb-0">Atur jadwal reservasi layanan Anda</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="bookingForm" onsubmit="handleFormSubmit(event)">
                        <div class="mb-3">
                            <label class="form-label small text-muted">Pilih Layanan</label>
                            <select class="form-select bg-dark text-white border-secondary rounded-3"
                                id="serviceSelect">
                                <option value="Haircut Classic">Haircut Classic - Rp 75.000</option>
                                <option value="Fade Cut">Fade Cut - Rp 90.000</option>
                                <option value="Makeup Natural Wisuda">Makeup Natural Wisuda - Rp 350.000</option>
                            </select>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small text-muted">Tanggal</label>
                                <input type="date" required
                                    class="form-control bg-dark text-white border-secondary rounded-3"
                                    id="bookingDate">
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted">Jam</label>
                                <input type="time" required
                                    class="form-control bg-dark text-white border-secondary rounded-3"
                                    id="bookingTime">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Catatan Tambahan (Opsional)</label>
                            <textarea class="form-control bg-dark text-white border-secondary rounded-3" rows="2"
                                placeholder="Model rambut khusus atau permintaan MUA..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-gold w-100 py-2.5 rounded-3 fw-bold mt-2">Konfirmasi
                            Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;">
        <div id="bookingToast" class="toast align-items-center text-bg-success border-0 rounded-3 shadow-lg"
            role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-check fs-5"></i>
                    <div>
                        <strong class="d-block">Booking Berhasil!</strong>
                        <span id="toastMessage" class="small">Pesanan Anda telah tersimpan.</span>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        let modalInstance = null;
        let toastInstance = null;

        // Helper to safely get or initialize the Bootstrap Modal
        function getModalInstance() {
            const modalEl = document.getElementById('bookingModal');
            if (!modalEl) return null;

            if (window.bootstrap && window.bootstrap.Modal) {
                return bootstrap.Modal.getOrCreateInstance(modalEl);
            }
            return null;
        }

        // Helper to safely get or initialize the Bootstrap Toast
        function getToastInstance() {
            const toastEl = document.getElementById('bookingToast');
            if (!toastEl) return null;

            if (window.bootstrap && window.bootstrap.Toast) {
                return bootstrap.Toast.getOrCreateInstance(toastEl);
            }
            return null;
        }

        function openModal(serviceName = null) {
            if (serviceName) {
                const select = document.getElementById('serviceSelect');
                if (select) {
                    for (let option of select.options) {
                        if (option.value.includes(serviceName)) {
                            option.selected = true;
                            break;
                        }
                    }
                }
            }

            const modal = getModalInstance();
            if (modal) {
                modal.show();
            } else {
                // Fallback display if Bootstrap JS is not available
                const modalEl = document.getElementById('bookingModal');
                if (modalEl) {
                    modalEl.classList.add('show');
                    modalEl.style.display = 'block';
                    document.body.classList.add('modal-open');
                }
            }
        }

        function closeModal() {
            const modal = getModalInstance();
            if (modal) {
                modal.hide();
            } else {
                const modalEl = document.getElementById('bookingModal');
                if (modalEl) {
                    modalEl.classList.remove('show');
                    modalEl.style.display = 'none';
                    document.body.classList.remove('modal-open');
                }
            }
        }

        function handleFormSubmit(e) {
            e.preventDefault();
            const serviceSelect = document.getElementById('serviceSelect');
            const service = serviceSelect ? serviceSelect.value : 'Layanan';

            closeModal();

            const toastMessage = document.getElementById('toastMessage');
            if (toastMessage) {
                toastMessage.innerText = `Jadwal untuk "${service}" berhasil dikonfirmasi.`;
            }

            const toast = getToastInstance();
            if (toast) {
                toast.show();
            } else {
                const toastEl = document.getElementById('bookingToast');
                if (toastEl) {
                    toastEl.classList.add('show');
                    setTimeout(() => toastEl.classList.remove('show'), 3000);
                }
            }
        }

        function filterCategory(category) {
            const items = document.querySelectorAll('.package-item');
            items.forEach(item => {
                if (item.classList.contains(category)) {
                    item.classList.remove('d-none');
                } else {
                    item.classList.add('d-none');
                }
            });

            const catBarber = document.getElementById('cat-barbershop');
            const catMua = document.getElementById('cat-mua');

            if (catBarber) catBarber.style.borderColor = category === 'barbershop' ? 'var(--gold-primary)' :
                'var(--border-color)';
            if (catMua) catMua.style.borderColor = category === 'mua' ? 'var(--gold-primary)' : 'var(--border-color)';
        }

        function resetFilter() {
            const items = document.querySelectorAll('.package-item');
            items.forEach(item => item.classList.remove('d-none'));
            const catBarber = document.getElementById('cat-barbershop');
            const catMua = document.getElementById('cat-mua');

            if (catBarber) catBarber.style.borderColor = 'var(--border-color)';
            if (catMua) catMua.style.borderColor = 'var(--border-color)';
        }
    </script>
</body>

</html>
