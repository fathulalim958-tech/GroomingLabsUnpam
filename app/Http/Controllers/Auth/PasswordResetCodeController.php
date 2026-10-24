<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PasswordResetCodeController extends Controller
{
    /**
     * Display the forgot password request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Generate and send a 6-digit verification code to the customer's email.
     */
    public function sendCode(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email ini belum terdaftar di sistem kami.',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && ! $user->is_active) {
            return back()->withInput()->withErrors([
                'email' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi admin.',
            ]);
        }

        // Generate 6-digit verification code
        $code = (string) random_int(100000, 999999);

        // Store hashed code in password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($code),
                'created_at' => now(),
            ]
        );

        // Send email with the verification code
        try {
            Mail::raw(
                "Halo {$user->name},\n\n" .
                "Kami menerima permintaan untuk mereset kata sandi akun Grooming Lab Anda.\n\n" .
                "Berikut adalah KODE VERIFIKASI Anda:\n\n" .
                "    >>> {$code} <<<\n\n" .
                "Kode verifikasi ini berlaku selama 15 menit.\n" .
                "Jika Anda tidak pernah meminta perubahan kata sandi ini, silakan abaikan email ini dengan aman.\n\n" .
                "Salam hangat,\n" .
                "Tim Grooming Lab & MUA Studio",
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject("{$code} adalah Kode Verifikasi Reset Password Anda - Grooming Lab");
                }
            );
        } catch (\Throwable $e) {
            logger()->error('Gagal mengirim email reset password: ' . $e->getMessage());
        }

        session(['password_reset_email' => $request->email]);

        return redirect()->route('password.verify.form')
            ->with('status', 'Kode verifikasi telah dikirim ke email Anda. Silakan cek kotak masuk atau folder spam.');
    }

    /**
     * Display the view to enter the 6-digit verification code.
     */
    public function showVerifyForm(): View|RedirectResponse
    {
        $email = session('password_reset_email');

        if (! $email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Silakan masukkan email Anda terlebih dahulu.']);
        }

        return view('auth.verify-code', compact('email'));
    }

    /**
     * Verify the 6-digit code entered by user.
     */
    public function verifyCode(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ], [
            'code.required' => 'Kode verifikasi wajib diisi.',
            'code.size' => 'Kode verifikasi harus terdiri dari 6 digit.',
        ]);

        $email = session('password_reset_email');

        if (! $email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi telah kadaluarsa. Silakan masukkan email kembali.']);
        }

        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (! $record) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Permintaan verifikasi tidak ditemukan. Silakan kirim ulang kode.']);
        }

        // Check if code has expired (15 minutes)
        if (Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return back()->withErrors(['code' => 'Kode verifikasi sudah kadaluarsa (lebih dari 15 menit). Silakan kirim ulang kode baru.']);
        }

        // Check code match
        if (! Hash::check($request->code, $record->token)) {
            return back()->withInput()->withErrors(['code' => 'Kode verifikasi tidak sesuai. Silakan periksa kembali email Anda.']);
        }

        // Mark as verified in session
        $token = Str::random(40);
        session([
            'password_reset_verified' => true,
            'password_reset_token' => $token,
        ]);

        return redirect()->route('password.reset.form')
            ->with('status', 'Kode verifikasi benar. Silakan masukkan kata sandi baru Anda.');
    }

    /**
     * Resend the verification code.
     */
    public function resendCode(): RedirectResponse
    {
        $email = session('password_reset_email');

        if (! $email) {
            return redirect()->route('password.request');
        }

        $user = User::where('email', $email)->first();
        if (! $user) {
            return redirect()->route('password.request');
        }

        $code = (string) random_int(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($code),
                'created_at' => now(),
            ]
        );

        try {
            Mail::raw(
                "Halo {$user->name},\n\n" .
                "Berikut adalah KODE VERIFIKASI BARU Anda:\n\n" .
                "    >>> {$code} <<<\n\n" .
                "Kode berlaku selama 15 menit.\n\n" .
                "Salam hangat,\n" .
                "Tim Grooming Lab & MUA Studio",
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject("{$code} adalah Kode Verifikasi Baru Anda - Grooming Lab");
                }
            );
        } catch (\Throwable $e) {
            logger()->error('Gagal mengirim ulang email reset password: ' . $e->getMessage());
        }

        return back()->with('status', 'Kode verifikasi baru telah dikirim ke email Anda.');
    }

    /**
     * Display the view to create a new password.
     */
    public function showResetForm(): View|RedirectResponse
    {
        if (! session('password_reset_verified') || ! session('password_reset_email')) {
            return redirect()->route('password.request');
        }

        $email = session('password_reset_email');

        return view('auth.reset-password', compact('email'));
    }

    /**
     * Store the new password.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        if (! session('password_reset_verified') || ! session('password_reset_email')) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min' => 'Kata sandi minimal terdiri dari 8 karakter.',
        ]);

        $email = session('password_reset_email');
        $user = User::where('email', $email)->firstOrFail();

        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        // Clean up reset token and session
        DB::table('password_reset_tokens')->where('email', $email)->delete();
        session()->forget(['password_reset_email', 'password_reset_verified', 'password_reset_token']);

        return redirect()->route('login')
            ->with('status', 'Kata sandi berhasil diubah! Silakan masuk menggunakan kata sandi baru Anda.');
    }
}

