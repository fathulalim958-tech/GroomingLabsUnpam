<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun - GlowCut</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts: Cinzel & Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-dark: #0b0b0e;
            --bg-card: #141418;
            --input-bg: #1a1a20;
            --input-border: #2d2d38;
            --input-focus-border: #e2be6e;
            --gold-primary: #e2be6e;
            --gold-hover: #f0cb7d;
            --text-main: #f0f0f5;
            --text-muted: #8e8e9e;
            --text-label: #a0a0b2;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
            margin: 0;
        }

        .font-serif {
            font-family: 'Cinzel', serif;
        }

        .register-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }

        .register-card {
            background-color: var(--bg-card);
            border: 1px solid rgba(226, 190, 110, 0.15);
            border-radius: 24px;
            padding: 36px 32px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
        }

        @media (max-width: 575.98px) {
            .register-card {
                padding: 24px 18px;
                border-radius: 20px;
            }
        }

        /* Back Button */
        .btn-back {
            color: #d1d1db;
            text-decoration: none;
            font-size: 0.9rem;
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

        /* Header */
        .header-title {
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            margin-top: 20px;
            margin-bottom: 4px;
            text-align: center;
        }
        
        .header-subtitle {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 28px;
            text-align: center;
        }

        .form-label-custom {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 1.2px;
            color: var(--text-label);
            text-transform: uppercase;
            margin-bottom: 8px;
            display: block;
        }

        /* Custom Input Container */
        .custom-input-group {
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 14px;
            display: flex;
            align-items: center;
            overflow: hidden;
            transition: all 0.25s ease;
        }

        .custom-input-group:focus-within {
            border-color: var(--input-focus-border);
            box-shadow: 0 0 0 3px rgba(226, 190, 110, 0.15);
        }

        .custom-input-group .form-control-custom {
            background: transparent;
            border: none;
            color: #ffffff;
            padding: 13px 16px;
            font-size: 0.95rem;
            width: 100%;
        }

        .custom-input-group .form-control-custom:focus {
            outline: none;
            box-shadow: none;
        }

        .custom-input-group .form-control-custom::placeholder {
            color: #4a4a5a;
        }

        /* Prefix Badge Phone */
        .prefix-badge {
            padding: 13px 14px;
            color: var(--gold-primary);
            font-weight: 600;
            font-size: 0.95rem;
            border-right: 1px solid var(--input-border);
            background: rgba(255, 255, 255, 0.02);
            user-select: none;
        }

        /* Icon Toggle Password */
        .input-icon-btn {
            background: transparent;
            border: none;
            color: #6a6a7c;
            padding: 0 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: color 0.2s ease;
        }

        .input-icon-btn:hover {
            color: var(--gold-primary);
        }

        /* Custom Checkbox */
        .form-check-input.custom-checkbox {
            background-color: var(--input-bg);
            border-color: var(--input-border);
            width: 18px;
            height: 18px;
            margin-top: 2px;
            cursor: pointer;
        }

        .form-check-input.custom-checkbox:checked {
            background-color: var(--gold-primary);
            border-color: var(--gold-primary);
        }

        .terms-text {
            font-size: 0.85rem;
            color: var(--text-muted);
            line-height: 1.4;
        }

        .terms-text a {
            color: var(--gold-primary);
            text-decoration: none;
        }

        .terms-text a:hover {
            text-decoration: underline;
        }

        /* Submit Button */
        .btn-gold-submit {
            background-color: var(--gold-primary);
            color: #000000;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 14px 20px;
            border-radius: 14px;
            border: none;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(226, 190, 110, 0.2);
        }

        .btn-gold-submit:hover {
            background-color: var(--gold-hover);
            color: #000000;
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(226, 190, 110, 0.3);
        }
    </style>
</head>

<body>

    <div class="register-container">
        <div class="register-card">

            <!-- Back Link -->
            <div>
                <a href="javascript:history.back()" class="btn-back">
                    <i class="bi bi-arrow-left fs-6"></i> Kembali
                </a>
            </div>

            <!-- Page Title Header -->
            <header>
                <h1 class="header-title font-serif">Buat Akun</h1>
                <p class="header-subtitle">Daftar untuk mendapatkan pengalaman premium</p>
            </header>

            <!-- Registration Form -->
            <form action="{{ route('Register.post') }}" method="POST">
                @csrf

                <!-- NAMA LENGKAP -->
                <div class="mb-3">
                    <label for="nameInput" class="form-label-custom">NAMA LENGKAP</label>
                    <div class="custom-input-group">
                        <input type="text" name="name" id="nameInput" class="form-control-custom"
                            placeholder="Ahmad Rizky" value="{{ old('name') }}" required>
                    </div>
                    @error('name')
                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                    @enderror
                </div>

                <!-- NO. TELEPON -->
                <div class="mb-3">
                    <label for="phoneInput" class="form-label-custom">NO. TELEPON</label>
                    <div class="custom-input-group">
                        <span class="prefix-badge">+62</span>
                        <input type="tel" name="phone" id="phoneInput" class="form-control-custom"
                            placeholder="812 3456 7890" value="{{ old('phone') }}" required>
                    </div>
                    @error('phone')
                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                    @enderror
                </div>

                <!-- EMAIL -->
                <div class="mb-3">
                    <label for="emailInput" class="form-label-custom">EMAIL</label>
                    <div class="custom-input-group">
                        <input type="email" name="email" id="emailInput" class="form-control-custom"
                            placeholder="ahmad@email.com" value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                    @enderror
                </div>

                <!-- PASSWORD -->
                <div class="mb-3">
                    <label for="passwordInput" class="form-label-custom">PASSWORD</label>
                    <div class="custom-input-group">
                        <input type="password" name="password" id="passwordInput" class="form-control-custom"
                            placeholder="Min. 8 karakter" required>
                        <button type="button" class="input-icon-btn" onclick="togglePasswordVisibility()" aria-label="Toggle password visibility">
                            <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                    @enderror
                </div>

                <!-- CHECKBOX SYARAT & KETENTUAN -->
                <div class="mb-4">
                    <div class="form-check text-start d-flex gap-2 align-items-start ps-0">
                        <input class="form-check-input custom-checkbox ms-0" type="checkbox" name="terms" value="1"
                            id="termsCheck" required>
                        <label class="form-check-label terms-text" for="termsCheck">
                            Saya menyetujui <a href="#">Syarat &amp; Ketentuan</a> serta <a href="#">Kebijakan Privasi</a> GlowCut
                        </label>
                    </div>
                    @error('terms')
                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                    @enderror
                </div>

                <!-- TOMBOL SUBMIT -->
                <button type="submit" class="btn btn-gold-submit">Daftar Sekarang</button>
            </form>

        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle password visibility function
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('passwordInput');
            const toggleIcon = document.getElementById('togglePasswordIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            }
        }
    </script>
</body>

</html>