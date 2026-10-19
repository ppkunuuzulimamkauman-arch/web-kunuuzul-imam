<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Subdomain fungsional (modular monolith: 1 app, 1 database).
            // Didaftarkan SEBELUM route utama agar subdomain diprioritaskan.
            $domain = config('pondok.domain');
            foreach (config('pondok.subdomains', []) as $sub => $file) {
                $path = base_path("routes/subs/{$file}.php");
                if (! is_file($path)) {
                    continue;
                }
                Route::domain("{$sub}.{$domain}")
                    ->middleware('web')
                    ->name("{$sub}.")
                    ->group($path);
            }

            // Domain utama (fallback terakhir).
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
