<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlowCut Barbershop - Premium Haircut</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-dark: #0b0b0d;
            --bg-card: #141418;
            --bg-card-hover: #1c1c22;
            --gold-primary: #e5c158;
            --gold-hover: #dfb750;
            --gold-glow: rgba(229, 193, 88, 0.22);
            --border-dark: #22222a;
            --text-main: #f0f0f3;
            --text-muted: #9a9ab0;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
            padding-bottom: 90px; /* Offset for fixed bottom button */
            overflow-x: hidden;
        }

        h1, h2, h3, h4, .font-serif {
            font-family: 'Cinzel', serif;
        }

        /* Container Limit for Desktop Readability */
        .app-container {
            max-width: 960px;
            margin: 0 auto;
        }

        /* Hero Header */
        .hero-section {
            position: relative;
            background: linear-gradient(180deg, rgba(11, 11, 13, 0.4) 0%, rgba(11, 11, 13, 0.95) 75%, var(--bg-dark) 100%),
                        url('https://images.unsplash.com/photo-1503951914875-452162b0f3f1?q=80&w=1200&auto=format&fit=crop') center/cover no-repeat;
            min-height: 380px;
            padding: 24px 20px 32px 20px;
            border-bottom-left-radius: 28px;
            border-bottom-right-radius: 28px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8);
        }

        .back-btn {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(20, 20, 24, 0.7);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .back-btn:hover {
            background: var(--gold-primary);
            color: #000;
            border-color: var(--gold-primary);
        }

        .tagline-badge {
            color: var(--gold-primary);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .hero-title {
            font-size: 2.3rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.5px;
            margin-top: 6px;
            margin-bottom: 4px;
        }

        @media (min-width: 768px) {
            .hero-title {
                font-size: 3.2rem;
            }
            .hero-section {
                min-height: 440px;
                padding-bottom: 48px;
            }
        }

        .hero-subtitle {
            color: var(--text-muted);
            font-size: 1rem;
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
            border-color: rgba(229, 193, 88, 0.3);
            transform: translateY(-2px);
        }

        .feature-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(229, 193, 88, 0.1);
            color: var(--gold-primary);
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
            line-height: 1.3;
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

        /* Service Cards */
        .service-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-dark);
            border-radius: 20px;
            padding: 20px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            cursor: pointer;
        }

        .service-card:hover {
            background-color: var(--bg-card-hover);
            border-color: rgba(229, 193, 88, 0.4);
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
        }

        .service-icon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.05);
            color: var(--gold-primary);
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
            margin-bottom: 4px;
        }

        .service-desc {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin-bottom: 6px;
            line-height: 1.4;
        }

        .service-duration {
            font-size: 0.76rem;
            color: #777788;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .service-price {
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--gold-primary);
            white-space: nowrap;
            text-align: right;
        }

        /* Service Process Items */
        .process-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 22px;
        }

        .process-number {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--gold-primary);
            background: rgba(229, 193, 88, 0.1);
            border: 1px solid rgba(229, 193, 88, 0.2);
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
            line-height: 1.4;
        }

        /* Fixed Bottom Sticky CTA */
        .fixed-bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1040;
            padding: 14px 20px;
            background: linear-gradient(180deg, rgba(11, 11, 13, 0) 0%, rgba(11, 11, 13, 0.95) 30%, var(--bg-dark) 100%);
            backdrop-filter: blur(12px);
            pointer-events: none;
        }

        .btn-booking-sticky {
            pointer-events: auto;
            background-color: var(--gold-primary);
            color: #0d0d0f;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 16px;
            padding: 14px 24px;
            width: 100%;
            max-width: 920px;
            margin: 0 auto;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 8px 25px var(--gold-glow);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-booking-sticky:hover, .btn-booking-sticky:focus {
            background-color: var(--gold-hover);
            color: #000000;
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(229, 193, 88, 0.35);
        }

        /* Dark Modal Custom */
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
            border-color: var(--gold-primary);
            box-shadow: 0 0 0 3px var(--gold-glow);
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
                <span class="badge bg-dark text-warning border border-secondary px-3 py-2 rounded-pill">
                    <i class="bi bi-shield-check me-1"></i> Official Barbershop
                </span>
            </div>

            <!-- Hero Branding -->
            <div>
                <div class="tagline-badge">
                    <i class="bi bi-scissors"></i> GLOWCUT BARBERSHOP
                </div>
                <h1 class="hero-title">Premium Haircut</h1>
                <p class="hero-subtitle mb-0">Seni memangkas rambut modern</p>
            </div>
        </header>

        <!-- MAIN SCROLLABLE CONTENT -->
        <main class="px-3 px-md-4 pt-4">

            <!-- SECTION 2: ABOUT SECTION -->
            <section class="mb-5">
                <h2 class="section-title">Tentang Barbershop Kami</h2>
                <div class="text-secondary style-about-text" style="color: #a0a0b5 !important; line-height: 1.7; font-size: 0.95rem;">
                    <p class="mb-3">
                        GlowCut Barbershop menghadirkan pengalaman grooming premium untuk pria modern Indonesia. Dikelola oleh barber bersertifikat dengan pengalaman lebih dari 5 tahun, kami memastikan setiap potongan rambut dilakukan dengan presisi, detail, dan penuh seni.
                    </p>
                    <p class="mb-0">
                        Dari classic haircut yang timeless hingga fade cut yang stylish, setiap layanan menggunakan alat profesional dan produk premium pilihan — mulai dari gunting Jaguar hingga pomade Layrite & American Crew — agar tampilan kamu selalu terbaik.
                    </p>
                </div>
            </section>

            <!-- SECTION 3: FEATURE HIGHLIGHTS GRID -->
            <section class="mb-5">
                <div class="row g-3">
                    <!-- Feature 1 -->
                    <div class="col-6 col-md-3">
                        <div class="feature-card">
                            <div class="feature-icon-box">
                                <i class="bi bi-scissors"></i>
                            </div>
                            <div>
                                <div class="feature-title">Master Barber</div>
                                <div class="feature-desc">Berpengalaman 5+ tahun</div>
                            </div>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="col-6 col-md-3">
                        <div class="feature-card">
                            <div class="feature-icon-box">
                                <i class="bi bi-armchair"></i>
                            </div>
                            <div>
                                <div class="feature-title">Kursi Premium</div>
                                <div class="feature-desc">Full leather recliner</div>
                            </div>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="col-6 col-md-3">
                        <div class="feature-card">
                            <div class="feature-icon-box">
                                <i class="bi bi-droplet-hot"></i>
                            </div>
                            <div>
                                <div class="feature-title">Hot Towel</div>
                                <div class="feature-desc">Tersedia di semua layanan</div>
                            </div>
                        </div>
                    </div>

                    <!-- Feature 4 -->
                    <div class="col-6 col-md-3">
                        <div class="feature-card">
                            <div class="feature-icon-box">
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <div>
                                <div class="feature-title">Rating 4.9</div>
                                <div class="feature-desc">Dari 500+ ulasan</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION 4: GALLERY SECTION -->
            <section class="mb-5">
                <h2 class="section-title">Galeri</h2>
                <div class="row g-2 g-md-3">
                    <div class="col-4 col-md-2">
                        <div class="gallery-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1585747860715-2ba37e788b70?q=80&w=400&auto=format&fit=crop" alt="Interior Barbershop">
                        </div>
                    </div>
                    <div class="col-4 col-md-2">
                        <div class="gallery-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1622286342621-4bd786c2447c?q=80&w=400&auto=format&fit=crop" alt="Haircut Action">
                        </div>
                    </div>
                    <div class="col-4 col-md-2">
                        <div class="gallery-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1503951914875-452162b0f3f1?q=80&w=400&auto=format&fit=crop" alt="Fade Cut Precision">
                        </div>
                    </div>
                    <div class="col-4 col-md-2">
                        <div class="gallery-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1621605815971-fbc98d665033?q=80&w=400&auto=format&fit=crop" alt="Beard Grooming">
                        </div>
                    </div>
                    <div class="col-4 col-md-2">
                        <div class="gallery-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1599351431202-1e0f0137899a?q=80&w=400&auto=format&fit=crop" alt="Vintage Barber Chair">
                        </div>
                    </div>
                    <div class="col-4 col-md-2">
                        <div class="gallery-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1512690459411-b9245aed614b?q=80&w=400&auto=format&fit=crop" alt="Clipper Styling">
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION 5: SERVICES LIST -->
            <section class="mb-5">
                <h2 class="section-title">Daftar Layanan</h2>
                <div class="d-flex flex-column gap-3">
                    
                    <!-- Service 1 -->
                    <div class="service-card" onclick="selectService('Haircut Classic', '75k')">
                        <div class="d-flex align-items-center gap-3">
                            <div class="service-icon">
                                <i class="bi bi-scissors"></i>
                            </div>
                            <div>
                                <div class="service-title">Haircut Classic</div>
                                <div class="service-desc">Potong rambut presisi, finishing pomade premium.</div>
                                <div class="service-duration"><i class="bi bi-clock"></i> 45 mnt</div>
                            </div>
                        </div>
                        <div class="service-price">Rp 75k</div>
                    </div>

                    <!-- Service 2 -->
                    <div class="service-card" onclick="selectService('Fade Cut', '90k')">
                        <div class="d-flex align-items-center gap-3">
                            <div class="service-icon">
                                <i class="bi bi-scissors"></i>
                            </div>
                            <div>
                                <div class="service-title">Fade Cut</div>
                                <div class="service-desc">Low/mid/high fade dengan clipper profesional.</div>
                                <div class="service-duration"><i class="bi bi-clock"></i> 60 mnt</div>
                            </div>
                        </div>
                        <div class="service-price">Rp 90k</div>
                    </div>

                    <!-- Service 3 -->
                    <div class="service-card" onclick="selectService('Hair Coloring', '250k')">
                        <div class="d-flex align-items-center gap-3">
                            <div class="service-icon">
                                <i class="bi bi-scissors"></i>
                            </div>
                            <div>
                                <div class="service-title">Hair Coloring</div>
                                <div class="service-desc">Pewarnaan Wella — natural, ombre, atau bold.</div>
                                <div class="service-duration"><i class="bi bi-clock"></i> 90 mnt</div>
                            </div>
                        </div>
                        <div class="service-price">Rp 250k</div>
                    </div>

                    <!-- Service 4 -->
                    <div class="service-card" onclick="selectService('Paket Wisuda Pria', '200k')">
                        <div class="d-flex align-items-center gap-3">
                            <div class="service-icon">
                                <i class="bi bi-scissors"></i>
                            </div>
                            <div>
                                <div class="service-title">Paket Wisuda Pria</div>
                                <div class="service-desc">Haircut + grooming + styling formal wisuda.</div>
                                <div class="service-duration"><i class="bi bi-clock"></i> 90 mnt</div>
                            </div>
                        </div>
                        <div class="service-price">Rp 200k</div>
                    </div>

                </div>
            </section>

            <!-- SECTION 6: SERVICE PROCESS -->
            <section class="mb-5">
                <h2 class="section-title">Proses Layanan</h2>
                <div class="pt-2">
                    
                    <!-- Process 1 -->
                    <div class="process-item">
                        <div class="process-number">01</div>
                        <div>
                            <div class="process-title">Konsultasi</div>
                            <p class="process-desc">Barber mendiskusikan gaya & preferensi rambut kamu.</p>
                        </div>
                    </div>

                    <!-- Process 2 -->
                    <div class="process-item">
                        <div class="process-number">02</div>
                        <div>
                            <div class="process-title">Cuci Rambut</div>
                            <p class="process-desc">Rambut dibersihkan menggunakan shampo premium.</p>
                        </div>
                    </div>

                    <!-- Process 3 -->
                    <div class="process-item">
                        <div class="process-number">03</div>
                        <div>
                            <div class="process-title">Pemangkasan</div>
                            <p class="process-desc">Proses potong presisi oleh barber bersertifikat.</p>
                        </div>
                    </div>

                    <!-- Process 4 -->
                    <div class="process-item">
                        <div class="process-number">04</div>
                        <div>
                            <div class="process-title">Finishing & Styling</div>
                            <p class="process-desc">Penataan akhir dengan pomade atau wax pilihan kamu.</p>
                        </div>
                    </div>

                </div>
            </section>

        </main>
    </div>

    <!-- SECTION 7: FIXED BOTTOM STICKY CTA BUTTON -->
    <div class="fixed-bottom-bar">
        <button type="button" class="btn-booking-sticky" data-bs-toggle="modal" data-bs-target="#bookingModal">
            <span>Booking Sekarang</span>
            <i class="bi bi-arrow-right fs-5"></i>
        </button>
    </div>

    <!-- BOOKING MODAL -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-dark">
                <div class="modal-header modal-header-dark">
                    <h5 class="modal-title font-serif fw-bold text-warning" id="bookingModalLabel">
                        <i class="bi bi-calendar-check me-2"></i>Reservasi Jadwal
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
                            <label for="serviceSelect" class="form-label text-secondary small fw-bold">PILIH LAYANAN</label>
                            <select class="form-select form-control-dark" id="serviceSelect">
                                <option value="Haircut Classic">Haircut Classic (Rp 75k)</option>
                                <option value="Fade Cut">Fade Cut (Rp 90k)</option>
                                <option value="Hair Coloring">Hair Coloring (Rp 250k)</option>
                                <option value="Paket Wisuda Pria">Paket Wisuda Pria (Rp 200k)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tanggalInput" class="form-label text-secondary small fw-bold">TANGGAL & WAKTU</label>
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
        // Select service dynamically when clicking on service cards
        function selectService(serviceName, price) {
            const selectElement = document.getElementById('serviceSelect');
            if (selectElement) {
                for (let option of selectElement.options) {
                    if (option.value === serviceName) {
                        option.selected = true;
                        break;
                    }
                }
            }
            // Open modal
            const modal = new bootstrap.Modal(document.getElementById('bookingModal'));
            modal.show();
        }

        // Handle booking submission
        function handleFormSubmit(event) {
            event.preventDefault();
            const nama = document.getElementById('namaInput').value;
            const layanan = document.getElementById('serviceSelect').value;
            
            // Close modal
            const modalEl = document.getElementById('bookingModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();

            // Simple visual feedback
            alert(`Terima kasih ${nama}! Reservasi kamu untuk "${layanan}" berhasil dibuat. Kami akan menghubungi kamu segera.`);
        }
    </script>
</body>
</html>