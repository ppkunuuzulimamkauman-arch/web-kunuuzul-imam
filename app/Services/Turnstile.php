<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Verifikasi Cloudflare Turnstile.
 * Nonaktif otomatis bila key belum diisi di .env (situs tetap jalan),
 * aktifkan dengan mengisi TURNSTILE_SITE_KEY + TURNSTILE_SECRET_KEY.
 */
class Turnstile
{
    public static function enabled(): bool
    {
        return (bool) (config('services.turnstile.site_key') && config('services.turnstile.secret'));
    }

    public static function siteKey(): ?string
    {
        return config('services.turnstile.site_key');
    }

    public static function verify(?string $token, ?string $ip = null): bool
    {
        if (! static::enabled()) {
            return true;
        }
        if (! $token) {
            return false;
        }
        try {
            $res = Http::asForm()->timeout(10)->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => config('services.turnstile.secret'),
                'response' => $token,
                'remoteip' => $ip,
            ]);
            return (bool) ($res->json('success') ?? false);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
