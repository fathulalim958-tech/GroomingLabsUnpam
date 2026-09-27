<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barbershop Premium - Login</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --bg-dark: #0a0a0a;
            --card-bg: #141414;
            --input-bg: #1e1e1e;
            --gold-primary: #e5c158;
            --gold-hover: #d4b046;
            --gold-glow: rgba(229, 193, 88, 0.25);
            --text-muted: #9e9e9e;
            --border-color: #2a2a2a;
        }

        body {
            background-color: var(--bg-dark);
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 16px;
        }

        /* Container responsif utama */
        .login-wrapper {
            width: 100%;
            max-width: 420px;
            /* Default ukuran mobile */
            transition: all 0.4s ease;
        }

        /* Tampilan Melebar pada Laptop/Desktop (min-width: 768px) */
        @media (min-width: 768px) {
            .login-wrapper {
                max-width: 900px;
                /* Melebar di laptop */
            }
        }

        @media (min-width: 1200px) {
            .login-wrapper {
                max-width: 1020px;
                /* Sangat leluasa di layar desktop besar */
            }
        }

        /* Card Container */
        .main-card {
            background-color: var(--card-bg);
            border-radius: 24px;
            border: 1px solid var(--border-color);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7);
            overflow: hidden;
        }

        /* Banner Hero / Kolom Kiri Gambar */
        .banner-side {
            position: relative;
            background: url('https://images.unsplash.com/photo-1503951914875-452162b0f3f1?q=80&w=1200&auto=format&fit=crop') center/cover no-repeat;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 24px;
        }

        /* Penyesuaian banner saat di laptop (2 kolom) */
        @media (min-width: 768px) {
            .banner-side {
                min-height: 100%;
                padding: 40px;
                justify-content: space-between;
            }
        }

        /* Overlay Gelap Transparan pada Gambar */
        .banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, rgba(10, 10, 10, 0.3) 0%, rgba(10, 10, 10, 0.85) 100%);
            z-index: 1;
        }

        .banner-content {
            position: relative;
            z-index: 2;
        }

        .brand-badge {
            display: inline-block;
            color: var(--gold-primary);
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            padding: 6px 14px;
            border-radius: 30px;
            border: 1px solid rgba(229, 193, 88, 0.3);
            margin-bottom: 8px;
        }

        .brand-heading {
            font-family: 'Cinzel', serif;
            color: #ffffff;
            font-weight: 700;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.8);
        }

        .brand-subtext {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            max-width: 380px;
        }

        /* Form Side */
        .form-side {
            padding: 28px 24px;
        }

        @media (min-width: 768px) {
            .form-side {
                padding: 48px 40px;
            }
        }

        /* Role Switcher Tab */
        .role-switcher {
            background-color: var(--input-bg);
            border-radius: 14px;
            padding: 5px;
            display: flex;
            gap: 4px;
            margin-bottom: 24px;
            border: 1px solid var(--border-color);
        }

        .role-btn {
            flex: 1;
            padding: 10px 14px;
            border: none;
            background: transparent;
            color: var(--text-muted);
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
        }

        .role-btn.active {
            background-color: var(--gold-primary);
            color: #000000;
            box-shadow: 0 4px 15px var(--gold-glow);
        }

        /* Custom Input Groups */
        .form-label-custom {
            color: var(--gold-primary);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
            display: block;
        }

        .custom-input-group {
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            padding: 2px 14px;
        }

        .custom-input-group:focus-within {
            border-color: var(--gold-primary);
            box-shadow: 0 0 0 3px var(--gold-glow);
        }

        .country-code {
            color: var(--gold-primary);
            font-weight: 700;
            padding-right: 12px;
            border-right: 1px solid var(--border-color);
            font-size: 0.9rem;
            user-select: none;
        }

        .form-control-custom {
            background: transparent;
            border: none;
            color: #ffffff;
            padding: 12px;
            width: 100%;
            font-size: 0.95rem;
        }

        .form-control-custom:focus {
            outline: none;
            box-shadow: none;
            background: transparent;
            color: #ffffff;
        }

        .form-control-custom::placeholder {
            color: #555555;
        }

        .input-icon {
            color: var(--text-muted);
            font-size: 1.1rem;
            transition: color 0.3s;
        }

        .custom-input-group:focus-within .input-icon {
            color: var(--gold-primary);
        }

        .forgot-link {
            color: var(--gold-primary);
            font-size: 0.82rem;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.2s;
        }

        .forgot-link:hover {
            color: var(--gold-hover);
            text-decoration: underline;
        }

        /* Custom Buttons */
        .btn-submit {
            background-color: var(--gold-primary);
            color: #000000;
            font-weight: 700;
            border-radius: 12px;
            padding: 13px;
            width: 100%;
            border: none;
            margin-top: 18px;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px var(--gold-glow);
        }

        .btn-submit:hover {
            background-color: var(--gold-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px var(--gold-glow);
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.78rem;
            margin: 22px 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border-color);
        }

        .divider span {
            padding: 0 12px;
            text-transform: lowercase;
        }

        .btn-register {
            background-color: transparent;
            color: #ffffff;
            font-weight: 600;
            border-radius: 12px;
            padding: 12px;
            width: 100%;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            background-color: #1e1e1e;
            border-color: #444444;
            color: #ffffff;
        }

        .terms-text {
            color: var(--text-muted);
            font-size: 0.78rem;
            text-align: center;
            margin-top: 24px;
        }

        .terms-text a {
            color: var(--gold-primary);
            text-decoration: none;
            font-weight: 600;
        }

        .terms-text a:hover {
            text-decoration: underline;
        }

        /* Notifikasi pesan feedback sederhana */
        .toast-msg {
            display: none;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.85rem;
            margin-bottom: 16px;
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
            --surface-2: #eef3f8;
            --surface-3: #e7edf7;
            --text: #111827;
            --text-soft: #475467;
            --muted: #667085;
            --border: #dfe7f1;
            --gold: #d4a63a;
            --gold-strong: #b77f11;
            --gold-glow: rgba(212, 166, 58, 0.18);
            --shadow: rgba(15, 23, 42, 0.08);
        }

        html[data-theme="dark"] {
            color-scheme: dark;
            --bg-app: #0a0a0a;
            --surface: #141414;
            --surface-2: #1e1e1e;
            --surface-3: #262626;
            --text: #f5f5f5;
            --text-soft: rgba(255, 255, 255, 0.8);
            --muted: #9e9e9e;
            --border: #2a2a2a;
            --gold: #e5c158;
            --gold-strong: #d4b046;
            --gold-glow: rgba(229, 193, 88, 0.25);
            --shadow: rgba(0, 0, 0, 0.7);
        }

        body {
            background: var(--bg-app);
            color: var(--text);
            transition: background 0.25s ease, color 0.25s ease;
        }

        .main-card,
        .custom-input-group .form-control-custom,
        .role-switcher,
        .input-icon,
        .btn-submit,
        .btn-register,
        .alert-danger,
        .toast-msg {
            transition: all 0.25s ease;
        }

        .main-card {
            background-color: var(--surface);
            border-color: var(--border);
            box-shadow: 0 20px 50px var(--shadow);
        }

        .form-side {
            background-color: var(--surface);
        }

        .custom-input-group {
            background: var(--surface-2);
            border: 1px solid var(--border);
        }

        .custom-input-group .form-control-custom {
            background: transparent;
            color: var(--text);
            border: none;
        }

        .form-control-custom::placeholder {
            color: var(--muted);
        }

        .form-label-custom,
        .brand-badge,
        .brand-heading,
        .brand-subtext,
        .terms-text,
        .forgot-link {
            color: var(--text);
        }

        .role-switcher {
            background-color: var(--surface-2);
            border-color: var(--border);
        }

        .role-btn {
            color: var(--muted);
        }

        .role-btn.active {
            background-color: var(--gold);
            color: #111111;
            box-shadow: 0 4px 15px var(--gold-glow);
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
            font-size: 1.1rem;
            cursor: pointer;
        }

        .theme-toggle-btn:hover {
            border-color: var(--gold);
            color: var(--gold-strong);
        }
    </style>
</head>

<body>

    <div class="login-wrapper">
        <div class="main-card">
            <div class="row g-0">

                <!-- KOLOM KIRI / HERO BANNER (Di Desktop/Laptop melebar di sebelah kiri, di HP berada di paling atas) -->
                <div class="col-12 col-md-5 col-lg-6 banner-side">
                    <div class="banner-overlay"></div>

                    <!-- Konten Khusus Desktop (Atas) -->
                    <div class="banner-content d-none d-md-block">
                        <span class="brand-badge">GROOMINGLABS UNPAM</span>
                    </div>

                    <!-- Konten Banner Utama -->
                    <div class="banner-content">
                        <div class="d-md-none">
                            <span class="brand-badge">PREMIUM SINCE 2019</span>
                        </div>
                        <h2 class="brand-heading d-none d-md-block fs-2 mb-2">Gentlemen's Choice</h2>
                        <p class="brand-subtext d-none d-md-block mb-0">Pengalaman cukur rambut & perawatan maskulin
                            terbaik dengan standar pelayanan kelas dunia.</p>
                        <div class="d-none d-md-block mt-4">
                            <span class="badge rounded-pill bg-dark border border-secondary text-warning px-3 py-2">
                                <i class="bi bi-star-fill me-1"></i> Rating 4.9 (2,400+ Ulasan)
                            </span>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN / FORM LOGIN -->
                <div class="col-12 col-md-7 col-lg-6 form-side">

                    <!-- Judul Formulir -->
                    <div class="mb-4">
                        <h3 class="fw-bold mb-1 text-center" style="font-family: 'Cinzel', serif; font-size: 1.85rem;">
                            Selamat Datang</h3>
                        {{-- <p class="text-secondary small mb-0">Masuk sebagai siapa?</p> --}}
                    </div>

                    <!-- Pesan Notifikasi Simulasi -->
                    <div id="alertBox" class="toast-msg bg-danger-subtle text-danger border border-danger-subtle">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><span id="alertMsg">Terjadi
                            kesalahan</span>
                    </div>

                    <!-- Form Login -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('login') }}" method="POST" id="loginForm">
                        @csrf
                        <!-- Field Email -->
                        <div class="mb-3">
                            <label for="emailInput" class="form-label-custom">EMAIL</label>
                            <div class="custom-input-group">
                                <span class="input-icon"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" value="{{ old('email') }}" id="emailInput"
                                    class="form-control-custom" placeholder="example@email.com" autocomplete="email">
                            </div>
                        </div>

                        <!-- Field Password -->
                        <div class="mb-2">
                            <label for="passwordInput" class="form-label-custom">PASSWORD</label>
                            <div class="custom-input-group">
                                <i class="bi bi-lock input-icon me-2"></i>
                                <input type="password" name="password" id="passwordInput" class="form-control-custom"
                                    placeholder="••••••••">
                                <i class="bi bi-eye input-icon ms-2" id="togglePasswordBtn" style="cursor: pointer;"
                                    title="Tampilkan/Sembunyikan Password"></i>
                            </div>
                            <div class="d-flex justify-content-end mt-2">
                                <a href="#" class="forgot-link" onclick="handleForgot(event)">Lupa password?</a>
                            </div>
                        </div>

                        <!-- Tombol Submit / Masuk -->
                        <button type="submit" class="btn btn-submit">Masuk</button>
                    </form>

                    <!-- Divider 'atau' -->
                    <div class="divider">
                        <span>atau</span>
                    </div>

                    <!-- Tombol Daftar Akun Baru -->
                    <a href="/Register">
                        <button type="button" class="btn btn-register" onclick="handleRegister()">Daftar Akun Baru
                        </button>
                    </a>

                    <!-- Syarat dan Ketentuan -->
                    <p class="terms-text">
                        Dengan masuk, kamu menyetujui <a href="#"
                            onclick="event.preventDefault(); alert('Ketentuan Layanan: Menjaga privasi & kenyamanan bersama.');">Syarat
                            & Ketentuan</a>
                    </p>

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- <script>
        // State Peran Aktif (default: pelanggan)
        let currentRole = 'pelanggan';

        // Switcher Peran (Pelanggan / Kasir)
        function switchRole(role) {
            currentRole = role;
            const btnPelanggan = document.getElementById('btnPelanggan');
            const btnKasir = document.getElementById('btnKasir');

            if (role === 'pelanggan') {
                btnPelanggan.classList.add('active');
                btnKasir.classList.remove('active');
            } else {
                btnKasir.classList.add('active');
                btnPelanggan.classList.remove('active');
            }
            hideAlert();
        }

        // Toggle Password Visibility
        const togglePasswordBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('passwordInput');

        togglePasswordBtn.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            
            // Toggle ikon mata
            this.classList.toggle('bi-eye', !isPassword);
            this.classList.toggle('bi-eye-slash', isPassword);
        });

        // Penanganan Submit Form
        function handleLogin(e) {
            e.preventDefault();
            const phone = document.getElementById('phoneInput').value.trim();
            const password = document.getElementById('passwordInput').value.trim();

            if (!phone || !password) {
                showAlert('Silakan isi Nomor Telepon dan Password!');
                return;
            }

            // Simulasi Login Berhasil
            showAlert(`Berhasil masuk sebagai ${currentRole === 'pelanggan' ? 'Pelanggan' : 'Kasir / Karyawan'}!`, true);
        }

        function handleForgot(e) {
            e.preventDefault();
            const phone = document.getElementById('phoneInput').value.trim();
            if (!phone) {
                showAlert('Masukkan No. Telepon Anda terlebih dahulu untuk meriset password.');
            } else {
                showAlert(`Instruksi riset password telah dikirim ke +62 ${phone}`, true);
            }
        }

        function handleRegister() {
            showAlert('Mengarahkan ke halaman pendaftaran akun baru...', true);
        }

        // Helper fungsi Notifikasi Pesan
        function showAlert(msg, isSuccess = false) {
            const alertBox = document.getElementById('alertBox');
            const alertMsg = document.getElementById('alertMsg');
            
            alertMsg.textContent = msg;
            alertBox.style.display = 'block';

            if (isSuccess) {
                alertBox.className = 'toast-msg bg-success-subtle text-success border border-success-subtle';
            } else {
                alertBox.className = 'toast-msg bg-danger-subtle text-danger border border-danger-subtle';
            }
        }

        function hideAlert() {
            document.getElementById('alertBox').style.display = 'none';
        }
    </script> --}}
    {{-- <button type="button" class="theme-toggle-btn" id="themeToggle" aria-label="Ganti tema">🌙</button> --}}

    <script>
        (function() {
            const saved = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', saved);
            const themeToggle = document.getElementById('themeToggle');
            if (themeToggle) {
                themeToggle.textContent = saved === 'dark' ? '🌙' : '☀️';
            }

            document.addEventListener('click', function(event) {
                const toggle = event.target.closest('#themeToggle');
                if (!toggle) return;

                const current = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' :
                    'dark';
                document.documentElement.setAttribute('data-theme', current);
                localStorage.setItem('theme', current);
                toggle.textContent = current === 'dark' ? '🌙' : '☀️';
            });
        })();
    </script>
</body>

</html>
