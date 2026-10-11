<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SesiController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function index(): View|RedirectResponse
    {
        // Jika pengguna SUDAH login, arahkan ke dashboard sesuai role
        if (Auth::check()) {
            $role = Auth::user()->role;

            return match ($role) {
                'admin' => redirect('dashboard/admin'),
                'kasir' => redirect('dashboard/kasir'),
                'pelanggan' => redirect('dashboard/pelanggan'),
                default => redirect('/'),
            };
        }

        // Jika BELUM login, tampilkan form login
        return view('Login');
    }

    /**
     * Memproses otentikasi pengguna.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $loginField = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$loginField => $credentials['email'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            $roleRedirects = [
                'admin' => 'dashboard/admin',
                'kasir' => 'dashboard/kasir',
                'pelanggan' => 'dashboard/pelanggan',
            ];

            $userRole = Auth::user()->role;

            if (array_key_exists($userRole, $roleRedirects)) {
                return redirect()->intended($roleRedirects[$userRole]);
            }

            Auth::logout();

            return back()->withErrors(['email' => 'Akses peran tidak valid.']);
        }

        return back()
            ->withErrors(['email' => 'Email atau password yang Anda masukkan salah.'])
            ->withInput($request->only('email'));
    }

    // Memproses logout pengguna.
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // Memproses Register
    public function Register(): View
    {
        return view('Register');
    }

    // Memproses Pendaftaran Akun Baru
    // Memproses Pendaftaran Akun Baru
    public function createAccount(Request $request)
    {
        // 1. Validasi Input dari Form
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'accepted',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar, silakan gunakan email lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'terms.accepted' => 'Anda harus menyetujui Syarat & Ketentuan.',
        ]);

        // 2. Format Nomor Telepon
        // Hapus karakter non-angka/spasi terlebih dahulu
        $rawPhone = preg_replace('/[^0-9]/', '', $request->phone);

        if (str_starts_with($rawPhone, '62')) {
            $phoneFormatted = '+'.$rawPhone;
        } elseif (str_starts_with($rawPhone, '0')) {
            $phoneFormatted = '+62'.substr($rawPhone, 1);
        } else {
            $phoneFormatted = '+62'.$rawPhone;
        }

        // 3. Simpan User Baru ke Database
        $user = User::create([
            'name' => $request->name,
            'phone' => $phoneFormatted, // <-- GUNAKAN $phoneFormatted DI SINI (sebelumnya $request->phone)
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pelanggan',
        ]);

        // 4. Redirect ke Halaman Login dengan Pesan Sukses
        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan login untuk melanjutkan.');
    }
}
