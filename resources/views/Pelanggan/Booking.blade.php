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
            padding: 20px 0;
        }

        .font-serif {
            font-family: 'Cinzel', 'Playfair Display', serif;
        }

        /* Container Max-Width Control for Desktop & Mobile */
        .category-container {
            max-width: 100%;
            margin: 0 auto;
            width: 100%;
            padding: 0 12px;
        }

        @media (min-width: 992px) {
            .category-container {
                max-width: 1100px;
                padding: 0;
            }
        }

        @media (min-width: 1200px) {
            .category-container {
                max-width: 1280px;
            }
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
            padding: 6px 12px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .btn-back:hover {
            color: var(--gold-primary);
            border-color: rgba(212, 175, 55, 0.3);
            transform: translateX(-4px);
        }

        .header-title {
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #ffffff;
            margin-top: 20px;
            margin-bottom: 6px;
        }

        .header-subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
            font-weight: 400;
        }

        /* Responsive Desktop Typography & Spacing */
        @media (min-width: 992px) {
            body {
                padding: 40px 0;
            }
            .btn-back {
                font-size: 1.05rem;
                padding: 8px 18px;
            }
            .header-title {
                font-size: 3.5rem;
                margin-top: 28px;
                margin-bottom: 10px;
            }
            .header-subtitle {
                font-size: 1.25rem;
            }
        }

        /* Base Category Cards Styling */
        .category-card {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            min-height: 220px;
            padding: 24px 22px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            text-decoration: none;
            color: #ffffff;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            border: 1px solid var(--border-dark);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            cursor: pointer;
        }

        @media (min-width: 992px) {
            .category-card {
                border-radius: 28px;
                min-height: 380px;
                padding: 40px 36px;
            }
        }

        .category-card:hover {
            color: #ffffff;
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.8), 0 0 20px rgba(212, 175, 55, 0.15);
            border-color: rgba(212, 175, 55, 0.6);
        }

        /* Card 1: Barbershop */
        .card-barbershop {
            background: linear-gradient(180deg, rgba(11, 11, 14, 0.2) 0%, rgba(11, 11, 14, 0.8) 60%, rgba(11, 11, 14, 0.98) 100%),
                        url('https://images.unsplash.com/photo-1503951914875-452162b0f3f1?q=80&w=1200&auto=format&fit=crop') center/cover no-repeat;
            transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), background-size 0.5s ease;
        }

        .card-barbershop:hover {
            background-size: 108%;
        }

        /* Card 2: MUA Wisuda */
        .card-mua {
            background: var(--purple-card-bg);
            position: relative;
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
            transition: all 0.4s ease;
        }

        .card-mua-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 5rem;
            color: rgba(255, 255, 255, 0.05);
            pointer-events: none;
            transition: all 0.4s ease;
        }

        @media (min-width: 992px) {
            .card-mua::before {
                width: 240px;
                height: 240px;
            }
            .card-mua-icon {
                font-size: 9rem;
            }
        }

        .card-mua:hover::before {
            border-color: rgba(212, 175, 55, 0.2);
            transform: translate(-50%, -50%) scale(1.1);
        }

        .card-mua:hover .card-mua-icon {
            color: rgba(212, 175, 55, 0.1);
            transform: translate(-50%, -50%) scale(1.05);
        }

        /* Badges */
        .card-badge {
            position: absolute;
            top: 18px;
            right: 18px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            padding: 6px 12px;
            border-radius: 10px;
            text-transform: uppercase;
        }

        @media (min-width: 992px) {
            .card-badge {
                top: 28px;
                right: 28px;
                font-size: 0.85rem;
                padding: 8px 18px;
                border-radius: 12px;
            }
        }

        .badge-barbershop {
            background-color: var(--gold-badge-bg);
            color: #000000;
        }

        .badge-mua {
            background-color: rgba(58, 22, 53, 0.85);
            border: 1px solid var(--gold-badge-bg);
            color: var(--gold-primary);
        }

        /* Card Content Typography */
        .card-title-text {
            font-size: 1.65rem;
            font-weight: 700;
            margin-bottom: 6px;
            letter-spacing: 0.3px;
        }

        .card-subtitle-text {
            font-size: 0.85rem;
            color: #c0c0d0;
            font-weight: 400;
            margin-bottom: 12px;
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

        @media (min-width: 992px) {
            .card-title-text {
                font-size: 2.6rem;
                margin-bottom: 10px;
            }
            .card-subtitle-text {
                font-size: 1.1rem;
                margin-bottom: 20px;
            }
            .card-action-link {
                font-size: 1.1rem;
                gap: 10px;
            }
        }

        .category-card:hover .card-action-link {
            color: var(--gold-hover);
            transform: translateX(6px);
        }
    </style>
</head>
<body>

    <div class="container px-3 px-md-4">
        <div class="category-container">

            <!-- HEADER SECTION -->
            <header class="mb-4 mb-lg-5">
                <a href="javascript:history.back()" class="btn-back">
                    <i class="bi bi-arrow-left fs-5"></i> Kembali
                </a>

                <h1 class="header-title font-serif">Pilih Kategori</h1>
                <p class="header-subtitle">Layanan apa yang kamu butuhkan?</p>
            </header>

            <!-- CATEGORIES CARDS GRID -->
            <div class="row g-3 g-md-4 g-lg-5">

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