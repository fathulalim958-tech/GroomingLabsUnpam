<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Saya - GroomingLabs</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --bg-dark: #121212;
            --card-bg: #1e1e1e;
            --gold-primary: #d4af37;
            --text-muted: #a0a0a0;
            --badge-mendatang-bg: rgba(212, 175, 55, 0.15);
            --badge-mendatang-text: #d4af37;
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

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid var(--gold-primary);
        }

        /* --- NAVBAR MOBILE --- */
        .mobile-header {
            padding: 15px 0 5px 0;
        }

        .btn-back {
            color: #ffffff;
            text-decoration: none;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-back:hover {
            color: var(--gold-primary);
        }

        /* --- FILTER PILLS --- */
        .filter-container {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            white-space: nowrap;
            padding-bottom: 5px;
            margin-bottom: 20px;
            scrollbar-width: none;
        }

        .filter-container::-webkit-scrollbar {
            display: none;
        }

        .pill-btn {
            background-color: #2a2a2a;
            color: #ffffff;
            border: 1px solid #333333;
            border-radius: 20px;
            padding: 8px 20px;
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .pill-btn.active {
            background-color: #2a220a;
            color: var(--gold-primary);
            border: 1px solid var(--gold-primary);
        }

        /* --- CARD BOOKING --- */
        .booking-card {
            background-color: var(--card-bg);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #2a2a2a;
            height: 100%;
        }

        .booking-code {
            color: var(--gold-primary);
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .status-badge {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 12px;
        }

        .status-mendatang {
            background-color: var(--badge-mendatang-bg);
            color: var(--badge-mendatang-text);
        }

        .service-title {
            font-size: 1.15rem;
            font-weight: 700;
            margin-top: 6px;
            margin-bottom: 12px;
        }

        .info-row {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .price-tag {
            color: var(--gold-primary);
            font-weight: 800;
            font-size: 1.1rem;
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
            .mobile-only {
                display: none !important;
            }

            .desktop-only {
                display: block !important;
            }
        }

        @media (max-width: 991.98px) {
            .mobile-only {
                display: flex !important;
            }

            .mobile-only-block {
                display: block !important;
            }

            .desktop-only {
                display: none !important;
            }
        }
        .text-muted {
        color: var(--text-muted) !important;
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
                        <a class="nav-link active text-gold fw-semibold" href="/dashboard/pelanggan/order">
                            <i class="fa-solid fa-calendar-check me-1"></i> Order
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50 fw-semibold" href="/dashboard/pelanggan/riwayat">
                            <i class="fa-solid fa-clock-rotate-left me-1"></i> Riwayat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="/dashboard/pelanggan/profile"><i class="fa-solid fa-user me-1"></i>
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
    <div class="container mt-2 mt-lg-4">
        
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px;">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Header Mobile Only -->
        <div class="mobile-header mobile-only-block">
            <a href="#" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        <h2 class="fw-bold my-3 my-lg-4">Booking Saya</h2>

        <!-- Sub Filter -->
        <div class="filter-container">
            <a href="javascript:void(0)" class="pill-btn active" onclick="filterCat('all', this)">Semua</a>
            <a href="javascript:void(0)" class="pill-btn" onclick="filterCat('barber', this)"><i class="fa-solid fa-scissors"></i> Barber</a>
            <a href="javascript:void(0)" class="pill-btn" onclick="filterCat('mua', this)"><i class="fa-solid fa-wand-magic-sparkles"></i> MUA</a>
        </div>

        <!-- LIST BOOKING (RESPONSIF GRID LAPTOP & HP) -->
        <div class="row">
            @forelse($bookings as $booking)
            <!-- Item -->
            <div class="col-12 col-md-6 col-lg-6 booking-item" data-category="{{ $booking->category ?? 'barber' }}">
                <div class="booking-card">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="booking-code">GL-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
                        <span class="status-badge status-mendatang">{{ ucfirst($booking->status) }}</span>
                    </div>
                    <div class="service-title">{{ $booking->service_name }}</div>
                    <div class="info-row">
                        <i class="fa-solid fa-user text-secondary"></i> {{ $booking->staff_name ?? 'Siapa Saja (Bebas)' }} &nbsp;•&nbsp;
                        <i class="fa-solid fa-location-dot text-danger"></i> Pusat
                    </div>
                    <div class="d-flex justify-content-between align-items-end mt-3 pt-2 border-top border-dark">
                        <div class="info-row mb-0">
                            <i class="fa-regular fa-calendar-days text-primary"></i> {{ \Carbon\Carbon::parse($booking->appointment_at)->locale('id')->isoFormat('dddd, D MMM Y HH:mm') }}
                        </div>
                        <div class="price-tag">Rp {{ number_format($booking->service_price, 0, ',', '.') }}</div>
                    </div>
                    @if(in_array($booking->status, ['pending', 'confirmed']))
                                        <div class="mt-3 pt-2 border-top border-dark text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger" style="border-radius: 8px; font-weight: 600; font-size: 0.75rem;" onclick="showCancelModal('{{ $booking->id }}')"><i class="fa-solid fa-xmark me-1"></i>Batalkan Pesanan</button>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted mt-5 mb-5">
                <i class="fa-regular fa-calendar-xmark fs-1 mb-3"></i>
                <p>Belum ada jadwal pemesanan. Yuk booking sekarang!</p>
            </div>
            @endforelse
        </div>

    </div>

    <!-- BOTTOM NAV MOBILE ONLY -->
    <div class="bottom-nav mobile-only">
        <a href="/dashboard/pelanggan" class="nav-item-custom">
            <i class="fa-solid fa-house"></i>
            Beranda
        </a>
        <a href="/dashboard/pelanggan/order" class="nav-item-custom active">
            <i class="fa-solid fa-calendar-check"></i>
            Order
        </a>
        <a href="/dashboard/pelanggan/riwayat" class="nav-item-custom">
            <i class="fa-solid fa-clock-rotate-left"></i>
            Riwayat
        </a>
        <a href="/dashboard/pelanggan/profile" class="nav-item-custom">
            <i class="fa-solid fa-user"></i>
            Profil
        </a>
    </div>

    <script>
        function filterCat(category, element) {
            document.querySelectorAll('.pill-btn').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
            
            document.querySelectorAll('.booking-item').forEach(item => {
                if (category === 'all' || item.getAttribute('data-category') === category) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>


    <div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: var(--card-bg); border: 1px solid rgba(255,255,255,0.1);">
                <div class="modal-header border-bottom border-secondary border-opacity-25">
                    <h5 class="modal-title text-white">Batalkan Pesanan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-white-50">
                    Apakah Anda yakin ingin membatalkan pesanan ini? Aksi ini tidak dapat dikembalikan.
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tidak, Kembali</button>
                    <form id="cancelForm" method="POST" action="">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showCancelModal(bookingId) {
            const form = document.getElementById('cancelForm');
            // Assuming the route is /dashboard/pelanggan/booking/{booking}/cancel
            form.action = `/dashboard/pelanggan/booking/${bookingId}/cancel`;
            const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
            modal.show();
        }
    </script>
</body>

</html>
