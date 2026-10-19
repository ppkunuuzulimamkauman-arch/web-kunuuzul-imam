<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Services\Turnstile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AlumniController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q'));
        $angkatan = $request->query('angkatan');

        $alumnis = Alumni::tampil()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nama', 'like', "%{$q}%")
                        ->orWhere('lembaga', 'like', "%{$q}%")
                        ->orWhere('domisili', 'like', "%{$q}%")
                        ->orWhere('pekerjaan', 'like', "%{$q}%");
                });
            })
            ->when($angkatan, fn ($query) => $query->where('angkatan', $angkatan))
            ->orderByDesc('angkatan')
            ->orderBy('nama')
            ->paginate(12)
            ->withQueryString();

        $angkatans = Alumni::tampil()
            ->whereNotNull('angkatan')
            ->distinct()
            ->orderByDesc('angkatan')
            ->pluck('angkatan');

        return view('alumni.index', compact('alumnis', 'angkatans', 'q', 'angkatan'));
    }

    public function show(Alumni $alumni): View
    {
        abort_unless($alumni->isPubliclyVisible(), 404);

        return view('alumni.show', compact('alumni'));
    }

    public function registerForm(): View
    {
        return view('alumni.register');
    }

    public function registerStore(Request $request): RedirectResponse
    {
        // Honeypot: bot yang mengisi field tersembunyi langsung ditolak halus.
        if ($request->filled('website')) {
            return redirect()->route('alumni.register.success');
        }

        $data = $request->validate([
            'nama' => 'required|string|max:150',
            'angkatan' => 'nullable|integer|min:1900|max:2100',
            'tahun_lulus' => 'nullable|integer|min:1900|max:2100',
            'lembaga' => 'nullable|string|max:150',
            'domisili' => 'nullable|string|max:150',
            'pekerjaan' => 'nullable|string|max:150',
            'no_hp' => 'required|string|max:20|regex:/^[0-9+\-\s]{9,20}$/',
            'email' => 'nullable|email|max:150',
            'tentang' => 'nullable|string|max:2000',
            'foto' => 'nullable|image|max:2048',
        ], [
            'no_hp.regex' => 'Nomor HP tidak valid (9-20 karakter, angka / + / - / spasi).',
        ]);

        if (! Turnstile::verify($request->input('cf-turnstile-response'), $request->ip())) {
            return back()
                ->withErrors(['captcha' => 'Verifikasi keamanan gagal. Silakan coba lagi.'])
                ->withInput();
        }

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('alumni', 'public');
        }

        $data['status'] = Alumni::STATUS_PENDING;
        $data['is_tampil'] = false;

        $alumni = Alumni::create($data);

        // Notifikasi email ke admin (best-effort, gagal kirim tidak menggagalkan pendaftaran).
        try {
            $adminEmail = \App\Models\Setting::get('kontak_email');
            if ($adminEmail && filter_var($adminEmail, FILTER_VALIDATE_EMAIL) && ! str_ends_with($adminEmail, '@example.com')) {
                \Illuminate\Support\Facades\Mail::raw(
                    "Pendaftaran alumni baru: {$alumni->nama} (angkatan {$alumni->angkatan}). Cek panel admin untuk verifikasi.",
                    function ($m) use ($adminEmail) {
                        $m->to($adminEmail)->subject('Pendaftaran alumni baru');
                    }
                );
            }
        } catch (\Throwable $e) {
        }

        return redirect()->route('alumni.register.success');
    }

    public function registerSuccess(): View
    {
        return view('alumni.register-success');
    }

    public function stats(): View
    {
        $total = Alumni::tampil()->count();

        $perAngkatan = Alumni::tampil()
            ->whereNotNull('angkatan')
            ->select('angkatan', DB::raw('count(*) as total'))
            ->groupBy('angkatan')
            ->orderByDesc('angkatan')
            ->get();

        $perLembaga = Alumni::tampil()
            ->whereNotNull('lembaga')
            ->where('lembaga', '!=', '')
            ->select('lembaga', DB::raw('count(*) as total'))
            ->groupBy('lembaga')
            ->orderByDesc('total')
            ->orderBy('lembaga')
            ->limit(8)
            ->get();

        $perDomisili = Alumni::tampil()
            ->whereNotNull('domisili')
            ->where('domisili', '!=', '')
            ->select('domisili', DB::raw('count(*) as total'))
            ->groupBy('domisili')
            ->orderByDesc('total')
            ->orderBy('domisili')
            ->limit(8)
            ->get();

        $jumlahAngkatan = $perAngkatan->count();
        $jumlahLembaga = Alumni::tampil()
            ->whereNotNull('lembaga')
            ->where('lembaga', '!=', '')
            ->distinct()
            ->count('lembaga');
        $jumlahDomisili = Alumni::tampil()
            ->whereNotNull('domisili')
            ->where('domisili', '!=', '')
            ->distinct()
            ->count('domisili');
        $maxAngkatan = (int) ($perAngkatan->max('total') ?? 0);

        return view('alumni.stats', compact(
            'total',
            'perAngkatan',
            'perLembaga',
            'perDomisili',
            'jumlahAngkatan',
            'jumlahLembaga',
            'jumlahDomisili',
            'maxAngkatan'
        ));
    }
}
