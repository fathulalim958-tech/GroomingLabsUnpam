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
        $mailSent = false;
        $mailError = null;

        $htmlContent = $this->buildEmailHtml(
            name: $user->name,
            code: $code,
            isResend: false
        );

        try {
            Mail::html($htmlContent, function ($message) use ($user, $code) {
                $message->to($user->email)
                    ->subject("{$code} adalah Kode Verifikasi Reset Password Anda - Grooming Lab");
            });
            $mailSent = true;
        } catch (\Throwable $e) {
            $mailError = $e->getMessage();
            logger()->error('Gagal mengirim email reset password: ' . $e->getMessage());
        }

        session([
            'password_reset_email' => $request->email,
            'mail_sent' => $mailSent,
            'mail_error' => $mailError,
        ]);

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

        $mailSent = false;
        $mailError = null;

        $htmlContent = $this->buildEmailHtml(
            name: $user->name,
            code: $code,
            isResend: true
        );

        try {
            Mail::html($htmlContent, function ($message) use ($user, $code) {
                $message->to($user->email)
                    ->subject("{$code} adalah Kode Verifikasi Baru Anda - Grooming Lab");
            });
            $mailSent = true;
        } catch (\Throwable $e) {
            $mailError = $e->getMessage();
            logger()->error('Gagal mengirim ulang email reset password: ' . $e->getMessage());
        }

        session([
            'mail_sent' => $mailSent,
            'mail_error' => $mailError,
        ]);

        return back()->with('status', 'Kode verifikasi baru telah dikirim ke email Anda.');
    }

    /**
     * Generate HTML email template for password verification code.
     */
    protected function buildEmailHtml(string $name, string $code, bool $isResend = false): string
    {
        $headline = $isResend ? 'Kode Verifikasi Baru' : 'Kode Verifikasi Anda';
        $subtext = $isResend ? 'Berikut adalah kode verifikasi terbaru Anda untuk mereset kata sandi:' : 'Kami menerima permintaan untuk mereset kata sandi akun Grooming Lab Anda. Gunakan kode verifikasi di bawah ini:';

        return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi Reset Password</title>
</head>
<body style="margin: 0; padding: 24px; background-color: #0b1120; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #f8fafc;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 520px; background-color: #1e293b; border-radius: 20px; border: 1px solid #334155; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 32px 24px 20px 24px; background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%); border-bottom: 1px solid #334155;">
                            <div style="display: inline-block; width: 52px; height: 52px; line-height: 52px; border-radius: 16px; background-color: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.3); font-size: 26px; text-align: center; margin-bottom: 12px;">
                                💈
                            </div>
                            <h1 style="margin: 0; font-size: 22px; font-weight: 900; color: #ffffff; letter-spacing: -0.5px;">GROOMING LAB</h1>
                            <p style="margin: 4px 0 0 0; font-size: 11px; font-weight: 700; color: #f59e0b; text-transform: uppercase; letter-spacing: 2px;">Barbershop &amp; MUA Studio</p>
                        </td>
                    </tr>
                    
                    <!-- Content Body -->
                    <tr>
                        <td style="padding: 32px 28px;">
                            <h2 style="margin: 0 0 12px 0; font-size: 18px; font-weight: 800; color: #ffffff; text-align: center;">{$headline}</h2>
                            <p style="margin: 0 0 24px 0; font-size: 13px; line-height: 1.6; color: #94a3b8; text-align: center;">
                                Halo <strong style="color: #f1f5f9;">{$name}</strong>,<br>
                                {$subtext}
                            </p>

                            <!-- Verification Code Box -->
                            <div style="background-color: #090e1a; border-radius: 16px; border: 2px dashed #f59e0b; padding: 24px; text-align: center; margin: 0 0 24px 0;">
                                <div style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 8px;">6 Digit Kode OTP</div>
                                <div style="font-family: 'Courier New', Courier, monospace; font-size: 40px; font-weight: 900; letter-spacing: 10px; color: #fbbf24; text-shadow: 0 0 20px rgba(245, 158, 11, 0.4);">
                                    {$code}
                                </div>
                                <div style="margin-top: 10px; font-size: 11px; color: #64748b;">
                                    ⏱️ Kode ini berlaku selama <strong>15 menit</strong>.
                                </div>
                            </div>

                            <p style="margin: 0 0 8px 0; font-size: 12px; line-height: 1.6; color: #cbd5e1;">
                                <strong>Penting:</strong> Jangan berikan kode ini kepada siapa pun termasuk pihak Grooming Lab. Jika Anda tidak pernah meminta perubahan kata sandi, akun Anda tetap aman dan abaikan pesan ini.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px 28px; background-color: #0f172a; border-top: 1px solid #334155; text-align: center;">
                            <p style="margin: 0; font-size: 11px; color: #64748b; line-height: 1.5;">
                                &copy; Grooming Lab &amp; MUA Studio. Seluruh hak cipta dilindungi.<br>
                                Email ini dibuat secara otomatis oleh sistem reservasi.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
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

