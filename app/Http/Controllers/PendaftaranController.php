<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Program;
use App\Services\Turnstile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PendaftaranController extends Controller
{
    public function create(): View
    {
        $programs = Program::where('is_aktif', true)->orderBy('nama')->get();
        return view('ppdb-form', compact('programs'));
    }

    public function store(Request $request): RedirectResponse
    {
        // Honeypot: bot yang mengisi field tersembunyi langsung ditolak halus
        if ($request->filled('website')) {
            return redirect()->route('ppdb.form')->with('sukses', 'Pendaftaran berhasil dikirim. Jazakumullah khairan.');
        }

        $fileRules = 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:4096';
        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'nik' => 'required|string|max:20',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'nama_ayah' => 'required|string|max:100',
            'pekerjaan_ayah' => 'required|string|max:100',
            'nama_ibu' => 'required|string|max:100',
            'pekerjaan_ibu' => 'required|string|max:100',
            'no_hp' => 'required|string|max:20',
            'jenjang' => 'required|string|max:50',
            'asal_sekolah' => 'required|string|max:100',
            'nama_wali' => 'nullable|string|max:100',
            'hp_wali' => 'nullable|string|max:20',
            'program_pilihan' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
            'tanggal_pengisian' => 'required|date',
            'file_ijazah' => $fileRules,
            'file_kk' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:4096',
            'file_kip' => $fileRules,
        ]);

        if (! Turnstile::verify($request->input('cf-turnstile-response'), $request->ip())) {
            return back()->withErrors(['captcha' => 'Verifikasi keamanan gagal. Silakan selesaikan verifikasi lalu coba lagi.'])->withInput();
        }

        foreach (['file_ijazah', 'file_kk', 'file_kip'] as $f) {
            if ($request->hasFile($f)) {
                $data[$f] = $request->file($f)->store('ppdb', 'public');
            }
        }

        // Kolom lama tetap terisi (fallback) agar kompatibel dengan data lama
        $data['nama_wali'] = $data['nama_wali'] ?? $data['nama_ayah'];
        $data['hp_wali'] = $data['hp_wali'] ?? $data['no_hp'];

        // Nomor unik anti-tabrakan (aman dari submit bersamaan, tidak mengandalkan count+1)
        $tahun = date('Y');
        do {
            $data['nomor_pendaftaran'] = 'PPDB-' . $tahun . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Pendaftaran::where('nomor_pendaftaran', $data['nomor_pendaftaran'])->exists());

        $pendaftaran = Pendaftaran::create($data);

        return redirect()->route('ppdb.sukses', $pendaftaran->nomor_pendaftaran);
    }

    public function sukses(string $nomor): View
    {
        $pendaftaran = Pendaftaran::where('nomor_pendaftaran', $nomor)->firstOrFail();
        return view('ppdb-sukses', compact('pendaftaran'));
    }

    public function galeri(): View
    {
        $galeris = \App\Models\Galeri::latest()->paginate(12);
        return view('galeri-index', compact('galeris'));
    }
}
