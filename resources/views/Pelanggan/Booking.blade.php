<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Kategori - GlowCut</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts: Playfair Display / Cinzel & Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-dark: #0b0b0e;
            --bg-card: #141419;
            --gold-primary: #d4af37;
            --gold-hover: #f3ca40;
            --gold-badge-bg: #c99834;
            --text-muted: #9a9ab0;
            --border-dark: #22222d;
            --purple-card-bg: linear-gradient(135deg, #2a0826 0%, #170518 60%, #0d040e 100%);
        }

        body {
            background-color: var(--bg-dark);
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-x: hidden;
            padding: 24px 0;
        }

        .font-serif {
            font-family: 'Cinzel', 'Playfair Display', serif;
        }

        /* Container Max-Width Control for Desktop & Mobile */
        .category-container {
            max-width: 860px;
            margin: 0 auto;
            width: 100%;
        }

        /* Header Navigation & Titles */
        .btn-back {
            color: #d1d1db;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.25s ease;
        }

        .btn-back:hover {
            color: var(--gold-primary);
            transform: translateX(-4px);
        }

        .header-title {
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #ffffff;
            margin-top: 24px;
            margin-bottom: 6px;
        }

        @media (min-width: 768px) {
            .header-title {
                font-size: 2.8rem;
            }
        }

        .header-subtitle {
            color: var(--text-muted);
            font-size: 0.98rem;
            font-weight: 400;
        }

        /* Base Category Cards Styling */
        .category-card {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            min-height: 230px;
            padding: 26px 28px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            text-decoration: none;
            color: #ffffff;
            transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
            border: 1px solid var(--border-dark);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            cursor: pointer;
        }

        .category-card:hover {
            color: #ffffff;
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.7);
        }

        /* Card 1: Barbershop */
        .card-barbershop {
            background: linear-gradient(180deg, rgba(11, 11, 14, 0.2) 0%, rgba(11, 11, 14, 0.85) 70%, rgba(11, 11, 14, 0.98) 100%),
                        url('https://images.unsplash.com/photo-1503951914875-452162b0f3f1?q=80&w=1000&auto=format&fit=crop') center/cover no-repeat;
        }

        .card-barbershop:hover {
            border-color: rgba(212, 175, 55, 0.5);
        }

        /* Card 2: MUA Wisuda */
        .card-mua {
            background: var(--purple-card-bg);
            position: relative;
        }

        .card-mua:hover {
            border-color: rgba(212, 175, 55, 0.5);
        }

        /* Watermark Background Graphic for MUA Card */
        .card-mua::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.08);
            pointer-events: none;
        }

        .card-mua-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 5rem;
            color: rgba(255, 255, 255, 0.05);
            pointer-events: none;
        }

        /* Badges */
        .card-badge {
            position: absolute;
            top: 22px;
            right: 22px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            padding: 6px 14px;
            border-radius: 12px;
            text-transform: uppercase;
        }

        .badge-barbershop {
            background-color: var(--gold-badge-bg);
            color: #000000;
        }

        .badge-mua {
            background-color: rgba(58, 22, 53, 0.65);
            border: 1px solid var(--gold-badge-bg);
            color: var(--gold-primary);
        }

        /* Card Content Typography */
        .card-title-text {
            font-size: 1.85rem;
            font-weight: 700;
            margin-bottom: 6px;
            letter-spacing: 0.3px;
        }

        .card-subtitle-text {
            font-size: 0.88rem;
            color: #c0c0d0;
            font-weight: 400;
            margin-bottom: 14px;
            line-height: 1.4;
        }

        .card-action-link {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--gold-primary);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.25s ease;
        }

        .category-card:hover .card-action-link {
            color: var(--gold-hover);
            transform: translateX(4px);
        }
    </style>
</head>
<body>

    <div class="container px-3 px-md-4">
        <div class="category-container">

            <!-- HEADER SECTION -->
            <header class="mb-4 mb-md-5">
                <a href="javascript:history.back()" class="btn-back">
                    <i class="bi bi-arrow-left fs-5"></i> Kembali
                </a>

                <h1 class="header-title font-serif">Pilih Kategori</h1>
                <p class="header-subtitle">Layanan apa yang kamu butuhkan?</p>
            </header>

            <!-- CATEGORIES CARDS GRID -->
            <div class="row g-4">

                <!-- CARD 1: BARBERSHOP -->
                <div class="col-12 col-md-6">
                    <a href="javascript:void(0)" class="category-card card-barbershop">
                        <span class="card-badge badge-barbershop">BARBERSHOP</span>
                        <div class="position-relative z-1">
                            <h2 class="card-title-text font-serif">Barbershop</h2>
                            <p class="card-subtitle-text">Haircut · Shaving · Coloring · Perawatan</p>
                            <div class="card-action-link">
                                <span>12 layanan tersedia</span>
                                <i class="bi bi-arrow-right"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- CARD 2: MUA WISUDA -->
                <div class="col-12 col-md-6">
                    <a href="javascript:void(0)" class="category-card card-mua">
                        <i class="bi bi-person-fill card-mua-icon"></i>
                        <span class="card-badge badge-mua">MUA WISUDA</span>
                        <div class="position-relative z-1">
                            <h2 class="card-title-text font-serif">MUA Wisuda</h2>
                            <p class="card-subtitle-text">Makeup · Sanggul · Hijab Styling · Paket Lengkap</p>
                            <div class="card-action-link">
                                <span>8 paket tersedia</span>
                                <i class="bi bi-arrow-right"></i>
                            </div>
                        </div>
                    </a>
                </div>

            </div>

        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>