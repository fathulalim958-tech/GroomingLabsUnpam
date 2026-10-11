<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GLOWCUT - Dashboard Admin</title>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts: DM Serif Display & Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        :root {
            --bg-dark: #08080b;
            --card-bg: #121217;
            --card-border: #202029;
            --card-hover: #181820;
            --gold-primary: #e5be58;
            --gold-secondary: #c99b33;
            --gold-subtle: rgba(229, 190, 88, 0.12);
            --gold-border: rgba(229, 190, 88, 0.3);
            --purple-btn: #6343ce;
            --purple-btn-hover: #7351df;
            --text-muted: #8c8c9a;
            --accent-green: #34d399;
            --accent-red: #f87171;
            --accent-cyan: #38bdf8;
            --accent-pink: #f472b6;
        }

        body {
            background-color: var(--bg-dark);
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .font-serif {
            font-family: 'DM Serif Display', serif;
        }

        .app-container {
            width: 100%;
            max-width: 100%;
            min-height: 100vh;
            padding: 1.25rem 1rem 3rem 1rem;
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
                max-width: 980px;
                padding: 2rem;
            }
        }

        .dropdown-item:hover, .dropdown-item:focus {
            background-color: var(--card-hover) !important;
            color: var(--gold-primary) !important;
        }

        @media (min-width: 1200px) {
            .app-container {
                max-width: 1240px;
                padding: 2.25rem 2.5rem;
            }
        }

        .header-sub {
            font-size: 0.72rem;
            letter-spacing: 2.5px;
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
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background-color: #16161d;
            border: 1px solid #282832;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-circle-icon:hover {
            background-color: #22222c;
            color: var(--gold-primary);
            border-color: var(--gold-primary);
            transform: translateY(-1px);
        }

        .stat-card {
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 1.15rem;
            padding: 1.15rem;
            height: 100%;
            position: relative;
            transition: transform 0.2s ease, border-color 0.2s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            border-color: rgba(229, 190, 88, 0.35);
        }

        .stat-card-gold {
            border-color: var(--gold-border);
            background: linear-gradient(145deg, #181510 0%, #121217 100%);
        }

        .icon-badge {
            width: 38px;
            height: 38px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .trend-indicator {
            font-size: 0.72rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.2rem;
        }

        .trend-up {
            color: var(--accent-green);
        }

        .trend-down {
            color: var(--accent-red);
        }

        .stat-val {
            font-size: 1.65rem;
            font-weight: 800;
            margin-top: 0.6rem;
            margin-bottom: 0.1rem;
            letter-spacing: -0.5px;
        }

        .val-gold { color: var(--gold-primary); }
        .val-cyan { color: var(--accent-cyan); }
        .val-purple { color: #a78bfa; }
        .val-pink { color: var(--accent-pink); }

        .stat-lbl {
            font-size: 0.82rem;
            font-weight: 700;
            color: #d1d1dc;
            margin-bottom: 0.15rem;
        }

        .stat-subtext {
            font-size: 0.73rem;
            color: var(--text-muted);
        }

        .btn-action-purple {
            background: linear-gradient(135deg, #6d4be0 0%, #5235b8 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            border-radius: 1rem;
            padding: 0.95rem 1.25rem;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            box-shadow: 0 4px 18px rgba(99, 67, 206, 0.25);
            transition: all 0.2s ease;
        }

        .btn-action-purple:hover {
            background: linear-gradient(135deg, #7b58f0 0%, #5d3dc9 100%);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 22px rgba(99, 67, 206, 0.35);
        }

        .btn-action-gold {
            background: linear-gradient(135deg, #ebd178 0%, #e5be58 50%, #c99b33 100%);
            color: #0e0e11;
            font-weight: 800;
            font-size: 0.95rem;
            border: none;
            border-radius: 1rem;
            padding: 0.95rem 1.25rem;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            box-shadow: 0 4px 18px rgba(229, 190, 88, 0.25);
            transition: all 0.2s ease;
        }

        .btn-action-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 22px rgba(229, 190, 88, 0.35);
            color: #000;
        }

        .revenue-chart-card {
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 1.15rem;
            padding: 1.25rem 1.35rem;
        }

        .chart-day-col {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
        }

        .chart-bar-bg {
            width: 100%;
            max-width: 28px;
            height: 90px;
            background-color: #1b1b24;
            border-radius: 0.5rem;
            display: flex;
            align-items: flex-end;
            overflow: hidden;
            padding: 2px;
        }

        .chart-bar-fill {
            width: 100%;
            background-color: #31313e;
            border-radius: 0.35rem;
            transition: height 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .chart-day-col.active .chart-bar-fill {
            background: linear-gradient(180deg, #f0d479 0%, #c99b33 100%);
            box-shadow: 0 0 12px rgba(229, 190, 88, 0.4);
        }

        .chart-day-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--text-muted);
        }

        .chart-day-col.active .chart-day-label {
            color: var(--gold-primary);
            font-weight: 800;
        }

        .employee-card {
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 1rem;
            padding: 0.95rem 1.15rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.2s ease;
        }

        .employee-card:hover {
            border-color: rgba(229, 190, 88, 0.3);
            background-color: var(--card-hover);
        }

        .avatar-wrapper {
            position: relative;
            width: 48px;
            height: 48px;
            flex-shrink: 0;
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #333342;
        }

        .avatar-initial {
            display: grid;
            width: 100%;
            height: 100%;
            place-items: center;
            border: 1px solid #333342;
            border-radius: 50%;
            background: #382957;
            color: #c7a6ff;
            font-weight: 800;
        }

        .status-dot-active {
            position: absolute;
            bottom: 1px;
            right: 1px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #22c55e;
            border: 2px solid var(--card-bg);
        }

        .status-dot-off {
            position: absolute;
            bottom: 1px;
            right: 1px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #6b7280;
            border: 2px solid var(--card-bg);
        }

        .emp-name {
            font-weight: 800;
            font-size: 0.95rem;
            color: #ffffff;
            margin-bottom: 0.1rem;
        }

        .emp-role-barber {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--gold-primary);
        }

        .emp-role-mua {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--accent-pink);
        }

        .emp-stats-num {
            font-weight: 800;
            font-size: 0.9rem;
            color: #d1d1dc;
        }

        .emp-status-badge {
            font-size: 0.7rem;
            font-weight: 700;
            color: #4ade80;
        }

        /* Modal styling */
        .custom-modal-content {
            background-color: #141419;
            border: 1px solid var(--card-border);
            border-radius: 1.25rem;
        }

        .form-dark-input {
            background-color: #1a1a22;
            border: 1px solid #2a2a36;
            color: #ffffff;
            border-radius: 0.75rem;
            padding: 0.7rem 1rem;
        }

        .form-dark-input:focus {
            background-color: #1e1e28;
            border-color: var(--gold-primary);
            box-shadow: 0 0 0 0.25rem rgba(229, 190, 88, 0.15);
            color: #fff;
        }

        .employee-modal-dialog {
            width: min(560px, calc(100% - 1rem));
        }

        .employee-modal-content {
            max-height: min(90vh, 820px);
            overflow: hidden;
            border: 1px solid #34313d;
            border-radius: 0.9rem;
            background-color: #17171c !important;
            color: #f5f5f6;
        }

        .employee-modal-body {
            overflow-y: auto;
        }

        .employee-account-list {
            overflow: hidden;
            border: 1px solid #302e38;
            border-radius: 0.8rem;
        }

        .employee-account-row {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.7rem 0.8rem;
        }

        .employee-account-row + .employee-account-row {
            border-top: 1px solid #302e38;
        }

        .employee-avatar-initial {
            display: grid;
            width: 2.25rem;
            height: 2.25rem;
            flex: 0 0 auto;
            place-items: center;
            border-radius: 50%;
            background: #382957;
            color: #c7a6ff;
            font-weight: 800;
        }

        .employee-account-details {
            min-width: 0;
            flex: 1;
        }

        .employee-account-name {
            overflow: hidden;
            color: #f5f5f6;
            font-size: 0.82rem;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .employee-account-meta {
            color: #92909b;
            font-size: 0.68rem;
        }

        .employee-account-status {
            width: 0.45rem;
            height: 0.45rem;
            flex: 0 0 auto;
            border-radius: 50%;
            background: #43d59a;
        }

        .employee-add-toggle {
            width: 100%;
            border: 1px solid #382957;
            border-radius: 0.55rem;
            background: #292231;
            color: #c7a6ff;
            font-weight: 800;
            padding: 0.72rem 1rem;
        }

        .employee-add-toggle:hover,
        .employee-add-toggle:focus-visible {
            border-color: #7252a8;
            background: #33283f;
            color: #e0d1ff;
        }

        .employee-create-form {
            border-top: 1px solid #302e38;
        }

        .employee-form-label {
            color: #b9b6c2;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        @media (max-width: 575.98px) {
            .employee-modal-dialog {
                width: 100%;
                margin: 0;
            }

            .employee-modal-content {
                max-height: 100vh;
                min-height: 100vh;
                border: 0;
                border-radius: 0;
            }
        }

        .report-modal-dialog {
            max-width: 760px;
        }

        .report-modal-content {
            max-height: min(86vh, 900px);
            background-color: #141419 !important;
            color: #ffffff;
        }

        .report-total {
            background: rgba(229, 190, 88, 0.1);
            border: 1px solid var(--gold-border);
            border-radius: 0.9rem;
        }

        .report-filters .form-dark-input {
            min-width: 180px;
            padding-block: 0.55rem;
        }

        .report-category-btn {
            background: #1a1a22;
            border: 1px solid #2a2a36;
            color: var(--text-muted);
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.55rem 0.9rem;
        }

        .report-category-btn:hover,
        .report-category-btn.active {
            background: var(--purple-btn);
            border-color: var(--purple-btn);
            color: #fff;
        }

        .report-list {
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 0.85rem;
        }

        .report-list-heading,
        .report-transaction {
            display: grid;
            grid-template-columns: 76px 1fr 88px;
            gap: 0.75rem;
            align-items: center;
        }

        .report-list-heading {
            padding: 0.65rem 0.85rem;
            background: var(--surface-2);
            color: var(--text-muted);
            font-size: 0.62rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .report-transaction {
            min-height: 70px;
            padding: 0.65rem 0.85rem;
            border-top: 1px solid var(--border);
        }

        .report-date-time {
            color: var(--text-muted);
            font-size: 0.68rem;
            line-height: 1.5;
        }

        .report-service-name {
            overflow: hidden;
            color: var(--text);
            font-size: 0.82rem;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .report-service-meta {
            color: var(--text-muted);
            font-size: 0.66rem;
        }

        .report-type-badge {
            display: inline-block;
            margin-right: 0.3rem;
            border-radius: 0.25rem;
            background: rgba(99, 67, 206, 0.2);
            color: #b6a2ff;
            font-size: 0.58rem;
            font-weight: 800;
            padding: 0.12rem 0.32rem;
        }

        .report-type-badge.walkin {
            background: rgba(52, 211, 153, 0.14);
            color: #6ee7b7;
        }

        .report-amount {
            color: var(--gold-primary);
            font-size: 0.76rem;
            font-weight: 800;
            text-align: right;
            white-space: nowrap;
        }

        @media (max-width: 575.98px) {
            .report-modal-content {
                max-height: 100vh;
                border: 0;
                border-radius: 0;
            }

            .report-filters .form-dark-input {
                flex: 1 1 100%;
                width: 100%;
            }

            .report-category-filter {
                display: flex;
                width: 100%;
            }

            .report-category-btn {
                flex: 1;
                padding-inline: 0.45rem;
            }

            .report-list-heading,
            .report-transaction {
                grid-template-columns: 54px minmax(0, 1fr) 66px;
                gap: 0.45rem;
                padding-inline: 0.65rem;
            }

            .report-service-name {
                font-size: 0.74rem;
            }

            .report-amount {
                font-size: 0.68rem;
            }
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
            --surface-3: #e4ebf6;
            --text: #111827;
            --text-muted: #5f6c7a;
            --border: #dfe7f1;
            --gold-primary: #c8901a;
            --gold-secondary: #b88318;
            --gold-border: rgba(200, 144, 26, 0.28);
            --shadow: rgba(15, 23, 42, 0.08);
        }

        html[data-theme="dark"] {
            color-scheme: dark;
            --bg-app: #08080b;
            --surface: #121217;
            --surface-2: #171720;
            --surface-3: #1d1d29;
            --text: #ffffff;
            --text-muted: #8c8c9a;
            --border: #202029;
            --gold-primary: #e5be58;
            --gold-secondary: #c99b33;
            --gold-border: rgba(229, 190, 88, 0.3);
            --shadow: rgba(0,0,0,0.7);
        }

        body {
            background: var(--bg-app);
            color: var(--text);
            transition: background 0.25s ease, color 0.25s ease;
        }

        .app-container,
        .stat-card,
        .revenue-chart-card,
        .employee-card,
        .custom-modal-content,
        .form-dark-input,
        .btn-circle-icon,
        .booking-card,
        .category-switcher,
        .search-box,
        .search-input,
        .filter-pills-container,
        .pill-btn,
        .card,
        .navbar,
        .btn-action-gold {
            transition: all 0.25s ease;
        }

        .app-container {
            background-color: var(--bg-app);
        }

        .header-title,
        .emp-name,
        .cust-name,
        .stat-lbl,
        .chart-day-label,
        .text-white,
        .header-date,
        .modal-title {
            color: var(--text) !important;
        }

        .stat-card,
        .revenue-chart-card,
        .employee-card,
        .custom-modal-content,
        .booking-card,
        .card,
        .search-input,
        .filter-pills-container,
        .pill-btn,
        .btn-circle-icon,
        .navbar {
            background-color: var(--surface);
            border-color: var(--border);
            color: var(--text);
        }

        .stat-card,
        .revenue-chart-card,
        .employee-card,
        .booking-card,
        .custom-modal-content,
        .btn-circle-icon {
            box-shadow: 0 8px 18px var(--shadow);
        }

        .search-input,
        .form-dark-input {
            background-color: var(--surface-2);
            color: var(--text);
            border-color: var(--border);
        }

        .search-input::placeholder,
        .form-label,
        .card-detail-item,
        .small,
        .header-sub,
        .stats-sub,
        .text-muted {
            color: var(--text-muted) !important;
        }

        .chart-bar-bg {
            background-color: var(--surface-2);
        }

        .chart-bar-fill {
            background: linear-gradient(180deg, rgba(229,190,88,0.7), var(--gold-primary));
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
        <header class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <div class="header-sub">GLOWCUT &nbsp;•&nbsp; ADMIN PANEL</div>
                <h1 class="header-title font-serif">Dashboard Admin</h1>
                <div class="header-date">{{ $dashboardDate }}</div>
            </div>
            <div class="d-flex gap-2">
                <div class="dropdown">
                    <button class="btn-circle-icon" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi">
                        <i class="fa-regular fa-bell"></i>
                    </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow" style="min-width: 320px; background-color: var(--card-bg); border-color: var(--card-border);">
            <li>
                <h6 class="dropdown-header text-white fw-bold d-flex justify-content-between align-items-center">
                    Notifikasi Kehadiran
                    @php $loggedInUsers = \App\Models\User::whereDate('last_login_at', now()->toDateString())->get(); @endphp
                    <span class="badge bg-success rounded-pill">{{ $loggedInUsers->count() }}</span>
                </h6>
            </li>
            <li><hr class="dropdown-divider" style="border-color: var(--card-border);"></li>
            @forelse($loggedInUsers as $user)
            <li>
                <a class="dropdown-item d-flex align-items-center gap-3 py-2" style="color: var(--text);" href="#">
                    <div class="avatar-initial" style="width: 36px; height: 36px; font-size: 0.85rem;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-bold" style="font-size: 0.85rem;">{{ $user->name }}</div>
                        <div class="text-success" style="font-size: 0.75rem;"><i class="fa-solid fa-circle-check me-1"></i>Telah login hari ini</div>
                    </div>
                </a>
            </li>
            @empty
            <li><span class="dropdown-item-text text-muted" style="font-size: 0.85rem;">Belum ada yang login hari ini.</span></li>
            @endforelse
        </ul>
                </div>
                <a href="{{ route('logout') }}" class="btn-circle-icon" title="Keluar">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </a>
            </div>
        </header>

        <section class="row g-2.5 mb-3">
            <!-- Stat 1: Total Pendapatan -->
            <div class="col-6 col-lg-3">
                <div class="stat-card stat-card-gold">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="icon-badge">💰</div>
                        <div class="trend-indicator trend-up">
                            <i class="fa-solid fa-caret-up"></i>
                        </div>
                    </div>
                    <div>
                        <div class="stat-val val-gold">Rp {{ number_format($monthRevenue, 0, ',', '.') }}</div>
                        <div class="stat-lbl">Total Pendapatan</div>
                        <div class="stat-subtext">{{ $monthStartLabel }} s.d. {{ $monthEndLabel }}</div>
                    </div>
                </div>
            </div>

            <!-- Stat 2: Booking Hari Ini -->
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="icon-badge">📅</div>
                        <div class="trend-indicator trend-up">
                            <i class="fa-solid fa-caret-up"></i>
                        </div>
                    </div>
                    <div>
                        <div class="stat-val val-cyan">{{ $todayBookingCount }}</div>
                        <div class="stat-lbl">Booking Hari Ini</div>
                        <div class="stat-subtext">{{ $todayPendingCount }} menunggu · {{ $todayInProgressCount }} proses</div>
                    </div>
                </div>
            </div>

            <!-- Stat 3: Walk-in Hari Ini -->
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="icon-badge">🚶</div>
                        <div class="trend-indicator trend-down">
                            <i class="fa-solid fa-caret-down"></i>
                        </div>
                    </div>
                    <div>
                        <div class="stat-val val-purple">{{ $todayWalkInCount }}</div>
                        <div class="stat-lbl">Walk-in Hari Ini</div>
                        <div class="stat-subtext">Rp {{ number_format($todayWalkInRevenue, 0, ',', '.') }} selesai</div>
                    </div>
                </div>
            </div>

            <!-- Stat 4: Total Karyawan -->
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="icon-badge">👥</div>
                        <div class="trend-indicator trend-up">
                            <i class="fa-solid fa-caret-up"></i>
                        </div>
                    </div>
                    <div>
                        <div class="stat-val val-pink">{{ $employeeCount }}</div>
                        <div class="stat-lbl">Total Karyawan</div>
                        <div class="stat-subtext">Terdaftar di database</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="row g-2.5 mb-3">
            <div class="col-12 col-md-6">
                <a href="{{ route('admin.karyawan') }}" class="btn-action-purple text-decoration-none">
                    <i class="fa-solid fa-user-group fs-5"></i>
                    <span>Kelola Karyawan</span>
                </a>
            </div>
            <div class="col-12 col-md-6">
                <a href="{{ route('admin.laporan') }}" class="btn-action-gold text-decoration-none">
                    <i class="fa-solid fa-sack-dollar fs-5"></i>
                    <span>Laporan Pendapatan</span>
                </a>
            </div>
        </section>

        <section class="revenue-chart-card mb-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h6 fw-bold text-white mb-0">Pendapatan Minggu Ini</h2>
                <div class="fw-extrabold text-gold fs-6">Rp {{ number_format($weeklyRevenue, 0, ',', '.') }}</div>
            </div>

            <!-- Bar Chart Display -->
            <div class="d-flex justify-content-between align-items-end pt-2 pb-1 px-1">
                @foreach ($weeklyDays as $day)
                    <div class="chart-day-col {{ $day['isToday'] ? 'active' : '' }}" title="{{ $day['date']->format('d/m/Y') }} · Rp {{ number_format($day['revenue'], 0, ',', '.') }}">
                        <div class="chart-bar-bg">
                            <div class="chart-bar-fill" style="height: {{ $day['barHeight'] }}%;"></div>
                        </div>
                        <span class="chart-day-label">{{ $day['label'] }}</span>
                    </div>
                @endforeach
            </div>
            <div class="small text-muted text-center mt-2">{{ $weeklyStartLabel }} – {{ $weeklyEndLabel }} · Pendapatan booking selesai</div>
        </section>

        <section>
            <div class="d-flex justify-content-between align-items-center mb-2.5">
                <h2 class="h6 fw-bold text-white mb-0">Daftar Karyawan</h2>
                <a href="{{ route('admin.karyawan') }}" class="text-decoration-none small text-purple fw-bold" style="color: #a78bfa;">
                    Kelola
                </a>
            </div>

            <div class="d-flex flex-column gap-2" id="employeeListContainer">
                @forelse ($employees as $employee)
                    <div class="employee-card">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-wrapper">
                                <span class="avatar-initial" aria-hidden="true">{{ strtoupper(substr($employee->name, 0, 1)) }}</span>
                                <span class="status-dot-active"></span>
                            </div>
                            <div>
                                <div class="emp-name">{{ $employee->name }}</div>
                                <div class="{{ $employee->position === 'MUA Artist' ? 'emp-role-mua' : 'emp-role-barber' }}">{{ $employee->position }}</div>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="emp-stats-num">{{ $employee->completed_service_count }} layanan selesai</div>
                            <div class="emp-status-badge">Terdaftar</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-3">Belum ada karyawan terdaftar.</div>
                @endforelse
            </div>
        </section>

    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1090;">
        <div id="actionToast" class="toast align-items-center text-bg-success border-0 rounded-3 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-check fs-5"></i>
                    <div>
                        <strong class="d-block" id="toastTitle">Berhasil!</strong>
                        <span class="small" id="toastDesc">Aksi berhasil diperbarui.</span>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        function triggerToast(title, desc) {
            const toastTitle = document.getElementById('toastTitle');
            const toastDesc = document.getElementById('toastDesc');
            const toastEl = document.getElementById('actionToast');

            if (toastTitle) toastTitle.innerText = title;
            if (toastDesc) toastDesc.innerText = desc;

            if (toastEl && window.bootstrap && window.bootstrap.Toast) {
                const toast = bootstrap.Toast.getOrCreateInstance(toastEl);
                toast.show();
            }
        }

        function showNotification() {
            triggerToast('Notifikasi', '3 pesanan booking baru membutuhkan konfirmasi.');
        }
    </script>
</body>
</html>
