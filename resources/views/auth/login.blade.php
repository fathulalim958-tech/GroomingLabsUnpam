@extends('layouts.app')
@section('content')
<div class="login-shell">
    <button class="theme-toggle" type="button" data-theme-toggle aria-label="Aktifkan mode terang"><span data-theme-icon aria-hidden="true">☼</span></button>
    <section class="login-visual">
        <div class="login-visual-image"></div>
        <p class="login-kicker">PREMIUM SINCE 2019</p>
        <div class="login-visual-copy"><strong>Ruang Rias</strong><span>Barber &amp; MUA studio</span></div>
    </section>
    <form class="login-form" method="POST" action="{{ route('login.store') }}">
        @csrf
        <div class="login-heading"><p class="login-eyebrow">RUANG RIAS</p><h1>Selamat Datang</h1><p>Masuk sebagai siapa?</p></div>
        <div class="login-roles" aria-label="Jenis akun"><button class="login-role is-active" type="button" data-login-role="customer"><span aria-hidden="true">♟</span> Pelanggan</button><button class="login-role" type="button" data-login-role="staff"><span aria-hidden="true">▣</span> Kasir / Karyawan</button></div>
        <div class="login-staff-note" data-staff-note hidden><span aria-hidden="true">▣</span><p>Masuk sebagai <strong>Kasir / Karyawan</strong> untuk mengelola booking pelanggan dan mencatat transaksi walk-in.</p></div>
        <label class="login-field"><span data-login-label>NO. TELEPON / EMAIL</span><input id="login-email" type="text" name="login" value="{{ old('login', old('email')) }}" placeholder="pelanggan@ruangrias.test" required autofocus></label>
        <label class="login-field"><span>PASSWORD</span><span class="password-wrap"><input type="password" name="password" placeholder="••••••••" required><span class="password-icon" aria-hidden="true">◉</span></span></label>
        <div class="login-options"><label class="login-remember"><input type="checkbox" name="remember" value="1"> Ingat saya</label><span>Lupa password?</span></div>
        <button class="button login-submit" type="submit" data-login-submit>Masuk</button>
        <div class="login-divider login-customer-only"><span>atau</span></div>
        <a class="login-register login-customer-only" href="{{ route('register') }}">Daftar Akun Baru</a>
        <p class="login-terms">Dengan masuk, kamu menyetujui <a href="#">Syarat &amp; Ketentuan</a></p>
    </form>
</div>
@endsection
