<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Barbershop - GlowCut</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-dark: #0b0b0e;
            --bg-card: #141419;
            --bg-input: #1a1a22;
            --gold-primary: #d4af37;
            --gold-hover: #f3ca40;
            --text-muted: #9a9ab0;
            --border-dark: #22222d;
            --border-gold-glow: rgba(212, 175, 55, 0.4);
        }

        body {
            background-color: var(--bg-dark);
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            padding: 20px 0;
        }

        .font-serif {
            font-family: 'Cinzel', 'Playfair Display', serif;
        }

        /* Container Layout */
        .booking-container {
            max-width: 100%;
            margin: 0 auto;
            width: 100%;
            padding: 0 12px;
        }

        @media (min-width: 992px) {
            .booking-container {
                max-width: 1100px;
                padding: 0;
            }
            body {
                padding: 40px 0;
            }
        }

        @media (min-width: 1200px) {
            .booking-container {
                max-width: 1280px;
            }
        }

        /* Header Navigation */
        .btn-back {
            color: #d1d1db;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.25s ease;
            padding: 6px 14px;
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
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            margin-top: 20px;
            margin-bottom: 6px;
        }

        @media (min-width: 992px) {
            .btn-back { font-size: 1.05rem; padding: 8px 18px; }
            .header-title { font-size: 3rem; margin-top: 28px; }
        }

        /* Section Card Box */
        .section-box {
            background-color: var(--bg-card);
            border: 1px solid var(--border-dark);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 24px;
        }

        @media (min-width: 992px) {
            .section-box {
                padding: 32px;
                border-radius: 24px;
            }
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gold-primary);
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Service Cards Selectable */
        .service-option {
            background-color: var(--bg-input);
            border: 1px solid var(--border-dark);
            border-radius: 14px;
            padding: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
        }

        .service-option:hover {
            border-color: rgba(212, 175, 55, 0.5);
            transform: translateY(-2px);
        }

        .service-option.active {
            border-color: var(--gold-primary);
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(20, 20, 25, 0.9) 100%);
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.15);
        }

        /* Barber Select Cards */
        .barber-card {
            background-color: var(--bg-input);
            border: 1px solid var(--border-dark);
            border-radius: 14px;
            padding: 14px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .barber-card img {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            border: 2px solid var(--border-dark);
            transition: all 0.3s ease;
        }

        .barber-card:hover {
            border-color: rgba(212, 175, 55, 0.5);
        }

        .barber-card.active {
            border-color: var(--gold-primary);
            background: linear-gradient(180deg, rgba(212, 175, 55, 0.1) 0%, rgba(20, 20, 25, 1) 100%);
        }

        .barber-card.active img {
            border-color: var(--gold-primary);
            box-shadow: 0 0 12px rgba(212, 175, 55, 0.4);
        }

        /* Form Control Custom Styling */
        .form-control-custom {
            background-color: var(--bg-input);
            border: 1px solid var(--border-dark);
            color: #ffffff;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control-custom:focus {
            background-color: var(--bg-input);
            border-color: var(--gold-primary);
            color: #ffffff;
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.2);
            outline: none;
        }

        .form-control-custom::placeholder {
            color: var(--text-muted);
        }

        /* Time Slots Selection */
        .time-slot {
            background-color: var(--bg-input);
            border: 1px solid var(--border-dark);
            color: #ffffff;
            border-radius: 10px;
            padding: 10px 0;
            text-align: center;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .time-slot:hover {
            border-color: rgba(212, 175, 55, 0.5);
        }

        .time-slot.active {
            background-color: var(--gold-primary);
            color: #000000;
            border-color: var(--gold-primary);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }

        /* Summary Sidebar Sticky (Desktop) */
        .summary-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-dark);
            border-radius: 20px;
            padding: 24px;
            position: sticky;
            top: 24px;
        }

        .btn-gold {
            background: linear-gradient(135deg, var(--gold-primary) 0%, #b89327 100%);
            color: #000000;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            padding: 14px 24px;
            width: 100%;
            font-size: 1rem;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-gold:hover {
            background: linear-gradient(135deg, var(--gold-hover) 0%, var(--gold-primary) 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3);
        }
    </style>
</head>
<body>

    <div class="container px-3 px-md-4">
        <div class="booking-container">

            <!-- HEADER SECTION -->
            <header class="mb-4">
                <a href="javascript:history.back()" class="btn-back">
                    <i class="bi bi-arrow-left fs-5"></i> Kembali ke Kategori
                </a>
                <h1 class="header-title font-serif">Booking Barbershop</h1>
                <p class="text-muted">Pilih layanan, barber favorit, dan tentukan jadwal kamu.</p>
            </header>

            <form id="bookingForm" onsubmit="event.preventDefault(); alert('Pesanan Berhasil Dibuat!');">
                <div class="row g-4">
                    
                    <!-- LEFT COLUMN: FORM INPUTS -->
                    <div class="col-12 col-lg-8">
                        
                        <!-- 1. PILIH LAYANAN -->
                        <div class="section-box">
                            <div class="section-title">
                                <i class="bi bi-scissors fs-4"></i> 1. Pilih Layanan
                            </div>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="service-option active" onclick="selectService(this, 'Gentleman Cut', 65000)">
                                        <div>
                                            <h6 class="mb-1 text-white fw-semibold">Gentleman Cut</h6>
                                            <small class="text-muted d-block">Potong rambut, Cuci, Styling & Tonic</small>
                                        </div>
                                        <div class="text-end ms-2">
                                            <span class="fw-bold text-warning">Rp 65.000</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="service-option" onclick="selectService(this, 'Hair Cut & Shave', 90000)">
                                        <div>
                                            <h6 class="mb-1 text-white fw-semibold">Hair Cut & Shave</h6>
                                            <small class="text-muted d-block">Potong, Cuci, Cukur Kumis/Jenggot</small>
                                        </div>
                                        <div class="text-end ms-2">
                                            <span class="fw-bold text-warning">Rp 90.000</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="service-option" onclick="selectService(this, 'Hair Color Treatment', 150000)">
                                        <div>
                                            <h6 class="mb-1 text-white fw-semibold">Hair Coloring</h6>
                                            <small class="text-muted d-block">Pewarnaan dasar / Bleaching + Color</small>
                                        </div>
                                        <div class="text-end ms-2">
                                            <span class="fw-bold text-warning">Rp 150.000</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="service-option" onclick="selectService(this, 'Glow Hair Spa', 85000)">
                                        <div>
                                            <h6 class="mb-1 text-white fw-semibold">Glow Hair Spa</h6>
                                            <small class="text-muted d-block">Creambath, Pijat Kepala & Hot Towel</small>
                                        </div>
                                        <div class="text-end ms-2">
                                            <span class="fw-bold text-warning">Rp 85.000</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. PILIH BARBER -->
                        <div class="section-box">
                            <div class="section-title">
                                <i class="bi bi-person-badge fs-4"></i> 2. Pilih Barber / Kapster
                            </div>
                            <div class="row g-3">
                                <div class="col-6 col-sm-3">
                                    <div class="barber-card active" onclick="selectBarber(this, 'Budi - Sr. Barber')">
                                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop" alt="Barber">
                                        <h6 class="mb-0 text-white fs-6">Budi</h6>
                                        <small class="text-muted">Senior Barber</small>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <div class="barber-card" onclick="selectBarber(this, 'Alex - Fade Specialist')">
                                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop" alt="Barber">
                                        <h6 class="mb-0 text-white fs-6">Alex</h6>
                                        <small class="text-muted">Fade Specialist</small>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <div class="barber-card" onclick="selectBarber(this, 'Rian - Stylist')">
                                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=200&auto=format&fit=crop" alt="Barber">
                                        <h6 class="mb-0 text-white fs-6">Rian</h6>
                                        <small class="text-muted">Stylist</small>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <div class="barber-card" onclick="selectBarber(this, 'Siapa Saja (Bebas)')">
                                        <div class="d-flex align-items-center justify-content-center mx-auto mb-2 rounded-circle bg-dark" style="width:65px; height:65px; border: 2px solid var(--border-dark);">
                                            <i class="bi bi-shuffle text-warning fs-3"></i>
                                        </div>
                                        <h6 class="mb-0 text-white fs-6">Bebas</h6>
                                        <small class="text-muted">Barber Tersedia</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. PILIH TANGGAL & JAM -->
                        <div class="section-box">
                            <div class="section-title">
                                <i class="bi bi-calendar-event fs-4"></i> 3. Tanggal & Waktu
                            </div>
                            <div class="row g-3">
                                <div class="col-12 col-md-5">
                                    <label class="form-label text-muted small fw-medium">Tanggal Kedatangan</label>
                                    <input type="date" class="form-control form-control-custom" id="bookingDate" value="2026-09-28">
                                </div>
                                <div class="col-12 col-md-7">
                                    <label class="form-label text-muted small fw-medium">Pilih Jam Operasional</label>
                                    <div class="row g-2">
                                        <div class="col-4"><div class="time-slot" onclick="selectTime(this, '10:00')">10:00</div></div>
                                        <div class="col-4"><div class="time-slot active" onclick="selectTime(this, '13:00')">13:00</div></div>
                                        <div class="col-4"><div class="time-slot" onclick="selectTime(this, '15:00')">15:00</div></div>
                                        <div class="col-4"><div class="time-slot" onclick="selectTime(this, '17:00')">17:00</div></div>
                                        <div class="col-4"><div class="time-slot" onclick="selectTime(this, '19:00')">19:00</div></div>
                                        <div class="col-4"><div class="time-slot" onclick="selectTime(this, '20:30')">20:30</div></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. DATA PEMESAN -->
                        <div class="section-box">
                            <div class="section-title">
                                <i class="bi bi-person-vcard fs-4"></i> 4. Informasi Pemesan
                            </div>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label text-muted small fw-medium">Nama Lengkap</label>
                                    <input type="text" class="form-control form-control-custom" placeholder="Masukkan nama kamu" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label text-muted small fw-medium">Nomor WhatsApp</label>
                                    <input type="tel" class="form-control form-control-custom" placeholder="08xxxxxxxxxx" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted small fw-medium">Catatan Khusus (Opsional)</label>
                                    <textarea class="form-control form-control-custom" rows="2" placeholder="Contoh: Minta gaya potongan undercut pendek"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- RIGHT COLUMN: RINGKASAN & PEMBAYARAN -->
                    <div class="col-12 col-lg-4">
                        <div class="summary-card">
                            <h4 class="font-serif text-white mb-3">Ringkasan Pesanan</h4>
                            <hr style="border-color: var(--border-dark);">

                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Layanan Dipilih:</small>
                                <span class="fw-semibold text-white d-block" id="summaryService">Gentleman Cut</span>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Barber:</small>
                                <span class="fw-semibold text-white d-block" id="summaryBarber">Budi - Sr. Barber</span>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Waktu Kedatangan:</small>
                                <span class="fw-semibold text-white d-block" id="summaryTime">Hari Ini, 13:00 WIB</span>
                            </div>

                            <hr style="border-color: var(--border-dark);" class="my-3">

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Harga Layanan</span>
                                <span class="text-white fw-medium" id="summaryPrice">Rp 65.000</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Biaya Layanan/App</span>
                                <span class="text-white fw-medium">Rp 2.000</span>
                            </div>

                            <hr style="border-color: var(--border-dark);" class="my-3">

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="fw-semibold text-white">Total Bayar</span>
                                <span class="fs-4 fw-bold text-warning" id="summaryTotal">Rp 67.000</span>
                            </div>

                            <button type="submit" class="btn btn-gold">
                                <i class="bi bi-check-circle-fill me-2"></i> Konfirmasi Booking
                            </button>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <!-- Interactive Script untuk Update Ringkasan -->
    <script>
        let currentPrice = 65000;

        function selectService(element, serviceName, price) {
            document.querySelectorAll('.service-option').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
            
            currentPrice = price;
            document.getElementById('summaryService').innerText = serviceName;
            document.getElementById('summaryPrice').innerText = 'Rp ' + price.toLocaleString('id-ID');
            document.getElementById('summaryTotal').innerText = 'Rp ' + (price + 2000).toLocaleString('id-ID');
        }

        function selectBarber(element, barberName) {
            document.querySelectorAll('.barber-card').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
            document.getElementById('summaryBarber').innerText = barberName;
        }

        function selectTime(element, timeStr) {
            document.querySelectorAll('.time-slot').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
            document.getElementById('summaryTime').innerText = timeStr + ' WIB';
        }
    </script>
</body>
</html>