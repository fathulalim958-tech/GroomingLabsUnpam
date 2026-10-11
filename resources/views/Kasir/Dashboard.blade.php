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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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

            .booking-card[role="button"] {
                cursor: pointer;
            }

            .booking-card[role="button"]:focus-visible {
                outline: 2px solid var(--gold-primary);
                outline-offset: 3px;
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
        @include('Kasir.partials.page-header', [
            'title' => 'Dashboard Kasir',
            'date' => \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y'),
            'backRoute' => null,
            'userName' => \Illuminate\Support\Facades\Auth::user()->name ?? 'Kasir',
            'userRole' => isset($loggedInEmployee) ? $loggedInEmployee->position : (\Illuminate\Support\Facades\Auth::user()->role === 'kasir' ? 'Kasir' : \Illuminate\Support\Facades\Auth::user()->role)
        ])

        <!-- Daily Summary Cards -->
        <section class="stats-card">
            <div class="row text-center g-0">
                @include('Kasir.partials.summary-card', [
                    'label' => 'BOOKING',
                    'value' => 'Rp ' . number_format($onlineRevenue / 1000, 0, ',', '.') . 'k',
                    'caption' => $onlineCompletedCount . ' selesai'
                ])
                @include('Kasir.partials.summary-card', [
                    'label' => 'WALK-IN',
                    'value' => 'Rp ' . number_format($walkInRevenue / 1000, 0, ',', '.') . 'k',
                    'caption' => $walkInCompletedCount . ' transaksi'
                ])
                @include('Kasir.partials.summary-card', [
                    'label' => 'TOTAL',
                    'value' => 'Rp ' . number_format($totalRevenue / 1000, 0, ',', '.') . 'k',
                    'caption' => 'Hari ini'
                ])
            </div>
        </section>

        <section class="row g-2 align-items-center mb-3">
            <div class="col-12 col-md-4 col-lg-3 order-1">
                <a href="{{ route('kasir.booking.walkin') }}" class="btn btn-gold-action d-flex align-items-center justify-content-center gap-2 text-decoration-none">
                    <i class="fa-solid fa-plus fs-6"></i>
                    <span>Catat Walk-in</span>
                </a>
            </div>
            <div class="col-12 col-md-4 col-lg-3 order-2">
                @include('Kasir.partials.category-switcher')
            </div>
            <div class="col-12 col-md-4 col-lg-6 order-3">
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchInput" onkeyup="filterItems()" class="search-input" placeholder="Cari nama, kode, atau layanan...">
                </div>
            </div>
        </section>

        @include('Kasir.partials.filter-pills')

        <!-- Orders Queue List / Grid -->
        <main id="bookingList">

            @foreach ($walkInBookings as $booking)
                @include('Kasir.partials.booking-card', [
                    'type' => $booking->category,
                    'status' => match ($booking->status) {
                        'confirmed', 'in_progress' => 'proses',
                        'completed' => 'selesai',
                        default => 'menunggu',
                    },
                    'customerName' => $booking->customer_name ?? 'Pelanggan online',
                    'bookingCode' => ($booking->cashier_id ? 'WI-' : 'BK-') . $booking->id,
                    'icon' => ($booking->category ?? 'barber') === 'mua' ? 'fa-wand-magic-sparkles' : 'fa-scissors',
                    'service' => $booking->service_name ?? 'Layanan booking',
                    'time' => $booking->appointment_at?->format('H:i') ?? '-',
                    'staff' => $booking->staff_name ?? 'Belum ditentukan',
                    'price' => $booking->service_price === null ? '-' : 'Rp ' . number_format($booking->service_price, 0, ',', '.'),
                    'completeUrl' => route('kasir.booking.complete', $booking),
                ])
            @endforeach

            @if ($walkInBookings->isEmpty())
                <p class="text-center text-muted py-4" style="grid-column: 1 / -1;">Belum ada booking hari ini.</p>
            @endif

        </main>
    </div>

    <div class="modal fade" id="bookingDetailModal" tabindex="-1" aria-labelledby="bookingDetailTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background:#141418;border:1px solid var(--card-border);border-radius:1rem;color:#fff">
                <div class="modal-header border-secondary">
                    <div>
                        <div class="header-sub">DETAIL BOOKING</div>
                        <h2 class="modal-title fs-5" id="bookingDetailTitle"></h2>
                        <div class="small text-muted" id="bookingDetailCode"></div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <dl class="row mb-0">
                        <dt class="col-4 text-muted">Layanan</dt><dd class="col-8" id="bookingDetailService"></dd>
                        <dt class="col-4 text-muted">Waktu</dt><dd class="col-8" id="bookingDetailTime"></dd>
                        <dt class="col-4 text-muted">Staff</dt><dd class="col-8" id="bookingDetailStaff"></dd>
                        <dt class="col-4 text-muted">Harga</dt><dd class="col-8" id="bookingDetailPrice"></dd>
                        <dt class="col-4 text-muted">Status</dt><dd class="col-8" id="bookingDetailStatus"></dd>
                    </dl>
                    <form id="completeBookingForm" method="POST" class="mt-3">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-gold-action">Selesaikan Booking</button>
                    </form>
                    <div id="bookingAlreadyComplete" class="alert alert-success d-none mt-3 mb-0">Booking ini sudah selesai.</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentCategory = 'barber';
        let currentStatus = 'all';

        function switchCategory(category, button) {
            currentCategory = category;
            document.querySelectorAll('.cat-btn').forEach((item) => {
                item.classList.toggle('active', item === button);
            });
            filterItems();
        }

        function filterStatus(status, button) {
            currentStatus = status;
            document.querySelectorAll('.pill-btn').forEach((item) => {
                item.classList.toggle('active', item === button);
            });
            filterItems();
        }

        function filterItems() {
            const searchInput = document.getElementById('searchInput');
            const query = (searchInput ? searchInput.value : '').toLowerCase();
            const cards = document.querySelectorAll('.booking-card');

            cards.forEach((card) => {
                const matchesCategory = currentCategory === 'all' || card.dataset.category === currentCategory;
                const matchesStatus = currentStatus === 'all' || card.dataset.status === currentStatus;
                const matchesQuery = !query || card.textContent.toLowerCase().includes(query);

                card.classList.toggle('d-none', !(matchesCategory && matchesStatus && matchesQuery));
            });
        }

        function showBookingDetails(card) {
            document.getElementById('bookingDetailTitle').textContent = card.dataset.customer;
            document.getElementById('bookingDetailCode').textContent = card.dataset.code;
            document.getElementById('bookingDetailService').textContent = card.dataset.service;
            document.getElementById('bookingDetailTime').textContent = card.dataset.time;
            document.getElementById('bookingDetailStaff').textContent = card.dataset.staff;
            document.getElementById('bookingDetailPrice').textContent = card.dataset.price;
            document.getElementById('bookingDetailStatus').textContent = card.dataset.status;
            document.getElementById('completeBookingForm').action = card.dataset.completeUrl;

            const status = card.dataset.status;
            const category = card.dataset.category;
            const isComplete = status === 'selesai';
            
            const btn = document.querySelector('#completeBookingForm button[type="submit"]');
            if (status === 'menunggu') {
                btn.textContent = 'Proses Booking';
            } else {
                btn.textContent = 'Selesaikan Booking';
            }

            document.getElementById('completeBookingForm').classList.toggle('d-none', isComplete);
            document.getElementById('bookingAlreadyComplete').classList.toggle('d-none', !isComplete);
            
            // ROLE LOCK LOGIC
            const userPosition = "{{ $loggedInEmployee->position ?? '' }}";
            let canProcess = true;
            let warningText = '';
            
            if (userPosition === 'Hair Stylist' && category === 'mua') {
                canProcess = false;
                warningText = 'Hair Stylist tidak bisa memproses booking MUA.';
            } else if (userPosition === 'MUA Artist' && category === 'barber') {
                canProcess = false;
                warningText = 'MUA Artist tidak bisa memproses booking Barber.';
            }
            
            let warningEl = document.getElementById('roleLockWarning');
            if (!warningEl) {
                warningEl = document.createElement('div');
                warningEl.id = 'roleLockWarning';
                warningEl.className = 'alert alert-danger mt-3 mb-0';
                document.getElementById('completeBookingForm').parentElement.appendChild(warningEl);
            }
            
            if (!canProcess && !isComplete) {
                btn.disabled = true;
                btn.classList.add('d-none');
                warningEl.textContent = warningText;
                warningEl.classList.remove('d-none');
            } else {
                btn.disabled = false;
                btn.classList.remove('d-none');
                warningEl.classList.add('d-none');
            }

            bootstrap.Modal.getOrCreateInstance(document.getElementById('bookingDetailModal')).show();
        }

        filterItems();
    </script>
</body>
</html>
