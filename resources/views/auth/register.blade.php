@extends('layouts.app')
@section('content')
<div class="register-shell">
    <button class="theme-toggle" type="button" data-theme-toggle aria-label="Aktifkan mode terang"><span data-theme-icon aria-hidden="true">☼</span></button>
    <a class="register-back" href="{{ route('login') }}"><span aria-hidden="true">←</span> Kembali</a>
    <form class="register-form" method="POST" action="{{ route('register.store') }}">
    @csrf
    <div class="register-heading"><h1>Buat Akun</h1><p>Daftar untuk pengalaman premium</p></div>
    <label class="register-field"><span>NAMA LENGKAP</span><input type="text" name="name" value="{{ old('name') }}" placeholder="Nama lengkap" required autofocus></label>
    <label class="register-field"><span>EMAIL</span><input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required></label>
    <label class="register-field"><span>PASSWORD</span><input type="password" name="password" placeholder="Min. 8 karakter" required></label>
    <label class="register-field"><span>KONFIRMASI PASSWORD</span><input type="password" name="password_confirmation" placeholder="Ulangi password" required></label>
    <label class="register-agreement"><input type="checkbox" checked required><span>Saya menyetujui <a href="#">Syarat &amp; Ketentuan</a> serta <a href="#">Kebijakan Privasi</a> Ruang Rias</span></label>
    <button class="button register-submit" type="submit">Daftar Sekarang</button>
    </form>
</div>
@endsection
