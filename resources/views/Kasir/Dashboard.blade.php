<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GLOWCUT - Dashboard Kasir</title>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts: Plus Jakarta Sans & DM Serif Display -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/bootstrap.bundle.min.js"></script>

    <style>
        :root {
            --bg-dark: #0a0a0c;
            --card-bg: #141418;
            --card-border: #23232c;
            --card-hover: #1b1b22;
            --gold-primary: #e5be58;
            --gold-secondary: #c99b33;
            --gold-subtle: rgba(229, 190, 88, 0.12);
            --gold-border: rgba(229, 190, 88, 0.3);
            --text-muted: #8e8e9a;
        }

        body {
            background-color: var(--bg-dark);
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 0;
            margin: 0;
        }

        .app-container {
            width: 100%;
            max-width: 100%;
            min-height: 100vh;
            background-color: var(--bg-dark);
            padding: 1.25rem 1rem 3rem 1rem;
            position: relative;
            margin: 0 auto;
        }

        @media (min-width: 768px) {
            .app-container {
                max-width: 720px;
                padding: 1.75rem 1.5rem;
            }
        }

        @media (min-width: 992px) {
            .app-container {
                max-width: 960px;
                padding: 2rem;
            }
        }

        @media (min-width: 1200px) {
            .app-container {
                max-width: 1280px;
                padding: 2.25rem 2.5rem;
            }
        }

        .font-serif {
            font-family: 'DM Serif Display', serif;
        }

        /* Top Header */
        .header-sub {
            font-size: 0.72rem;
            letter-spacing: 2px;
            color: var(--text-muted);
            font-weight: 700;
        }

        .header-title {
            font-size: 1.85rem;
            color: #ffffff;
            margin-bottom: 0.1rem;
            font-weight: 400;
        }

        .header-date {
            font-size: 0.82rem;
            color: var(--text-muted);
        }

        .btn-circle-icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background-color: #17171d;
            border: 1px solid #282832;
            color: #ffffff;
            display: flex;
            align-items:center;
            justify-content: center;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-circle-icon:hover {
            background-color: #23232d;
            color: var(--gold-primary);
            border-color: var(--gold-primary);
        }

        /* Daily Stats Card */
        .stats-card {
            background: linear-gradient(145deg, #181610 0%, #121216 100%);
            border: 1px solid var(--gold-border);
            border-radius: 1rem;
            padding: 1.25rem 1rem;
            margin-top: 1.25rem;
            margin-bottom: 1.25rem;
        }

        .stats-label {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #bfa152;
            margin-bottom: 0.25rem;
        }

        .stats-value {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--gold-primary);
            margin-bottom: 0.1rem;
        }

        .stats-sub {
            font-size: 0.7rem;
            color: #888894;
        }

        /* Main Gold Button */
        .btn-gold-action {
            background: linear-gradient(135deg, #ebd178 0%, #e5be58 50%, #c99b33 100%);
            color: #0d0d0f;
            font-weight: 800;
            font-size: 0.95rem;
            border: none;
            border-radius: 1rem;
            padding: 0.9rem 1.25rem;
            width: 100%;
            box-shadow: 0 4px 18px rgba(229, 190, 88, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-gold-action:hover, .btn-gold-action:active {
            transform: translateY(-1px);
            box-shadow: 0 6px 22px rgba(229, 190, 88, 0.35);
            color: #000;
        }

        /* Service Category Switcher */
        .category-switcher {
            background-color: #15151a;
            border-radius: 0.85rem;
            padding: 0.3rem;
            border: 1px solid var(--card-border);
            display: flex;
            gap: 0.3rem;
        }

        .cat-btn {
            flex: 1;
            border: none;
            background: transparent;
            color: var(--text-muted);
            padding: 0.6rem 0.8rem;
            border-radius: 0.65rem;
            font-weight: 700;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            transition: all 0.25s ease;
        }

        .cat-btn.active {
            background-color: var(--gold-primary);
            color: #0d0d0f;
        }

        .cat-badge {
            font-size: 0.7rem;
            padding: 0.15rem 0.45rem;
            border-radius: 50rem;
            background: rgba(0,0,0,0.15);
        }

        .cat-btn:not(.active) .cat-badge {
            background: #252530;
            color: var(--text-muted);
        }

        /* Search Input */
        .search-box {
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .search-input {
            background-color: #141418;
            border: 1px solid var(--card-border);
            border-radius: 0.85rem;
            padding: 0.75rem 1rem 0.75rem 2.6rem;
            color: #ffffff;
            font-size: 0.85rem;
            width: 100%;
        }

        .search-input::placeholder {
            color: #5d5d6a;
        }

        .search-input:focus {
            background-color: #17171d;
            border-color: var(--gold-border);
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(229, 190, 88, 0.15);
            color: #fff;
        }

        /* Horizontal Filter Pills */
        .filter-pills-container {
            display: flex;
            gap: 0.5rem;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            padding-bottom: 0.25rem;
            -webkit-overflow-scrolling: touch;
        }

        .filter-pills-container::-webkit-scrollbar {
            display: none;
        }

        .pill-btn {
            white-space: nowrap;
            background-color: #141418;
            border: 1px solid var(--card-border);
            color: var(--text-muted);
            border-radius: 50rem;
            padding: 0.45rem 0.9rem;
            font-size: 0.78rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .pill-btn.active {
            background-color: var(--gold-primary);
            color: #0d0d0f;
            border-color: var(--gold-primary);
        }

        .pill-badge {
            font-size: 0.68rem;
            padding: 0.1rem 0.4rem;
            border-radius: 50rem;
            background: rgba(255, 255, 255, 0.1);
        }

        .pill-btn.active .pill-badge {
            background: rgba(0, 0, 0, 0.2);
            color: #000000;
        }

        #bookingList {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.85rem;
        }

        @media (min-width: 768px) {
            #bookingList {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
        }

        @media (min-width: 1200px) {
            #bookingList {
                grid-template-columns: repeat(3, 1fr);
                gap: 1.25rem;
            }
        }

        /* Booking Cards */
        .booking-card {
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 1rem;
            padding: 1rem 1.15rem;
            margin-bottom: 0;
            transition: border-color 0.2s ease, transform 0.2s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .booking-card:hover {
            border-color: rgba(229, 190, 88, 0.4);
            transform: translateY(-2px);
        }

        .cust-name {
            font-weight: 800;
            font-size: 0.98rem;
            color: #ffffff;
            margin-bottom: 0.1rem;
        }

        .booking-code {
            font-size: 0.72rem;
            color: #626270;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Status Badges */
        .badge-status {
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.3rem 0.65rem;
            border-radius: 50rem;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .badge-status-menunggu {
            background-color: rgba(229, 190, 88, 0.15);
            color: var(--gold-primary);
            border: 1px solid rgba(229, 190, 88, 0.25);
        }

        .badge-status-menunggu .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: var(--gold-primary);
        }

        .badge-status-selesai {
            background-color: rgba(34, 197, 94, 0.15);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.25);
        }

        .badge-status-selesai .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #4ade80;
        }

        .badge-status-proses {
            background-color: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.25);
        }

        .badge-status-proses .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #60a5fa;
        }

        .card-detail-item {
            font-size: 0.82rem;
            color: #b0b0bc;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .card-time {
            font-weight: 800;
            font-size: 0.95rem;
            color: var(--gold-primary);
        }

        .card-price {
            font-weight: 700;
            font-size: 0.88rem;
            color: #d1d1db;
        }

        .divider-line {
            border-top: 1px solid #1c1c24;
            margin: 0.6rem 0;
        }
    </style>

    <style>
        html {
            color-scheme: dark;
        }

        html[data-theme="light"] {
            color-scheme: light;
            --bg-app: #f2f6fb;
            --surface: #ffffff;
            --surface-2: #edf3fa;
            --surface-3: #e7edf5;
            --text: #111827;
            --text-muted: #56657a;
            --border: #dfe7f1;
            --gold-primary: #d5a22d;
            --gold-secondary: #be8a1d;
            --gold-border: rgba(213, 162, 45, 0.28);
            --shadow: rgba(15,23,42,0.08);
        }

        html[data-theme="dark"] {
            color-scheme: dark;
            --bg-app: #0a0a0c;
            --surface: #141418;
            --surface-2: #1b1b22;
            --surface-3: #23232c;
            --text: #ffffff;
            --text-muted: #8e8e9a;
            --border: #23232c;
            --gold-primary: #e5be58;
            --gold-secondary: #c99b33;
            --gold-border: rgba(229, 190, 88, 0.3);
            --shadow: rgba(0,0,0,0.65);
        }

        body {
            background: var(--bg-app);
            color: var(--text);
            transition: background 0.25s ease, color 0.25s ease;
        }

        .app-container,
        .stats-card,
        .booking-card,
        .category-switcher,
        .search-box,
        .search-input,
        .filter-pills-container,
        .pill-btn,
        .stats-card,
        .btn-circle-icon,
        .card,
        .btn-gold-action {
            transition: all 0.25s ease;
        }

        .app-container,
        .stats-card,
        .booking-card,
        .category-switcher,
        .search-box,
        .search-input,
        .filter-pills-container,
        .pill-btn,
        .btn-circle-icon,
        .card,
        .navbar {
            background-color: var(--surface);
            border-color: var(--border);
            color: var(--text);
        }

        .header-title,
        .stats-value,
        .cust-name,
        .booking-code,
        .text-white,
        .header-date,
        .search-input,
        .modal-title {
            color: var(--text) !important;
        }

        .stats-card,
        .booking-card,
        .btn-circle-icon,
        .card,
        .search-input,
        .search-box {
            box-shadow: 0 8px 16px var(--shadow);
        }

        .search-input,
        .form-control,
        .form-select,
        .form-dark-input {
            background-color: var(--surface-2);
            color: var(--text);
            border-color: var(--border);
        }

        .search-input::placeholder,
        .stats-sub,
        .text-muted,
        .header-sub,
        .card-detail-item,
        .small {
            color: var(--text-muted) !important;
        }

        .chart-bar-bg {
            background-color: var(--surface-2);
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

    <div class="app-container">
        <!-- Top Navigation / Header -->
        <header class="d-flex justify-content-between align-items-center mb-1">
            <div>
                <div class="header-sub">GLOWCUT &nbsp;•&nbsp; KASIR</div>
                <h1 class="header-title font-serif">Dashboard Kasir</h1>
                <div class="header-date">Kamis, 24 September 2026</div>
            </div>
            <div class="d-flex gap-2">
                <a href="#" class="btn-circle-icon" title="Riwayat Transaksi">
                    <i class="fa-regular fa-clock"></i>
                </a>
                <a href="/logout" class="btn-circle-icon" title="Keluar">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </a>
            </div>
        </header>

        <!-- Daily Summary Cards -->
        <section class="stats-card">
            <div class="row text-center g-0">
                <div class="col-4 border-end border-secondary border-opacity-25">
                    <div class="stats-label">BOOKING</div>
                    <div class="stats-value">Rp 204k</div>
                    <div class="stats-sub">2 selesai</div>
                </div>
                <div class="col-4 border-end border-secondary border-opacity-25">
                    <div class="stats-label">WALK-IN</div>
                    <div class="stats-value">Rp 215k</div>
                    <div class="stats-sub">3 transaksi</div>
                </div>
                <div class="col-4">
                    <div class="stats-label">TOTAL</div>
                    <div class="stats-value">Rp 419k</div>
                    <div class="stats-sub">Hari ini</div>
                </div>
            </div>
        </section>

        <section class="row g-2 align-items-center mb-3">
            <div class="col-12 col-md-4 col-lg-3 order-1">
                <button class="btn btn-gold-action d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#walkInModal">
                    <i class="fa-solid fa-plus fs-6"></i>
                    <span>Catat Walk-in</span>
                </button>
            </div>
            <div class="col-12 col-md-4 col-lg-3 order-2">
                <div class="category-switcher">
                    <button class="cat-btn active" onclick="switchCategory('barber', this)">
                        <span>✂ Barber</span>
                        <span class="cat-badge">4</span>
                    </button>
                    <button class="cat-btn" onclick="switchCategory('mua', this)">
                        <span>💄 MUA</span>
                        <span class="cat-badge">2</span>
                    </button>
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-6 order-3">
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchInput" onkeyup="filterItems()" class="search-input" placeholder="Cari nama, kode, atau layanan...">
                </div>
            </div>
        </section>

        <!-- Status Filter Pills -->
        <section class="filter-pills-container mb-3">
            <button class="pill-btn active" onclick="filterStatus('all', this)">
                <span>Semua</span>
                <span class="pill-badge">4</span>
            </button>
            <button class="pill-btn" onclick="filterStatus('menunggu', this)">
                <span>Menunggu</span>
                <span class="pill-badge">2</span>
            </button>
            <button class="pill-btn" onclick="filterStatus('proses', this)">
                <span>Proses</span>
                <span class="pill-badge">0</span>
            </button>
            <button class="pill-btn" onclick="filterStatus('selesai', this)">
                <span>Selesai</span>
                <span class="pill-badge">2</span>
            </button>
        </section>

        <!-- Orders Queue List / Grid -->
        <main id="bookingList">
            
            <!-- Card 1 -->
            <div class="booking-card barber-item status-menunggu">
                <div>
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="cust-name">Ahmad Rizky</div>
                            <div class="booking-code">GC-X9K3PM2A</div>
                        </div>
                        <span class="badge-status badge-status-menunggu">
                            <span class="dot"></span> Menunggu
                        </span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-2.5">
                        <div class="card-detail-item">
                            <i class="fa-solid fa-scissors text-gold"></i>
                            <span>Haircut Classic</span>
                        </div>
                        <div class="card-time">10:00</div>
                    </div>
                </div>

                <div>
                    <div class="divider-line"></div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-detail-item">
                            <i class="fa-solid fa-user text-muted"></i>
                            <span class="text-white-50">Budi S.</span>
                        </div>
                        <div class="card-price">Rp 82.500</div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="booking-card barber-item status-menunggu">
                <div>
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="cust-name">Kevin Pratama</div>
                            <div class="booking-code">GC-B2C3D4E5</div>
                        </div>
                        <span class="badge-status badge-status-menunggu">
                            <span class="dot"></span> Menunggu
                        </span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-2.5">
                        <div class="card-detail-item">
                            <i class="fa-solid fa-scissors text-gold"></i>
                            <span>Fade Cut</span>
                        </div>
                        <div class="card-time">13:00</div>
                    </div>
                </div>

                <div>
                    <div class="divider-line"></div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-detail-item">
                            <i class="fa-solid fa-user text-muted"></i>
                            <span class="text-white-50">Dimas R.</span>
                        </div>
                        <div class="card-price">Rp 99.000</div>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="booking-card barber-item status-selesai">
                <div>
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="cust-name">Bagas W.</div>
                            <div class="booking-code">GC-J0K1L2M3</div>
                        </div>
                        <span class="badge-status badge-status-selesai">
                            <span class="dot"></span> Selesai
                        </span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-2.5">
                        <div class="card-detail-item">
                            <i class="fa-solid fa-scissors text-gold"></i>
                            <span>Haircut & Beard Trim</span>
                        </div>
                        <div class="card-time">09:00</div>
                    </div>
                </div>

                <div>
                    <div class="divider-line"></div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-detail-item">
                            <i class="fa-solid fa-user text-muted"></i>
                            <span class="text-white-50">Budi S.</span>
                        </div>
                        <div class="card-price">Rp 120.000</div>
                    </div>
                </div>
            </div>

            <!-- Card 4 (MUA Example) -->
            <div class="booking-card mua-item status-selesai d-none">
                <div>
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="cust-name">Siti Nurhaliza</div>
                            <div class="booking-code">GC-MUA9811</div>
                        </div>
                        <span class="badge-status badge-status-selesai">
                            <span class="dot"></span> Selesai
                        </span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-2.5">
                        <div class="card-detail-item">
                            <i class="fa-solid fa-wand-magic-sparkles text-danger"></i>
                            <span>Makeup Natural Wisuda</span>
                        </div>
                        <div class="card-time">08:00</div>
                    </div>
                </div>

                <div>
                    <div class="divider-line"></div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-detail-item">
                            <i class="fa-solid fa-user text-muted"></i>
                            <span class="text-white-50">Anisa MUA</span>
                        </div>
                        <div class="card-price">Rp 350.000</div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1090;">
        <div id="walkInToast" class="toast align-items-center text-bg-success border-0 rounded-3 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-check fs-5"></i>
                    <div>
                        <strong class="d-block">Berhasil!</strong>
                        <span class="small">Pelanggan walk-in telah ditambahkan.</span>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Modal Catat Pelanggan Walk-in -->
    <div class="modal fade" id="walkInModal" tabindex="-1" aria-labelledby="walkInModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: #141418; border: 1px solid var(--card-border); border-radius: 1.25rem;">
                <div class="modal-header border-bottom border-secondary border-opacity-25 px-4 pt-4 pb-3">
                    <h5 class="modal-title font-serif text-white fs-4" id="walkInModalLabel">Catat Walk-in</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="walkInForm" onsubmit="addWalkInCustomer(event)">
                        <div class="mb-3">
                            <label class="form-label small text-muted font-bold">Nama Pelanggan</label>
                            <input type="text" required class="form-control search-input" style="padding-left: 1rem;" placeholder="Masukkan nama pelanggan">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted font-bold">Kategori Layanan</label>
                            <select class="form-select search-input" style="padding-left: 1rem;">
                                <option value="barber">Barbershop</option>
                                <option value="mua">MUA Wisuda</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted font-bold">Pilih Layanan</label>
                            <select class="form-select search-input" style="padding-left: 1rem;">
                                <option value="Haircut Classic - 82500">Haircut Classic - Rp 82.500</option>
                                <option value="Fade Cut - 99000">Fade Cut - Rp 99.000</option>
                                <option value="Haircut & Beard Trim - 120000">Haircut & Beard Trim - Rp 120.000</option>
                                <option value="Makeup Wisuda - 350000">Makeup Wisuda - Rp 350.000</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted font-bold">Kapster / Staff</label>
                            <input type="text" required class="form-control search-input" style="padding-left: 1rem;" placeholder="Nama kapster/MUA">
                        </div>
                        <button type="submit" class="btn btn-gold-action mt-2">Simpan & Masukkan Antrean</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Filter & Interactive Logic -->
    <script>
        let currentCategory = 'barber';
        let currentStatus = 'all';

        function switchCategory(cat, element) {
            currentCategory = cat;
            document.querySelectorAll('.cat-btn').forEach(btn => btn.classList.remove('active'));
            element.classList.add('active');
            filterItems();
        }

        function filterStatus(status, element) {
            currentStatus = status;
            document.querySelectorAll('.pill-btn').forEach(btn => btn.classList.remove('active'));
            element.classList.add('active');
            filterItems();
        }

        function filterItems() {
            const searchQuery = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.booking-card');

            cards.forEach(card => {
                const matchesCategory = card.classList.contains(`${currentCategory}-item`);
                const matchesStatus = (currentStatus === 'all') || card.classList.contains(`status-${currentStatus}`);
                const cardText = card.innerText.toLowerCase();
                const matchesSearch = cardText.includes(searchQuery);

                if (matchesCategory && matchesStatus && matchesSearch) {
                    card.classList.remove('d-none');
                } else {
                    card.classList.add('d-none');
                }
            });
        }

        function addWalkInCustomer(e) {
            e.preventDefault();
            
            // Hide modal
            const modalEl = document.getElementById('walkInModal');
            if (modalEl && window.bootstrap && window.bootstrap.Modal) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }

            // Show Toast notification instead of alert()
            const toastEl = document.getElementById('walkInToast');
            if (toastEl && window.bootstrap && window.bootstrap.Toast) {
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
            }
        }
    </script>
</body>
</html>