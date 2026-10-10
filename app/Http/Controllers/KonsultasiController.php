<?php

namespace App\Http\Controllers;

use App\Models\Pesan;
use App\Services\Turnstile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KonsultasiController extends Controller
{
    public static function kategoris(): array
    {
        return [
            'pendidikan' => 'Pendidikan & Pembelajaran',
            'tahfidz' => 'Tahfidz & Tahsin',
            'ppdb' => 'PPDB & Pendaftaran',
            'keluarga' => 'Keluarga & Parenting',
            'lainnya' => 'Lainnya',
        ];
    }

    public function index(): View
    {
        return view('konsultasi', ['kategoris' => self::kategoris()]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Honeypot: bot yang mengisi field tersembunyi langsung ditolak halus
        if ($request->filled('website')) {
            return back()->with('sukses', 'Pertanyaan konsultasi Anda berhasil dikirim. Jazakumullah khairan.');
        }

        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'no_hp' => 'required|string|max:20',
            'kategori' => 'required|string|max:50',
            'pesan' => 'required|string|max:2000',
        ]);

        if (! Turnstile::verify($request->input('cf-turnstile-response'), $request->ip())) {
            return back()->withErrors(['captcha' => 'Verifikasi keamanan gagal. Silakan selesaikan verifikasi lalu coba lagi.'])->withInput();
        }

        $label = self::kategoris()[$data['kategori']] ?? $data['kategori'];

        Pesan::create([
            'nama' => $data['nama'],
            'no_hp' => $data['no_hp'],
            'subjek' => '[Konsultasi] '.$label,
            'pesan' => $data['pesan'],
        ]);

        return back()->with('sukses', 'Pertanyaan konsultasi Anda berhasil dikirim. Jazakumullah khairan.');
    }
}
