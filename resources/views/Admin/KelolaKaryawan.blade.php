<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Karyawan - GlowCut</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg-dark: #1d1f24;
            --bg-panel: #23262d;
            --gold: #f3c65d;
            --text: #f4f4f4;
            --muted: #a5a5a8;
            --border: rgba(255, 255, 255, 0.18);
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            min-height: 100%;
            background: var(--bg-dark);
            color: var(--text);
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            min-height: 100vh;
        }

        .blank-admin-page {
            min-height: 100vh;
            background: #1f2127;
            padding: 1.2rem 1.5rem 2rem;
        }

        .blank-admin-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            min-height: 120px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            padding: 0.3rem 0 0.5rem;
        }

        .blank-admin-label {
            color: var(--gold);
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 0.12rem;
            text-transform: uppercase;
            margin: 0 0 0.35rem;
        }

        .blank-admin-title {
            font-size: clamp(2.5rem, 5vw, 5rem);
            line-height: 0.95;
            font-weight: 700;
            letter-spacing: -0.06em;
            color: #f3f3f3;
            margin: 0;
            font-family: Georgia, 'Times New Roman', serif;
        }

        .blank-admin-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.7rem;
            border: 1px solid rgba(255,255,255,0.28);
            border-radius: 999px;
            background: transparent;
            color: var(--text);
            padding: 0.8rem 1.4rem;
            font-size: 1.05rem;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.2s ease;
        }

        .blank-admin-back:hover {
            border-color: rgba(255,255,255,0.5);
            background: rgba(255,255,255,0.02);
            color: #fff;
            text-decoration: none;
        }

        .blank-admin-back i {
            font-size: 1rem;
        }

        .blank-admin-content {
            min-height: calc(100vh - 140px);
            max-width: 760px;
            margin: 2rem auto 0;
        }

        .employee-account-list {
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.14);
            border-radius: 0.8rem;
            background: #23262d;
        }

        .employee-account-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.8rem 1rem;
        }

        .employee-account-row + .employee-account-row {
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .employee-list-item + .employee-list-item {
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .employee-avatar-initial {
            display: grid;
            width: 2.4rem;
            height: 2.4rem;
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
            color: #f5f5f6;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .employee-account-meta {
            color: var(--muted);
            font-size: 0.75rem;
        }

        .employee-account-status {
            width: 0.55rem;
            height: 0.55rem;
            border-radius: 50%;
            background: #43d59a;
        }

        .employee-edit-details {
            padding: 0 1rem 0.75rem;
        }

        .employee-edit-details summary {
            width: fit-content;
            color: var(--gold);
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 700;
            list-style: none;
        }

        .employee-edit-details summary::-webkit-details-marker {
            display: none;
        }

        .employee-edit-form {
            display: grid;
            gap: 0.75rem;
            margin-top: 0.75rem;
            padding: 1rem;
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 0.65rem;
            background: #1d1f24;
        }

        .employee-create-form {
            padding: 1rem;
            border: 1px solid rgba(255,255,255,0.14);
            border-radius: 0.8rem;
            background: #23262d;
        }

        .employee-create-form .form-label {
            color: #d2d2d5;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .employee-create-form .form-control,
        .employee-create-form .form-select {
            border-color: rgba(255,255,255,0.16);
            background-color: #1d1f24;
            color: #f4f4f4;
        }

        .employee-add-toggle {
            width: 100%;
            margin: 1rem 0;
            padding: 0.75rem 1rem;
            border: 1px solid rgba(243,198,93,0.45);
            border-radius: 0.7rem;
            background: transparent;
            color: var(--gold);
            font-weight: 700;
        }

        .employee-submit {
            border: 0;
            background: var(--gold);
            color: #1d1f24;
            font-weight: 800;
        }

        @media (max-width: 767.98px) {
            .blank-admin-page {
                padding: 1rem 1rem 2rem;
            }

            .blank-admin-header {
                flex-direction: column;
                align-items: flex-start;
                justify-content: center;
                min-height: auto;
                padding-top: 0.25rem;
            }

            .blank-admin-back {
                align-self: flex-end;
            }
        }
        .password-toggle-btn {
            border-color: rgba(255,255,255,0.16);
            background-color: #1d1f24;
            color: var(--muted);
        }
        .password-toggle-btn:hover, .password-toggle-btn:focus {
            color: var(--text);
            background-color: #23262d;
            border-color: rgba(255,255,255,0.3);
        }
    </style>
</head>
<body>
    <div class="blank-admin-page">
        <header class="blank-admin-header">
            <div>
                <div class="blank-admin-label">Admin</div>
                <h1 class="blank-admin-title">Kelola Karyawan</h1>
            </div>

            <a href="{{ route('admin.dashboard') }}" class="blank-admin-back">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </header>

        <main class="blank-admin-content">
            @if (session('success'))
                <div class="alert alert-success" role="status">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="employee-account-list" id="employeeAccountList" aria-label="Daftar karyawan aktif">
                @forelse ($employees as $employee)
                    <div class="employee-list-item">
                        <div class="employee-account-row">
                            <span class="employee-avatar-initial" aria-hidden="true">{{ strtoupper(substr($employee->name, 0, 1)) }}</span>
                            <div class="employee-account-details">
                                <div class="employee-account-name">{{ $employee->name }}</div>
                                <div class="employee-account-meta">{{ $employee->position }}</div>
                                <div class="employee-account-meta">{{ $employee->user?->email ?? 'Profil staff' }}</div>
                            </div>
                            <span class="employee-account-status" role="img" aria-label="Aktif"></span>
                        </div>
                        <details class="employee-edit-details">
                            <summary><i class="fa-solid fa-pen-to-square me-1" aria-hidden="true"></i>Edit</summary>
                            <form class="employee-edit-form" method="POST" action="{{ route('admin.karyawan.update', $employee) }}">
                                @csrf
                                @method('PUT')
                                <div>
                                    <label for="employeeName{{ $employee->id }}" class="form-label">Nama Lengkap</label>
                                    <input type="text" id="employeeName{{ $employee->id }}" name="name" value="{{ $employee->name }}" required maxlength="100" class="form-control">
                                </div>
                                <div>
                                    <label for="employeePosition{{ $employee->id }}" class="form-label">Peran / Posisi</label>
                                    <select id="employeePosition{{ $employee->id }}" name="position" class="form-select" required>
                                        @foreach ($positions as $position)
                                            <option value="{{ $position }}" @selected($employee->position === $position)>{{ $position }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="employeePassword{{ $employee->id }}" class="form-label mt-2">Password Baru (Opsional)</label>
                                    <div class="input-group">
                                        <input type="password" id="employeePassword{{ $employee->id }}" name="password" minlength="8" class="form-control" placeholder="Kosongkan jika tidak diubah" autocomplete="new-password">
                                        <button class="btn password-toggle-btn" type="button" tabindex="-1" onclick="togglePasswordVisibility('employeePassword{{ $employee->id }}', this)">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label for="employeePasswordConfirm{{ $employee->id }}" class="form-label mt-2">Ulangi Password Baru</label>
                                    <div class="input-group mb-3">
                                        <input type="password" id="employeePasswordConfirm{{ $employee->id }}" name="password_confirmation" minlength="8" class="form-control" placeholder="Ulangi password baru" autocomplete="new-password">
                                        <button class="btn password-toggle-btn" type="button" tabindex="-1" onclick="togglePasswordVisibility('employeePasswordConfirm{{ $employee->id }}', this)">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="submit" class="btn employee-submit">Simpan Perubahan</button>
                            </form>
                            <form class="mt-2" method="POST" action="{{ route('admin.karyawan.destroy', $employee) }}" onsubmit="return confirm('Hapus akun dan profil {{ $employee->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="fa-solid fa-trash-can me-2" aria-hidden="true"></i>{{ $employee->user ? 'Hapus Akun Karyawan' : 'Hapus Profil Karyawan' }}
                                </button>
                            </form>
                        </details>
                    </div>
                @empty
                    <div class="employee-account-row text-muted">Belum ada data karyawan.</div>
                @endforelse
            </div>

            <button type="button" class="employee-add-toggle" id="employeeFormToggle" aria-expanded="{{ $errors->any() ? 'true' : 'false' }}" aria-controls="employeeCreateForm" onclick="toggleEmployeeForm()">
                <i class="fa-solid fa-plus me-2" aria-hidden="true"></i><span id="employeeFormToggleText">{{ $errors->any() ? 'Tutup Form' : 'Tambah Karyawan' }}</span>
            </button>

            <form class="employee-create-form" id="employeeCreateForm" method="POST" action="{{ route('admin.karyawan.store') }}" {{ $errors->any() ? '' : 'hidden' }}>
                @csrf
                <div class="mb-3">
                    <label for="newEmpName" class="form-label">Nama Lengkap</label>
                    <input type="text" id="newEmpName" name="name" value="{{ old('name') }}" required maxlength="100" class="form-control" placeholder="Contoh: Rina Andini" autocomplete="name">
                </div>
                <div class="mb-3">
                    <label for="newEmpUsername" class="form-label">Username</label>
                    <input type="text" id="newEmpUsername" name="username" value="{{ old('username') }}" required minlength="3" maxlength="50" pattern="[A-Za-z0-9._-]+" class="form-control" placeholder="Contoh: rina.andini" autocomplete="username">
                </div>
                <div class="mb-3">
                    <label for="newEmpPassword" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" id="newEmpPassword" name="password" required minlength="8" class="form-control" placeholder="Minimal 8 karakter" autocomplete="new-password">
                        <button class="btn password-toggle-btn" type="button" tabindex="-1" onclick="togglePasswordVisibility('newEmpPassword', this)">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="newEmpPasswordConfirm" class="form-label">Ulangi Password</label>
                    <div class="input-group">
                        <input type="password" id="newEmpPasswordConfirm" name="password_confirmation" required minlength="8" class="form-control" placeholder="Ulangi password" autocomplete="new-password">
                        <button class="btn password-toggle-btn" type="button" tabindex="-1" onclick="togglePasswordVisibility('newEmpPasswordConfirm', this)">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="newEmpRole" class="form-label">Peran / Posisi</label>
                    <select id="newEmpRole" name="position" class="form-select" required>
                        @foreach ($positions as $position)
                            <option value="{{ $position }}" @selected(old('position') === $position)>{{ $position }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn employee-submit w-100">
                    <i class="fa-solid fa-plus me-1" aria-hidden="true"></i>Buat Akun Karyawan
                </button>
            </form>
        </main>
    </div>

    <script>
        function toggleEmployeeForm() {
            const form = document.getElementById('employeeCreateForm');
            const toggle = document.getElementById('employeeFormToggle');
            const isExpanded = toggle.getAttribute('aria-expanded') === 'true';

            form.hidden = isExpanded;
            toggle.setAttribute('aria-expanded', String(!isExpanded));
            document.getElementById('employeeFormToggleText').textContent = isExpanded ? 'Tambah Karyawan' : 'Tutup Form';
        }

        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
