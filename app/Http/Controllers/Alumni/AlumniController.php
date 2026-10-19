<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Services\Turnstile;
use App\Support\Nik;
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

        return view('alumni.index', [
            'alumnis' => $alumnis,
            'angkatans' => $angkatans,
            'q' => $q,
            'angkatan' => $angkatan,
            'totalAlumni' => Alumni::tampil()->count(),
        ]);
    }

    public function show(Alumni $alumni): View
    {
        abort_unless($alumni->isPubliclyVisible(), 404);

        return view('alumni.show', compact('alumni'));
    }

    public function registerForm(): View
    {
        return view('alumni.register', [
            'totalAlumni' => Alumni::tampil()->count(),
        ]);
    }

    public function checkNik(Request $request): RedirectResponse
    {
        $request->merge([
            'nik' => preg_replace('/\D+/', '', (string) $request->input('nik')),
        ]);

        $request->validate([
            'nik' => ['required', 'string', 'size:16', 'regex:/^\d{16}$/'],
        ], [
            'nik.required' => 'Masukkan NIK terlebih dahulu.',
            'nik.size' => 'NIK harus 16 digit angka.',
            'nik.regex' => 'NIK hanya boleh berisi angka.',
        ]);

        $nik = (string) $request->input('nik');

        if (! Nik::isValid($nik)) {
            return back()
                ->withErrors(['nik' => 'NIK tidak valid. Periksa kembali 16 digit angka pada KTP/KK Anda.'])
                ->withInput();
        }

        $request->session()->put('alumni_nik', $nik);

        return Alumni::where('nik', $nik)->exists()
            ? redirect()->route('alumni.register.recorded')
            : redirect()->route('alumni.register.form');
    }

    public function registerFormFields(Request $request): View|RedirectResponse
    {
        $nik = (string) $request->session()->get('alumni_nik', '');

        if (! Nik::isValid($nik)) {
            return redirect()->route('alumni.register')
                ->withErrors(['nik' => 'Silakan masukkan NIK terlebih dahulu.']);
        }

        if (Alumni::where('nik', $nik)->exists()) {
            return redirect()->route('alumni.register.recorded');
        }

        return view('alumni.register-form', [
            'nik' => $nik,
            'totalAlumni' => Alumni::tampil()->count(),
            'lembagaOptions' => Alumni::lembagaOptions(),
            'pekerjaanOptions' => Alumni::pekerjaanOptions(),
            'domisiliOptions' => Alumni::domisiliOptions(),
            'years' => range((int) now()->year + 1, 1950),
        ]);
    }

    public function registerRecorded(Request $request): View|RedirectResponse
    {
        $nik = (string) $request->session()->get('alumni_nik', '');

        if (! Nik::isValid($nik)) {
            return redirect()->route('alumni.register')
                ->withErrors(['nik' => 'Silakan masukkan NIK terlebih dahulu.']);
        }

        $alumni = Alumni::where('nik', $nik)->first();

        if (! $alumni) {
            return redirect()->route('alumni.register.form');
        }

        return view('alumni.register-recorded', [
            'alumni' => $alumni,
            'totalAlumni' => Alumni::tampil()->count(),
        ]);
    }

    public function resetNik(Request $request): RedirectResponse
    {
        $request->session()->forget('alumni_nik');

        return redirect()->route('alumni.register');
    }

    public function registerStore(Request $request): RedirectResponse
    {
        // Honeypot: bot yang mengisi field tersembunyi langsung ditolak halus.
        if ($request->filled('website')) {
            return redirect()->route('alumni.register.success');
        }

        $nik = (string) $request->session()->get('alumni_nik', '');

        if (! Nik::isValid($nik)) {
            return redirect()->route('alumni.register')
                ->withErrors(['nik' => 'Sesi NIK tidak valid. Silakan masukkan NIK kembali.']);
        }

        if (Alumni::where('nik', $nik)->exists()) {
            return redirect()->route('alumni.register.recorded')
                ->withErrors(['nik' => 'NIK sudah terdaftar.']);
        }

        // NIK diambil dari sesi, bukan dari input form.
        $request->merge(['nik' => $nik]);

        $data = $request->validate([
            'nik' => ['required', 'digits:16', 'unique:alumnis,nik'],
            'nama' => 'required|string|max:150',
            'angkatan' => 'nullable|integer|min:1900|max:2100',
            'tahun_lulus' => 'nullable|integer|min:1900|max:2100',
            'lembaga' => 'nullable|string|max:150',
            'lembaga_lainnya' => 'nullable|string|max:150',
            'domisili' => 'nullable|string|max:150',
            'pekerjaan' => 'nullable|string|max:150',
            'pekerjaan_lainnya' => 'nullable|string|max:150',
            'no_hp' => 'required|string|max:20|regex:/^[0-9+\-\s]{9,20}$/',
            'email' => 'nullable|email|max:150',
            'tentang' => 'nullable|string|max:2000',
            'foto' => 'nullable|image|max:2048',
        ], [
            'no_hp.regex' => 'Nomor HP tidak valid (9-20 karakter, angka / + / - / spasi).',
            'nik.unique' => 'NIK sudah terdaftar.',
        ]);

        foreach (['lembaga', 'pekerjaan'] as $field) {
            if (($data[$field] ?? null) === '__other__') {
                $data[$field] = trim((string) ($data[$field.'_lainnya'] ?? '')) ?: null;
            }
        }
        unset($data['lembaga_lainnya'], $data['pekerjaan_lainnya']);

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
        $request->session()->forget('alumni_nik');

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
        return view('alumni.register-success', [
            'totalAlumni' => Alumni::tampil()->count(),
        ]);
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
