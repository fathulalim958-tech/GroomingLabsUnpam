<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - GroomingLabs</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-dark: #121212;
            --card-bg: #1e1e1e;
            --gold-primary: #d4af37;
            --gold-light: #f3e5ab;
            --text-muted: #a0a0a0;
            --border-color: #2a2a2a;
        }

        body {
            background-color: var(--bg-dark);
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            padding-bottom: 90px;
        }

        @media (min-width: 992px) {
            body {
                padding-bottom: 40px;
            }
        }

        .text-gold {
            color: var(--gold-primary) !important;
        }

        /* --- NAVBAR LAPTOP / DESKTOP --- */
        .desktop-navbar {
            background-color: #161616;
            border-bottom: 1px solid #282828;
            padding: 12px 0;
        }

        .desktop-navbar .nav-link {
            color: #a0a0a0;
            font-weight: 600;
            font-size: 0.95rem;
            margin: 0 10px;
            transition: all 0.2s;
        }

        .desktop-navbar .nav-link:hover, 
        .desktop-navbar .nav-link.active {
            color: #ffffff;
        }

        .desktop-navbar .nav-link.active i {
            color: var(--gold-primary);
        }

        .user-profile-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
        }

        .user-avatar-nav {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid var(--gold-primary);
        }

        /* --- HEADER MOBILE --- */
        .mobile-header-subtitle {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            color: var(--gold-primary);
            text-transform: uppercase;
        }

        /* --- AVATAR PROFIL --- */
        .profile-avatar-container {
            position: relative;
            display: inline-block;
        }

        .profile-avatar {
            width: 90px;
            height: 90px;
            border-radius: 24px;
            object-fit: cover;
            border: 2px solid var(--gold-primary);
        }

        .user-name {
            font-size: 1.5rem;
            font-weight: 800;
            margin-top: 10px;
            margin-bottom: 2px;
        }

        .user-phone {
            color: var(--text-muted);
            font-size: 0.88rem;
            margin-bottom: 12px;
        }

        .badge-status-pelanggan {
            background-color: rgba(212, 175, 55, 0.12);
            color: var(--gold-primary);
            border: 1px solid rgba(212, 175, 55, 0.4);
            border-radius: 20px;
            padding: 6px 16px;
            font-size: 0.78rem;
            font-weight: 700;
            display: inline-block;
        }

        /* --- STATS BOXES --- */
        .stat-card {
            background-color: var(--card-bg);
            border-radius: 16px;
            padding: 16px 10px;
            text-align: center;
            border: 1px solid var(--border-color);
            height: 100%;
        }

        .stat-card.total { border-color: rgba(212, 175, 55, 0.3); }
        .stat-card.selesai { border-color: rgba(40, 167, 69, 0.3); }
        .stat-card.dibatalkan { border-color: rgba(220, 53, 69, 0.3); }

        .stat-number {
            font-size: 1.4rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 4px;
            font-weight: 500;
        }

        /* --- SECTION BOXES --- */
        .section-box {
            background-color: var(--card-bg);
            border-radius: 20px;
            padding: 20px;
            border: 1px solid var(--border-color);
            margin-bottom: 20px;
        }

        .section-title-gold {
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--gold-primary);
            letter-spacing: 1.5px;
            margin-bottom: 18px;
            text-transform: uppercase;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            font-size: 0.88rem;
        }

        .info-label {
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .info-label i {
            width: 18px;
            text-align: center;
            font-size: 1rem;
        }

        .info-value {
            font-weight: 700;
            color: #ffffff;
        }

        /* --- MENU LIST LINK --- */
        .menu-list-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            color: #ffffff;
            text-decoration: none;
            border-bottom: 1px solid #282828;
            transition: background-color 0.2s;
        }

        .menu-list-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .menu-list-item:first-child {
            padding-top: 0;
        }

        .menu-icon-box {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background-color: #2a2a2a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .menu-text {
            font-size: 0.95rem;
            font-weight: 700;
        }

        .btn-keluar-akun {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3);
            border-radius: 16px;
            padding: 14px;
            width: 100%;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-keluar-akun:hover {
            background-color: #dc3545;
            color: #ffffff;
        }

        /* --- BOTTOM NAV MOBILE --- */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #1a1a1a;
            border-top: 1px solid #2a2a2a;
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
            z-index: 1000;
        }

        .nav-item-custom {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #777777;
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .nav-item-custom i {
            font-size: 1.2rem;
            margin-bottom: 3px;
        }

        .nav-item-custom.active {
            color: var(--gold-primary);
        }

        /* VISIBILITY CONTROLS */
        @media (min-width: 992px) {
            .mobile-only { display: none !important; }
            .desktop-only { display: block !important; }
            .profile-max-width { max-width: 700px; margin: 0 auto; }
        }

        @media (max-width: 991.98px) {
            .mobile-only { display: flex !important; }
            .mobile-only-block { display: block !important; }
            .desktop-only { display: none !important; }
        }
    </style>
</head>
<body>

    <!-- NAVBAR DESKTOP / LAPTOP -->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark border-bottom border-secondary border-opacity-25 sticky-top d-none d-md-block"
        style="background-color: var(--bg-dark) !important;">
        <div class="container-xl">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <img src="path/ke/logo-anda.png" alt="Logo" class="brand-logo" style="height: 40px; width: auto;">
                <span class="fw-bold tracking-wide">
                    GROOMINGLABS <br>
                    <span class="text-gold">BARBER & MUA</span>
                </span>
            </a>
            <div class="collapse navbar-collapse" id="desktopNavbar">
                <ul class="navbar-menu navbar-nav mx-auto mb-2 mb-lg-0 gap-3">
                    <li class="nav-item">
                        <a class="nav-link text-white-50 fw-semibold" href="/dashboard/pelanggan">
                            <i class="fa-solid fa-house me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="/dashboard/pelanggan/order">
                            <i class="fa-solid fa-calendar-check me-1"></i> Order
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50 fw-semibold" href="/dashboard/pelanggan/riwayat">
                            <i class="fa-solid fa-clock-rotate-left me-1"></i> Riwayat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-white  text-white-50" href="/dashboard/pelanggan/profile"><i class="fa-solid fa-user me-1"></i>
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
                        <div class="fw-bold text-white">{{ Auth::user()->name ?? 'Pelanggan' }} 👋</div>
                    </div>
                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80"
                        alt="Profile" class="rounded-circle border border-2 border-warning" width="42"
                        height="42" style="object-fit: cover;">
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENT MAIN CONTAINER -->
    <div class="container mt-3 mt-lg-4 profile-max-width">
        
        <!-- Header Mobile Only -->
        <div class="mobile-only-block mb-3 text-start">
            <span class="mobile-header-subtitle">GROOMINGLABS • PROFIL</span>
        </div>

        <!-- USER PROFILE CARD HEADER -->
        <div class="text-center mb-4">
            <div class="profile-avatar-container">
                <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=300&auto=format&fit=crop&q=80" alt="Ahmad Rizky" class="profile-avatar">
            </div>
            <h3 class="user-name">{{ Auth::user()->name ?? 'Pelanggan' }}</h3>
            <div class="user-email">{{ Auth::user()->email ?? 'Pelanggan' }}</div>
            <div class="badge-status-pelanggan">
                Pelanggan Setia &nbsp;•&nbsp; 12 booking
            </div>
        </div>

        <!-- STATS ROW (3 BOXES) -->
        <div class="row g-2 mb-4">
            <div class="col-4">
                <div class="stat-card total">
                    <div class="stat-number text-gold">12</div>
                    <div class="stat-label">Total Booking</div>
                </div>
            </div>
            <div class="col-4">
                <div class="stat-card selesai">
                    <div class="stat-number text-success">10</div>
                    <div class="stat-label">Selesai</div>
                </div>
            </div>
            <div class="col-4">
                <div class="stat-card dibatalkan">
                    <div class="stat-number text-danger">2</div>
                    <div class="stat-label">Dibatalkan</div>
                </div>
            </div>
        </div>

        <!-- INFORMASI AKUN BOX -->
        <div class="section-box">
            <div class="section-title-gold">INFORMASI AKUN</div>
            
            <div class="info-item">
                <div class="info-label">
                    <i class="fa-solid fa-user text-secondary"></i> Nama
                </div>
                <div class="info-value">{{ Auth::user()->name ?? 'Pelanggan' }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">
                    <i class="fa-solid fa-phone text-danger"></i> Telepon
                </div>
                <div class="info-value">0812 3456 789</div>
            </div>

            <div class="info-item">
                <div class="info-label">
                    <i class="fa-solid fa-envelope text-primary"></i> Email
                </div>
                <div class="info-value">{{ Auth::user()->email ?? 'pelanggan' }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">
                    <i class="fa-solid fa-calendar-days text-info"></i> Bergabung
                </div>
                <div class="info-value">Maret 2025</div>
            </div>
        </div>

        <!-- NAVIGASI / MENU LIST BOX -->
        <div class="section-box">
            <a href="/dashboard/pelanggan/order" class="menu-list-item">
                <div class="d-flex align-items-center gap-3">
                    <div class="menu-icon-box text-info">
                        <i class="fa-regular fa-calendar-days"></i>
                    </div>
                    <span class="menu-text">Booking Saya</span>
                </div>
                <i class="fa-solid fa-chevron-right text-muted" style="font-size: 0.8rem;"></i>
            </a>

            <a href="/dashboard/pelanggan" class="menu-list-item">
                <div class="d-flex align-items-center gap-3">
                    <div class="menu-icon-box text-warning">
                        <i class="fa-solid fa-house"></i>
                    </div>
                    <span class="menu-text">Beranda</span>
                </div>
                <i class="fa-solid fa-chevron-right text-muted" style="font-size: 0.8rem;"></i>
            </a>

            <a href="#" class="menu-list-item">
                <div class="d-flex align-items-center gap-3">
                    <div class="menu-icon-box text-gold">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <span class="menu-text">Ganti Password</span>
                </div>
                <i class="fa-solid fa-chevron-right text-muted" style="font-size: 0.8rem;"></i>
            </a>
        </div>

        <!-- TOMBOL KELUAR -->
        <div class="mb-4">
            <a href="/logout" class="btn-keluar-akun">
                <i class="fa-solid fa-right-from-bracket"></i> Keluar dari Akun
            </a>
        </div>

    </div>

    <!-- BOTTOM NAV MOBILE ONLY -->
    <div class="bottom-nav mobile-only">
        <a href="/dashboard/pelanggan" class="nav-item-custom">
            <i class="fa-solid fa-house"></i>
            Beranda
        </a>
        <a href="/dashboard/pelanggan/order" class="nav-item-custom">
            <i class="fa-solid fa-calendar-check"></i>
            Booking
        </a>
        <a href="/dashboard/pelanggan/riwayat" class="nav-item-custom">
            <i class="fa-solid fa-clock-rotate-left"></i>
            Riwayat
        </a>
        <a href="/dashboard/pelanggan/profile" class="nav-item-custom active">
            <i class="fa-solid fa-user"></i>
            Profil
        </a>
    </div>

</body>
</html>