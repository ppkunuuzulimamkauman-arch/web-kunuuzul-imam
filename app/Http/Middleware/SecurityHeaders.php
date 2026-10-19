<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        // CSP: batasi sumber skrip/gambar/font ke domain sendiri + CDN yang dipakai.
        // 'unsafe-inline' masih diperlukan karena banyak inline <script> di view;
        // namun domain eksternal tak dikenal tetap diblokir (mitigasi XSS injeksi).
        $cspParts = [
            "default-src 'self'",
            "base-uri 'self'",
            "object-src 'none'",
            "form-action 'self'",
            "frame-ancestors 'self'",
            "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://esm.sh https://challenges.cloudflare.com https://static.cloudflareinsights.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com",
            "font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com",
            "img-src 'self' data: blob: https:",
            "media-src 'self' https:",
            "connect-src 'self' ws: wss: https://challenges.cloudflare.com https://cloudflareinsights.com",
            "frame-src 'self' https://challenges.cloudflare.com https://maps.google.com https://www.google.com",
        ];
        // Hanya paksa HTTPS di koneksi aman; jangan di localhost (http).
        if ($request->isSecure()) {
            $cspParts[] = 'upgrade-insecure-requests';
        }
        $response->headers->set('Content-Security-Policy', implode('; ', $cspParts));

        // HSTS hanya saat HTTPS agar localhost tidak terkunci
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
