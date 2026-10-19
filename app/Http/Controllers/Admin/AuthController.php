<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminIdleTimeout;
use App\Services\Turnstile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function login(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        if (! Turnstile::verify($request->input('cf-turnstile-response'), $request->ip())) {
            return back()->withErrors(['email' => 'Verifikasi keamanan gagal. Silakan selesaikan verifikasi lalu coba lagi.'])->onlyInput('email');
        }

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::validate($credentials)) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
        }

        $user = \App\Models\User::where('email', $credentials['email'])->first();

        // Blokir akun nonaktif.
        if (! $user || ! $user->is_active) {
            return back()->withErrors(['email' => 'Akun Anda dinonaktifkan.'])->onlyInput('email');
        }

        // Hanya role pengelola web (utama & operator).
        if (! $user->isAdmin()) {
            return back()->withErrors(['email' => 'Akun Anda tidak memiliki akses admin.'])->onlyInput('email');
        }

        // Tahap 1 lolos. Bila 2FA aktif, jangan login dulu — minta kode TOTP.
        if ($user->hasTwoFactor()) {
            $request->session()->put('2fa.pending_id', $user->id);
            $request->session()->put('2fa.remember', $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->route('admin.two-factor.challenge');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        $request->session()->put('user_session_version', $user->session_version);
        $user->forceFill(['last_login_at' => now()])->save();

        return redirect()->intended(route('admin.dashboard'))
            ->with('sukses', 'Ahlan wa sahlan, '.$user->name.'!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('sukses', 'Anda telah logout dengan aman.');
    }

    /** Tampilkan form kode 2FA setelah email & password benar. */
    public function showTwoFactorChallenge(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('2fa.pending_id')) {
            return redirect()->route('login');
        }

        return view('admin.two-factor');
    }

    /** Verifikasi kode TOTP atau kode pemulihan, lalu selesaikan login. */
    public function verifyTwoFactorChallenge(Request $request): RedirectResponse
    {
        $request->validate(['code' => 'required|string|max:20']);

        $user = \App\Models\User::find($request->session()->get('2fa.pending_id'));

        if (! $user || ! $user->is_active || ! $user->isAdmin() || ! $user->hasTwoFactor()) {
            $request->session()->forget(['2fa.pending_id', '2fa.remember']);

            return redirect()->route('login')->withErrors(['email' => 'Sesi verifikasi tidak valid. Silakan login ulang.']);
        }

        $code = trim((string) $request->input('code'));
        $valid = \App\Services\Totp::verify($user->two_factor_secret, $code);

        // Kode pemulihan (sekali pakai).
        if (! $valid) {
            $codes = $user->recoveryCodes();
            $index = array_search($code, $codes, true);
            if ($index !== false) {
                unset($codes[$index]);
                $user->forceFill([
                    'two_factor_recovery_codes' => encrypt(array_values($codes)),
                ])->save();
                $valid = true;
            }
        }

        if (! $valid) {
            return back()->withErrors(['code' => 'Kode tidak valid atau sudah kedaluwarsa. Coba lagi.']);
        }

        $remember = (bool) $request->session()->pull('2fa.remember', false);
        $request->session()->forget('2fa.pending_id');

        Auth::login($user, $remember);
        $request->session()->regenerate();
        $request->session()->put('user_session_version', $user->session_version);
        $user->forceFill(['last_login_at' => now()])->save();

        return redirect()->intended(route('admin.dashboard'))
            ->with('sukses', 'Verifikasi berhasil. Ahlan wa sahlan, '.$user->name.'!');
    }

    /** Dipanggil otomatis oleh panel saat idle: akhiri sesi lalu tampilkan halaman login. */
    public function lock(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->withErrors(['email' => 'Sesi berakhir karena '.AdminIdleTimeout::TIMEOUT_MINUTES.' menit tidak ada aktivitas. Silakan login lagi.']);
    }
}
