<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * Kunci otomatis panel admin setelah 15 menit tanpa aktivitas.
 * Setiap request admin yang lolos memperbarui penanda; kalau diam
 * lebih dari batas, sesi dihapus (termasuk remember-me) dan admin
 * dilempar ke halaman login.
 */
class AdminIdleTimeout
{
    public const TIMEOUT_MINUTES = 15;

    public const SESSION_KEY = 'admin_last_activity';

    public function handle(Request $request, Closure $next): Response
    {
        $last = $request->session()->get(self::SESSION_KEY);

        if ($last && now()->timestamp - (int) $last > self::TIMEOUT_MINUTES * 60) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Sesi berakhir karena '.self::TIMEOUT_MINUTES.' menit tidak ada aktivitas. Silakan login lagi.'])
                ->withCookie(Cookie::forget(auth()->getRecallerName()));
        }

        // Permintaan ping (polling chat / badge) tidak dihitung sebagai aktivitas,
        // agar kunci otomatis 5 menit tetap berjalan.
        if (! $request->header('X-Ping')) {
            $request->session()->put(self::SESSION_KEY, now()->timestamp);
        }

        return $next($request);
    }
}