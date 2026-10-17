<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureSessionVersion
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user) {
            $sessionVersion = $request->session()->get('user_session_version');

            if ($sessionVersion !== null && $user->session_version !== $sessionVersion) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors('Sesi Anda telah kedaluwarsa karena admin utama mereset semua sesi. Silakan login kembali.');
            }
        }

        return $next($request);
    }
}
