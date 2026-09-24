<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlowCut MUA Wisuda - Makeup Elegance</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-dark: #09090b;
            --bg-card: #141418;
            --bg-card-hover: #1c1c23;
            --pink-primary: #ec4899;
            --pink-hover: #db2777;
            --pink-gradient: linear-gradient(135deg, #f43f5e 0%, #ec4899 50%, #d946ef 100%);
            --pink-glow: rgba(236, 72, 153, 0.35);
            --border-dark: #23232c;
            --text-main: #f3f3f6;
            --text-muted: #9e9ea8;
            --gold-rating: #f59e0b;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
            padding-bottom: 95px; /* Offset for sticky CTA bar */
            overflow-x: hidden;
        }

        h1, h2, h3, .font-serif {
            font-family: 'Playfair Display', serif;
        }

        /* Container Limit for Desktop Viewports */
        .app-container {
            max-width: 920px;
            margin: 0 auto;
        }

        /* Hero Header Section */
        .hero-section {
            position: relative;
            background: linear-gradient(180deg, rgba(9, 9, 11, 0.35) 0%, rgba(9, 9, 11, 0.92) 75%, var(--bg-dark) 100%),
                        url('https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?q=80&w=1200&auto=format&fit=crop') center/cover no-repeat;
            min-height: 400px;
            padding: 24px 20px 36px 20px;
            border-bottom-left-radius: 28px;
            border-bottom-right-radius: 28px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.85);
        }

        .back-btn {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(20, 20, 24, 0.75);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .back-btn:hover {
            background: var(--pink-primary);
            color: #ffffff;
            border-color: var(--pink-primary);
        }

        .tagline-badge {
            color: #f472b6;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.3px;
            margin-top: 6px;
            margin-bottom: 4px;
        }

        @media (min-width: 768px) {
            .hero-title {
                font-size: 3.4rem;
            }
            .hero-section {
                min-height: 460px;
                padding-bottom: 50px;
            }
        }

        .hero-subtitle {
            color: var(--text-muted);
            font-size: 1.05rem;
            font-weight: 400;
        }

        /* Section Titles */
        .section-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 18px;
            letter-spacing: 0.3px;
        }

        /* Feature Cards */
        .feature-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-dark);
            border-radius: 18px;
            padding: 18px 16px;
            height: 100%;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            background-color: var(--bg-card-hover);
            border-color: rgba(236, 72, 153, 0.4);
            transform: translateY(-2px);
        }

        .feature-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(236, 72, 153, 0.12);
            color: #f472b6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .feature-title {
            font-weight: 700;
            font-size: 0.95rem;
            color: #ffffff;
            margin-bottom: 2px;
        }

        .feature-desc {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin: 0;
            line-height: 1.35;
        }

        /* Gallery Grid */
        .gallery-img-wrapper {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            aspect-ratio: 1 / 1;
            border: 1px solid var(--border-dark);
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
        }

        .gallery-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .gallery-img-wrapper:hover img {
            transform: scale(1.08);
        }

        /* Service Package Cards */
        .service-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-dark);
            border-radius: 20px;
            padding: 18px 20px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            cursor: pointer;
        }

        .service-card:hover {
            background-color: var(--bg-card-hover);
            border-color: rgba(236, 72, 153, 0.45);
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
        }

        .service-icon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.05);
            color: #f472b6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .service-title {
            font-weight: 700;
            font-size: 1.05rem;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .service-desc {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin-bottom: 4px;
            line-height: 1.4;
        }

        .service-duration {
            font-size: 0.76rem;
            color: #828292;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .service-price {
            font-weight: 700;
            font-size: 1.1rem;
            color: #f472b6;
            white-space: nowrap;
            text-align: right;
        }

        /* Badges for Services */
        .badge-terlaris {
            background-color: #f59e0b;
            color: #000000;
            font-weight: 700;
            font-size: 0.68rem;
            padding: 3px 8px;
            border-radius: 6px;
            letter-spacing: 0.5px;
        }

        .badge-populer {
            background-color: #ec4899;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.68rem;
            padding: 3px 8px;
            border-radius: 6px;
            letter-spacing: 0.5px;
        }

        .badge-hemat {
            background-color: #10b981;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.68rem;
            padding: 3px 8px;
            border-radius: 6px;
            letter-spacing: 0.5px;
        }

        /* Process Flow Items */
        .process-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 22px;
        }

        .process-number {
            font-size: 1.05rem;
            font-weight: 800;
            color: #f472b6;
            background: rgba(236, 72, 153, 0.12);
            border: 1px solid rgba(236, 72, 153, 0.25);
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .process-title {
            font-weight: 700;
            font-size: 1rem;
            color: #ffffff;
            margin-bottom: 4px;
        }

        .process-desc {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin: 0;
            line-height: 1.45;
        }

        /* Customer Review Cards */
        .review-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-dark);
            border-radius: 20px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .review-card:hover {
            border-color: rgba(236, 72, 153, 0.3);
            background-color: var(--bg-card-hover);
        }

        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(236, 72, 153, 0.2);
            color: #f472b6;
            font-weight: 700;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .star-rating {
            color: var(--gold-rating);
            font-size: 0.85rem;
        }

        /* Fixed Bottom Floating CTA Bar */
        .fixed-bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1040;
            padding: 14px 20px;
            background: linear-gradient(180deg, rgba(9, 9, 11, 0) 0%, rgba(9, 9, 11, 0.95) 35%, var(--bg-dark) 100%);
            backdrop-filter: blur(12px);
            pointer-events: none;
        }

        .btn-booking-sticky {
            pointer-events: auto;
            background: var(--pink-gradient);
            color: #ffffff;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 18px;
            padding: 15px 24px;
            width: 100%;
            max-width: 880px;
            margin: 0 auto;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 8px 30px var(--pink-glow);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-booking-sticky:hover, .btn-booking-sticky:focus {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(236, 72, 153, 0.5);
        }

        /* Custom Dark Modal */
        .modal-content-dark {
            background-color: var(--bg-card);
            border: 1px solid var(--border-dark);
            border-radius: 24px;
            color: var(--text-main);
        }

        .modal-header-dark {
            border-bottom: 1px solid var(--border-dark);
            padding: 20px 24px;
        }

        .form-control-dark {
            background-color: #0b0b0d;
            border: 1px solid var(--border-dark);
            color: #ffffff;
            border-radius: 12px;
            padding: 12px;
        }

        .form-control-dark:focus {
            background-color: #0b0b0d;
            border-color: var(--pink-primary);
            box-shadow: 0 0 0 3px var(--pink-glow);
            color: #ffffff;
        }
    </style>
</head>
<body>

    <div class="app-container">
        
        <!-- SECTION 1: HERO HEADER -->
        <header class="hero-section d-flex flex-column justify-content-between">
            <!-- Top Nav -->
            <div class="d-flex align-items-center justify-content-between w-100 mb-4">
                <a href="/dashboard/pelanggan" class="back-btn" title="Kembali">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <span class="badge bg-dark text-light border border-secondary px-3 py-2 rounded-pill">
                    <i class="bi bi-patch-check-fill text-pink me-1" style="color: #ec4899;"></i> Official MUA
                </span>
            </div>

            <!-- Hero Branding -->
            <div>
                <div class="tagline-badge">
                    <i class="bi bi-magic"></i> GLOWCUT MUA WISUDA
                </div>
                <h1 class="hero-title font-serif">Makeup Elegance</h1>
                <p class="hero-subtitle mb-0">Cantik sempurna di hari wisudamu</p>
            </div>
        </header>

        <!-- MAIN SCROLLABLE CONTENT -->
        <main class="px-3 px-md-4 pt-4">

            <!-- SECTION 2: ABOUT SECTION -->
            <section class="mb-5">
                <h2 class="section-title">Tentang Layanan MUA Kami</h2>
                <div style="color: #a3a3b2; line-height: 1.75; font-size: 0.95rem;">
                    <p class="mb-3">
                        Tim MUA GlowCut terdiri dari makeup artist berpengalaman yang telah menangani ribuan sesi wisuda. Setiap MUA kami memiliki sertifikasi profesional dan berlatih secara rutin mengikuti tren kecantikan terkini — dari teknik dewy natural hingga glam internasional.
                    </p>
                    <p class="mb-0">
                        Kami memahami betapa pentingnya momen wisuda. Karena itu, kami menawarkan sesi konsultasi sebelum hari H, memastikan riasan kamu sesuai keinginan, cocok dengan busana, dan tahan sepanjang acara dari pagi hingga malam — lengkap dengan touch-up kit gratis.
                    </p>
                </div>
            </section>

            <!-- SECTION 3: FEATURE BADGES GRID -->
            <section class="mb-5">
                <div class="row g-3">
                    <!-- Card 1 -->
                    <div class="col-6 col-md-3">
                        <div class="feature-card">
                            <div class="feature-icon-box">
                                <i class="bi bi-award-fill"></i>
                            </div>
                            <div>
                                <div class="feature-title">MUA Bersertifikat</div>
                                <div class="feature-desc">Terlatih dari lembaga resmi</div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="col-6 col-md-3">
                        <div class="feature-card">
                            <div class="feature-icon-box">
                                <i class="bi bi-stars"></i>
                            </div>
                            <div>
                                <div class="feature-title">Produk Premium</div>
                                <div class="feature-desc">MAC, Maybelline, NYX</div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="col-6 col-md-3">
                        <div class="feature-card">
                            <div class="feature-icon-box">
                                <i class="bi bi-stopwatch-fill"></i>
                            </div>
                            <div>
                                <div class="feature-title">Tahan 12 Jam</div>
                                <div class="feature-desc">Setting spray terbaik</div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="col-6 col-md-3">
                        <div class="feature-card">
                            <div class="feature-icon-box">
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <div>
                                <div class="feature-title">Rating 4.9</div>
                                <div class="feature-desc">Dari 300+ ulasan</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION 4: GALLERY SECTION -->
            <section class="mb-5">
                <h2 class="section-title">Galeri Hasil Makeup</h2>
                <div class="row g-2 g-md-3">
                    <div class="col-4">
                        <div class="gallery-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1512496015851-a90fb38ba796?q=80&w=400&auto=format&fit=crop" alt="Makeup Eye Application">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="gallery-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?q=80&w=400&auto=format&fit=crop" alt="Lipstick Touchup">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="gallery-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?q=80&w=400&auto=format&fit=crop" alt="Graduation Portrait">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="gallery-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1526045612212-70caf35c14df?q=80&w=400&auto=format&fit=crop" alt="Traditional Wisuda Look">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="gallery-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1560066984-138dadb4c035?q=80&w=400&auto=format&fit=crop" alt="Eye Shadow Detail">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="gallery-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1516975080664-ed2fc6a32937?q=80&w=400&auto=format&fit=crop" alt="Glamour Makeup Model">
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION 5: SERVICE PACKAGES LIST -->
            <section class="mb-5">
                <h2 class="section-title">Paket Tersedia</h2>
                <div class="d-flex flex-column gap-3">
                    
                    <!-- Package 1 -->
                    <div class="service-card" onclick="selectService('Makeup Natural Wisuda', '350k')">
                        <div class="d-flex align-items-center gap-3">
                            <div class="service-icon">
                                <i class="bi bi-brush"></i>
                            </div>
                            <div>
                                <div class="service-title">
                                    <span>Makeup Natural Wisuda</span>
                                    <span class="badge-terlaris">TERLARIS</span>
                                </div>
                                <div class="service-desc">Riasan dewy fresh, ringan, & tampak flawless sepanjang hari.</div>
                                <div class="service-duration"><i class="bi bi-clock"></i> 120 mnt</div>
                            </div>
                        </div>
                        <div class="service-price">Rp 350k</div>
                    </div>

                    <!-- Package 2 -->
                    <div class="service-card" onclick="selectService('Makeup Glamour Wisuda', '500k')">
                        <div class="d-flex align-items-center gap-3">
                            <div class="service-icon">
                                <i class="bi bi-gem"></i>
                            </div>
                            <div>
                                <div class="service-title">
                                    <span>Makeup Glamour Wisuda</span>
                                </div>
                                <div class="service-desc">Riasan glam bold, contouring tegas & bulu mata double layer.</div>
                                <div class="service-duration"><i class="bi bi-clock"></i> 150 mnt</div>
                            </div>
                        </div>
                        <div class="service-price">Rp 500k</div>
                    </div>

                    <!-- Package 3 -->
                    <div class="service-card" onclick="selectService('Paket Hijab + Makeup Wisuda', '450k')">
                        <div class="d-flex align-items-center gap-3">
                            <div class="service-icon">
                                <i class="bi bi-heart-fill"></i>
                            </div>
                            <div>
                                <div class="service-title">
                                    <span>Paket Hijab + Makeup</span>
                                    <span class="badge-populer">POPULER</span>
                                </div>
                                <div class="service-desc">Riasan wajah lengkap + styling kreasi hijab wisuda modern.</div>
                                <div class="service-duration"><i class="bi bi-clock"></i> 150 mnt</div>
                            </div>
                        </div>
                        <div class="service-price">Rp 450k</div>
                    </div>

                    <!-- Package 4 -->
                    <div class="service-card" onclick="selectService('Paket Sanggul Modern', '550k')">
                        <div class="d-flex align-items-center gap-3">
                            <div class="service-icon">
                                <i class="bi bi-flower1"></i>
                            </div>
                            <div>
                                <div class="service-title">
                                    <span>Paket Sanggul Modern</span>
                                </div>
                                <div class="service-desc">Makeup wisuda premium + hair do / sanggul kebaya rapi.</div>
                                <div class="service-duration"><i class="bi bi-clock"></i> 180 mnt</div>
                            </div>
                        </div>
                        <div class="service-price">Rp 550k</div>
                    </div>

                    <!-- Package 5 -->
                    <div class="service-card" onclick="selectService('Paket Keluarga Wisuda', '1.2jt')">
                        <div class="d-flex align-items-center gap-3">
                            <div class="service-icon">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div>
                                <div class="service-title">
                                    <span>Paket Keluarga</span>
                                    <span class="badge-hemat">HEMAT</span>
                                </div>
                                <div class="service-desc">Makeup wisudawati + 2 anggota keluarga (Ibu/Saudara).</div>
                                <div class="service-duration"><i class="bi bi-clock"></i> 360 mnt</div>
                            </div>
                        </div>
                        <div class="service-price">Rp 1.2jt</div>
                    </div>

                    <!-- Package 6 -->
                    <div class="service-card" onclick="selectService('Trial Makeup Wisuda', '200k')">
                        <div class="d-flex align-items-center gap-3">
                            <div class="service-icon">
                                <i class="bi bi-palette-fill"></i>
                            </div>
                            <div>
                                <div class="service-title">
                                    <span>Trial Makeup</span>
                                </div>
                                <div class="service-desc">Sesi uji coba riasan sebelum hari H untuk hasil maksimal.</div>
                                <div class="service-duration"><i class="bi bi-clock"></i> 90 mnt</div>
                            </div>
                        </div>
                        <div class="service-price">Rp 200k</div>
                    </div>

                </div>
            </section>

            <!-- SECTION 6: SERVICE FLOW -->
            <section class="mb-5">
                <h2 class="section-title">Alur Layanan MUA</h2>
                <div class="pt-2">
                    
                    <div class="process-item">
                        <div class="process-number">01</div>
                        <div>
                            <div class="process-title">Konsultasi Riasan</div>
                            <p class="process-desc">Diskusi gaya, warna, dan kesesuaian riasan dengan busana wisuda.</p>
                        </div>
                    </div>

                    <div class="process-item">
                        <div class="process-number">02</div>
                        <div>
                            <div class="process-title">Prep & Skincare</div>
                            <p class="process-desc">Primer dan skincare base untuk hasil riasan yang tahan lebih lama.</p>
                        </div>
                    </div>

                    <div class="process-item">
                        <div class="process-number">03</div>
                        <div>
                            <div class="process-title">Aplikasi Makeup</div>
                            <p class="process-desc">Proses riasan lengkap oleh MUA bersertifikat kami.</p>
                        </div>
                    </div>

                    <div class="process-item">
                        <div class="process-number">04</div>
                        <div>
                            <div class="process-title">Setting & Touch-up</div>
                            <p class="process-desc">Finishing spray + kit touch-up gratis untuk sepanjang hari.</p>
                        </div>
                    </div>

                </div>
            </section>

            <!-- SECTION 7: CUSTOMER REVIEWS -->
            <section class="mb-5">
                <h2 class="section-title">Ulasan Pelanggan</h2>
                <div class="d-flex flex-column gap-3">
                    
                    <!-- Review 1 -->
                    <div class="review-card">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle">S</div>
                                <div class="fw-bold text-white">Sari D.</div>
                            </div>
                            <div class="star-rating">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                        <p class="mb-0 text-secondary small" style="color: #a3a3b2 !important; line-height: 1.5;">
                            Hasilnya luar biasa! Tahan dari pagi sampai malam. MUA-nya sangat profesional dan sabar.
                        </p>
                    </div>

                    <!-- Review 2 -->
                    <div class="review-card">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle">N</div>
                                <div class="fw-bold text-white">Nina P.</div>
                            </div>
                            <div class="star-rating">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                        <p class="mb-0 text-secondary small" style="color: #a3a3b2 !important; line-height: 1.5;">
                            Paket hijab + makeup-nya bagus banget. Stylingnya anggun dan sesuai tema busana saya.
                        </p>
                    </div>

                    <!-- Review 3 -->
                    <div class="review-card">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle">P</div>
                                <div class="fw-bold text-white">Putri A.</div>
                            </div>
                            <div class="star-rating">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                        <p class="mb-0 text-secondary small" style="color: #a3a3b2 !important; line-height: 1.5;">
                            Sudah pakai GlowCut 2x wisuda — kakak dan saya sendiri. Hasilnya selalu memuaskan!
                        </p>
                    </div>

                </div>
            </section>

        </main>
    </div>

    <!-- SECTION 8: FIXED BOTTOM STICKY CTA BUTTON -->
    <div class="fixed-bottom-bar">
        <button type="button" class="btn-booking-sticky" data-bs-toggle="modal" data-bs-target="#bookingModal">
            <span>Booking MUA Sekarang</span>
            <i class="bi bi-arrow-right fs-5"></i>
        </button>
    </div>

    <!-- BOOKING MODAL -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-dark">
                <div class="modal-header modal-header-dark">
                    <h5 class="modal-title font-serif fw-bold text-pink" id="bookingModalLabel" style="color: #ec4899;">
                        <i class="bi bi-calendar-heart me-2"></i>Reservasi MUA Wisuda
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="bookingForm" onsubmit="handleFormSubmit(event)">
                        <div class="mb-3">
                            <label for="namaInput" class="form-label text-secondary small fw-bold">NAMA LENGKAP</label>
                            <input type="text" class="form-control form-control-dark" id="namaInput" placeholder="Masukkan nama kamu" required>
                        </div>
                        <div class="mb-3">
                            <label for="serviceSelect" class="form-label text-secondary small fw-bold">PILIH PAKET MUA</label>
                            <select class="form-select form-control-dark" id="serviceSelect">
                                <option value="Makeup Natural Wisuda">Makeup Natural Wisuda (Rp 350k)</option>
                                <option value="Makeup Glamour Wisuda">Makeup Glamour Wisuda (Rp 500k)</option>
                                <option value="Paket Hijab + Makeup Wisuda">Paket Hijab + Makeup Wisuda (Rp 450k)</option>
                                <option value="Paket Sanggul Modern">Paket Sanggul Modern (Rp 550k)</option>
                                <option value="Paket Keluarga Wisuda">Paket Keluarga Wisuda (Rp 1.2jt)</option>
                                <option value="Trial Makeup Wisuda">Trial Makeup Wisuda (Rp 200k)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tanggalInput" class="form-label text-secondary small fw-bold">TANGGAL & WAKTU WISUDA</label>
                            <input type="datetime-local" class="form-control form-control-dark" id="tanggalInput" required>
                        </div>
                        <button type="submit" class="btn btn-booking-sticky mt-4">
                            Konfirmasi Booking
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Select package dynamically when clicking on service card
        function selectService(packageName, price) {
            const selectElement = document.getElementById('serviceSelect');
            if (selectElement) {
                for (let option of selectElement.options) {
                    if (option.value === packageName) {
                        option.selected = true;
                        break;
                    }
                }
            }
            // Open modal
            const modal = new bootstrap.Modal(document.getElementById('bookingModal'));
            modal.show();
        }

        // Handle form submission
        function handleFormSubmit(event) {
            event.preventDefault();
            const nama = document.getElementById('namaInput').value;
            const paket = document.getElementById('serviceSelect').value;
            
            // Close modal
            const modalEl = document.getElementById('bookingModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }

            // Clean custom notification box inside modal backdrop or simple alert element
            const toast = document.createElement('div');
            toast.style.position = 'fixed';
            toast.style.top = '20px';
            toast.style.left = '50%';
            toast.style.transform = 'translateX(-50%)';
            toast.style.backgroundColor = '#141418';
            toast.style.border = '1px solid #ec4899';
            toast.style.color = '#ffffff';
            toast.style.padding = '14px 22px';
            toast.style.borderRadius = '14px';
            toast.style.zIndex = '9999';
            toast.style.boxShadow = '0 10px 30px rgba(236,72,153,0.3)';
            toast.innerHTML = `<i class="bi bi-check-circle-fill text-pink me-2" style="color: #ec4899;"></i> Terima kasih <b>${nama}</b>! Booking paket <b>${paket}</b> berhasil dibuat.`;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 4000);
        }
    </script>
</body>
</html>