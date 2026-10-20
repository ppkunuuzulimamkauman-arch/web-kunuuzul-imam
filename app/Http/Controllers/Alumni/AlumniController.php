<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Services\Turnstile;
use App\Support\Nik;
use App\Support\WilayahPk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AlumniController extends Controller
{
    public function index(Request $request): View
    {
        $nik = preg_replace('/\D+/', '', (string) $request->query('nik', ''));
        $searched = $nik !== '';
        $hasil = null;
        $nikError = null;

        if ($searched) {
            if (! Nik::isValid($nik)) {
                $nikError = 'NIK tidak valid. Masukkan 16 digit angka yang benar.';
            } else {
                $hasil = Alumni::tampil()->where('nik', $nik)->first();

                if (! $hasil) {
                    $nikError = 'Data alumni tidak ditemukan atau belum ditampilkan.';
                }
            }
        }

        return view('alumni.index', array_merge([
            'nik' => $nik,
            'searched' => $searched,
            'hasil' => $hasil,
            'nikError' => $nikError,
        ], $this->statistics()));
    }

    private function statistics(): array
    {
        $total = Alumni::tampil()->count();

        $perAngkatan = Alumni::tampil()
            ->whereNotNull('angkatan')
            ->select('angkatan', DB::raw('count(*) as total'))
            ->groupBy('angkatan')
            ->orderByDesc('angkatan')
            ->get();

        $perWilayahPk = Alumni::tampil()
            ->whereNotNull('wilayah_pk')
            ->where('wilayah_pk', '!=', '')
            ->select('wilayah_pk', DB::raw('count(*) as total'))
            ->groupBy('wilayah_pk')
            ->orderByDesc('total')
            ->orderBy('wilayah_pk')
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
        $jumlahWilayahPk = Alumni::tampil()
            ->whereNotNull('wilayah_pk')
            ->where('wilayah_pk', '!=', '')
            ->distinct()
            ->count('wilayah_pk');
        $jumlahDomisili = Alumni::tampil()
            ->whereNotNull('domisili')
            ->where('domisili', '!=', '')
            ->distinct()
            ->count('domisili');
        $maxAngkatan = (int) ($perAngkatan->max('total') ?? 0);

        return compact(
            'total',
            'perAngkatan',
            'perWilayahPk',
            'perDomisili',
            'jumlahAngkatan',
            'jumlahWilayahPk',
            'jumlahDomisili',
            'maxAngkatan'
        );
    }

    public function show(Alumni $alumni): View
    {
        abort_unless($alumni->isPubliclyVisible(), 404);

        return view('alumni.show', compact('alumni'));
    }

    public function checkNik(Request $request): RedirectResponse
    {
        $request->merge([
            'nik' => preg_replace('/\D+/', '', (string) $request->input('nik')),
        ]);

        $request->validate([
            'nik' => ['required', 'string', 'size:16', 'regex:/^\d{16}$/'],
            'source' => ['nullable', 'in:register,directory'],
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

        $alumni = Alumni::where('nik', $nik)->first();

        if (! $alumni) {
            return redirect()->route('alumni.register.form');
        }

        if ($request->input('source') === 'directory' && $alumni->isPubliclyVisible()) {
            return redirect()->route('alumni.home', ['nik' => $nik]);
        }

        return redirect()->route('alumni.register.recorded');
    }

    public function registerFormFields(Request $request): View|RedirectResponse
    {
        $nik = (string) $request->session()->get('alumni_nik', '');

        if (! Nik::isValid($nik)) {
            $nik = '';
        }

        if ($nik !== '' && Alumni::where('nik', $nik)->exists()) {
            return redirect()->route('alumni.register.recorded');
        }

        return view('alumni.register-form', [
            'nik' => $nik,
            'pekerjaanOptions' => Alumni::pekerjaanOptions(),
            'kabupatenOptions' => WilayahPk::kabupatenOptions(),
            'kecamatanOptions' => WilayahPk::kecamatanOptions(),
            'wilayahPkMap' => WilayahPk::map(),
            'years' => range((int) now()->year + 1, 1950),
        ]);
    }

    public function registerRecorded(Request $request): View|RedirectResponse
    {
        $nik = (string) $request->session()->get('alumni_nik', '');

        if (! Nik::isValid($nik)) {
            return redirect()->route('alumni.register.form')
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

        $nik = preg_replace('/\D+/', '', (string) $request->input('nik'));

        if (Nik::isValid($nik) && Alumni::where('nik', $nik)->exists()) {
            $request->session()->put('alumni_nik', $nik);

            return redirect()->route('alumni.register.recorded')
                ->withErrors(['nik' => 'NIK sudah terdaftar.']);
        }

        $request->merge(['nik' => $nik]);

        $data = $request->validate([
            'nik' => [
                'required',
                'digits:16',
                'unique:alumnis,nik',
                function ($attribute, $value, $fail) {
                    if (! Nik::isValid((string) $value)) {
                        $fail('NIK tidak valid. Periksa kembali 16 digit angka pada KTP/KK Anda.');
                    }
                },
            ],
            'nama' => 'required|string|max:150',
            'jenis_kelamin' => 'required|in:L,P',
            'status_hidup' => 'required|in:hidup,meninggal',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'angkatan' => 'nullable|integer|min:1900|max:2100',
            'tahun_lulus' => 'nullable|integer|min:1900|max:2100',
            'pekerjaan' => 'nullable|string|max:150',
            'pekerjaan_lainnya' => 'nullable|string|max:150',
            'no_hp' => 'required|string|max:20|regex:/^[0-9+\-\s]{9,20}$/',
            'provinsi' => 'required|string|max:100',
            'kabupaten' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'desa' => 'required|string|max:100',
            'dusun' => 'required|string|max:100',
            'rt' => ['required', 'regex:/^[0-9]{1,3}$/'],
            'rw' => ['required', 'regex:/^[0-9]{1,3}$/'],
            'foto' => 'nullable|image|max:2048',
        ], [
            'no_hp.regex' => 'Nomor HP tidak valid (9-20 karakter, angka / + / - / spasi).',
            'nik.unique' => 'NIK sudah terdaftar.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'status_hidup.required' => 'Status wajib dipilih.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Tanggal lahir tidak valid.',
            'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh melebihi hari ini.',
            'provinsi.required' => 'Provinsi wajib diisi.',
            'kabupaten.required' => 'Kabupaten wajib diisi.',
            'kecamatan.required' => 'Kecamatan wajib diisi.',
            'desa.required' => 'Desa/kelurahan wajib diisi.',
            'dusun.required' => 'Dusun wajib diisi.',
            'rt.required' => 'RT wajib diisi.',
            'rt.regex' => 'RT harus berupa 1-3 digit angka.',
            'rw.required' => 'RW wajib diisi.',
            'rw.regex' => 'RW harus berupa 1-3 digit angka.',
        ]);

        if (($data['pekerjaan'] ?? null) === '__other__') {
            $data['pekerjaan'] = trim((string) ($data['pekerjaan_lainnya'] ?? '')) ?: null;
        }
        unset($data['pekerjaan_lainnya']);

        $data['wilayah_pk'] = WilayahPk::for($data['kabupaten'], $data['kecamatan']);
        $data['domisili'] = $data['kabupaten'];

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

}
