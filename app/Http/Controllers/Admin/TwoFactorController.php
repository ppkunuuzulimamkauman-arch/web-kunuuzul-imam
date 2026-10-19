<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Totp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    /** Halaman pengaturan 2FA untuk akun yang sedang login. */
    public function show(Request $request): View
    {
        $user = $request->user();

        // Secret sementara disimpan di session sampai kode dikonfirmasi.
        if (! $user->hasTwoFactor() && ! $request->session()->has('2fa.setup_secret')) {
            $request->session()->put('2fa.setup_secret', Totp::generateSecret());
        }

        $secret = $user->hasTwoFactor()
            ? $user->two_factor_secret
            : $request->session()->get('2fa.setup_secret');

        $issuer = config('app.name', 'Pondok');
        $uri = Totp::provisioningUri($secret, $issuer.':'.$user->email, $issuer);

        return view('admin.two-factor-setup', [
            'enabled' => $user->hasTwoFactor(),
            'secret' => $secret,
            'uri' => $uri,
            'recoveryCodes' => $user->hasTwoFactor() ? $user->recoveryCodes() : [],
        ]);
    }

    /** Aktifkan 2FA setelah pengguna memindai QR & memasukkan kode valid. */
    public function enable(Request $request): RedirectResponse
    {
        $request->validate(['code' => 'required|string|max:10']);

        $user = $request->user();
        $secret = $request->session()->get('2fa.setup_secret');

        if (! $secret || ! Totp::verify($secret, (string) $request->input('code'))) {
            return back()->withErrors(['code' => 'Kode salah. Pastikan jam perangkat akurat lalu coba lagi.']);
        }

        $codes = $this->generateRecoveryCodes();

        $user->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => encrypt($codes),
        ])->save();

        $request->session()->forget('2fa.setup_secret');

        return redirect()->route('admin.two-factor.show')
            ->with('sukses', '2FA aktif. Simpan kode pemulihan di tempat aman.')
            ->with('recovery_codes_plain', $codes);
    }

    /** Matikan 2FA (butuh password saat ini). */
    public function disable(Request $request): RedirectResponse
    {
        $request->validate(['password' => 'required|string']);

        $user = $request->user();

        if (! \Illuminate\Support\Facades\Hash::check((string) $request->input('password'), $user->password)) {
            return back()->withErrors(['password' => 'Password salah.']);
        }

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_confirmed_at' => null,
            'two_factor_recovery_codes' => null,
        ])->save();

        $request->session()->forget('2fa.setup_secret');

        return redirect()->route('admin.two-factor.show')->with('sukses', '2FA dinonaktifkan.');
    }

    /** Buat ulang kode pemulihan (butuh password saat ini). */
    public function regenerateRecoveryCodes(Request $request): RedirectResponse
    {
        $request->validate(['password' => 'required|string']);

        $user = $request->user();

        if (! \Illuminate\Support\Facades\Hash::check((string) $request->input('password'), $user->password)) {
            return back()->withErrors(['password' => 'Password salah.']);
        }

        if (! $user->hasTwoFactor()) {
            return back()->withErrors(['password' => 'Aktifkan 2FA terlebih dahulu.']);
        }

        $codes = $this->generateRecoveryCodes();
        $user->forceFill(['two_factor_recovery_codes' => encrypt($codes)])->save();

        return redirect()->route('admin.two-factor.show')
            ->with('sukses', 'Kode pemulihan baru dibuat. Kode lama tidak berlaku lagi.')
            ->with('recovery_codes_plain', $codes);
    }

    /** 8 kode pemulihan acak format XXXX-XXXX. */
    protected function generateRecoveryCodes(): array
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(bin2hex(random_bytes(2))).'-'.strtoupper(bin2hex(random_bytes(2)));
        }

        return $codes;
    }
}
