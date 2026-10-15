<?php

namespace App\Http\Controllers;

use App\Models\Pesan;
use App\Services\Turnstile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KontakController extends Controller
{
    public function index(): View
    {
        return view('kontak');
    }

    public function store(Request $request): RedirectResponse
    {
        // Honeypot: bot yang mengisi field tersembunyi langsung ditolak halus
        if ($request->filled('website')) {
            return back()->with('sukses', 'Pesan Anda berhasil dikirim. Jazakumullah khairan, akan segera kami balas.');
        }

        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'nullable|email|max:100',
            'no_hp' => 'nullable|string|max:20',
            'subjek' => 'nullable|string|max:150',
            'pesan' => 'required|string|max:2000',
        ]);

        if (! Turnstile::verify($request->input('cf-turnstile-response'), $request->ip())) {
            return back()->withErrors(['captcha' => 'Verifikasi keamanan gagal. Silakan selesaikan verifikasi lalu coba lagi.'])->withInput();
        }

        Pesan::create($data);

        try {
            $adminEmail = \App\Models\Setting::get('kontak_email');
            if ($adminEmail && filter_var($adminEmail, FILTER_VALIDATE_EMAIL) && ! str_ends_with($adminEmail, '@example.com')) {
                \Illuminate\Support\Facades\Mail::raw(
                    "Pesan kontak dari {$data['nama']}: ".($data['subjek'] ?? '(tanpa subjek)')."\n\n".\Illuminate\Support\Str::limit($data['pesan'], 1000),
                    function ($m) use ($adminEmail) { $m->to($adminEmail)->subject('Pesan kontak baru'); }
                );
            }
        } catch (\Throwable $e) {
        }

        return back()->with('sukses', 'Pesan Anda berhasil dikirim. Jazakumullah khairan, akan segera kami balas.');
    }
}
