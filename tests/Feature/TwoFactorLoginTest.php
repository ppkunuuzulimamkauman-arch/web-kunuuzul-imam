<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Totp;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TwoFactorLoginTest extends TestCase
{
    use DatabaseTransactions;

    protected function makeUser(array $extra = []): User
    {
        return User::create(array_merge([
            'name' => 'Admin 2FA',
            'email' => 'admin2fa-'.uniqid().'@example.test',
            'password' => 'password123',
            'role' => 'superadmin',
            'is_active' => true,
        ], $extra));
    }

    public function test_login_dengan_2fa_belum_menandatangani_sebelum_kode_benar(): void
    {
        $secret = Totp::generateSecret();
        $user = $this->makeUser();
        $user->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => now(),
        ])->save();

        $res = $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $res->assertRedirect(route('admin.two-factor.challenge'));
        $this->assertGuest();
    }

    public function test_kode_totp_benar_menyelesaikan_login(): void
    {
        $secret = Totp::generateSecret();
        $user = $this->makeUser();
        $user->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => now(),
        ])->save();

        $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $res = $this->post(route('admin.two-factor.verify'), [
            'code' => Totp::code($secret),
        ]);

        $res->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_kode_pemulihan_hanya_bisa_dipakai_sekali(): void
    {
        $secret = Totp::generateSecret();
        $user = $this->makeUser();
        $user->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => encrypt(['AAAA-BBBB', 'CCCC-DDDD']),
        ])->save();

        $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);
        $this->post(route('admin.two-factor.verify'), ['code' => 'AAAA-BBBB']);
        $this->assertAuthenticatedAs($user);
        $this->assertSame(['CCCC-DDDD'], $user->fresh()->recoveryCodes());
    }

    public function test_kode_salah_tidak_meloloskan_login(): void
    {
        $secret = Totp::generateSecret();
        $user = $this->makeUser();
        $user->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => now(),
        ])->save();

        $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $this->post(route('admin.two-factor.verify'), ['code' => '000000']);
        $this->assertGuest();
    }
}
