<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
use App\Models\Lembaga;
use App\Models\Setting;
use App\Models\SuratPermohonan;
use App\Services\SantriService;
use App\Services\Turnstile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SuratController extends Controller
{
    public function index(): View
    {
        return view('surat.index', [
            'jenisOptions' => SuratPermohonan::jenisOptions(),
            'jenisMeta' => SuratPermohonan::jenisMeta(),
            'jumlahSelesai' => SuratPermohonan::where('status', SuratPermohonan::STATUS_SELESAI)->count(),
            'jamLayanan' => Setting::get('kontak_jam', 'Senin–Sabtu 08.00–15.00'),
            'waKontak' => Setting::get('kontak_wa_1'),
        ]);
    }

    public function create(): View
    {
        return view('surat.create', [
            'jenisOptions' => SuratPermohonan::jenisOptions(),
            'jenisMeta' => SuratPermohonan::jenisMeta(),
            'lembagaOptions' => $this->lembagaOptions(),
        ]);
    }

    public function store(Request $request, SantriService $santriService): RedirectResponse
    {
        // Honeypot: bot yang mengisi field tersembunyi langsung ditolak halus.
        if ($request->filled('website')) {
            return redirect()->route('surat.home');
        }

        $jenis = (string) $request->input('jenis');

        $rules = [
            'jenis' => ['required', 'string', Rule::in(array_keys(SuratPermohonan::jenisOptions()))],
            'nama' => ['required', 'string', 'max:150'],
            'nik' => ['nullable', 'digits:16'],
            'no_hp' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]{9,20}$/'],
            'email' => ['nullable', 'email', 'max:150'],
            'lembaga' => ['nullable', 'string', 'max:150'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'keperluan' => ['required', 'string', 'max:1500'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date'],
            'tujuan' => ['nullable', 'string', 'max:150'],
            'ditujukan' => ['nullable', 'string', 'max:150'],
            'jenis_lainnya' => ['nullable', 'string', 'max:150'],
            'lampiran' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'setuju' => ['accepted'],
        ];

        if ($jenis === 'izin_pulang') {
            $rules['tanggal_mulai'] = ['required', 'date'];
            $rules['tanggal_selesai'] = ['required', 'date', 'after_or_equal:tanggal_mulai'];
            $rules['tujuan'] = ['required', 'string', 'max:150'];
        }

        if (in_array($jenis, ['rekomendasi', 'pengantar'], true)) {
            $rules['ditujukan'] = ['required', 'string', 'max:150'];
        }

        if ($jenis === 'keterangan_lain') {
            $rules['jenis_lainnya'] = ['required', 'string', 'max:150'];
        }

        $data = $request->validate($rules, [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'no_hp.required' => 'Nomor HP / WhatsApp wajib diisi.',
            'no_hp.regex' => 'Nomor HP tidak valid (9-20 karakter, angka / + / - / spasi).',
            'nik.digits' => 'NIK harus 16 digit angka.',
            'keperluan.required' => 'Keperluan surat wajib diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai izin wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai izin wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'tujuan.required' => 'Tujuan izin wajib diisi.',
            'ditujukan.required' => 'Instansi / lembaga tujuan wajib diisi.',
            'jenis_lainnya.required' => 'Sebutkan jenis surat yang dibutuhkan.',
            'lampiran.mimes' => 'Lampiran harus berupa PDF, JPG, atau PNG.',
            'lampiran.max' => 'Ukuran lampiran maksimal 4 MB.',
            'setuju.accepted' => 'Silakan centang pernyataan kebenaran data.',
        ]);

        if (! Turnstile::verify($request->input('cf-turnstile-response'), $request->ip())) {
            return back()
                ->withErrors(['captcha' => 'Verifikasi keamanan gagal. Silakan coba lagi.'])
                ->withInput();
        }

        unset($data['setuju']);

        $data['nik'] = $data['nik'] ?: null;
        $data['kode'] = SuratPermohonan::generateKode();
        $data['status'] = SuratPermohonan::STATUS_MENUNGGU;

        if ($request->hasFile('lampiran')) {
            $data['lampiran'] = $request->file('lampiran')->store('surat/lampiran', 'public');
        }

        $permohonan = SuratPermohonan::create($data);
        $santriService->linkSurat($permohonan);

        // Notifikasi email ke admin (best-effort, gagal kirim tidak menggagalkan permohonan).
        try {
            $adminEmail = Setting::get('kontak_email');
            if ($adminEmail && filter_var($adminEmail, FILTER_VALIDATE_EMAIL) && ! str_ends_with($adminEmail, '@example.com')) {
                Mail::raw(
                    "Permohonan surat baru: {$permohonan->kode}\n"
                    ."Pemohon: {$permohonan->nama} ({$permohonan->no_hp})\n"
                    ."Jenis: {$permohonan->jenisLabel()}\n"
                    .'Keperluan: '.Str::limit($permohonan->keperluan, 300)."\n\n"
                    .'Buka panel admin untuk memproses permohonan ini.',
                    function ($m) use ($adminEmail, $permohonan) {
                        $m->to($adminEmail)->subject('Permohonan surat baru — '.$permohonan->kode);
                    }
                );
            }
        } catch (\Throwable $e) {
        }

        return redirect()->route('surat.sukses', ['kode' => $permohonan->kode]);
    }

    public function sukses(string $kode): View
    {
        $permohonan = SuratPermohonan::where('kode', Str::upper($kode))->firstOrFail();

        return view('surat.sukses', [
            'permohonan' => $permohonan,
            'waKontak' => Setting::get('kontak_wa_1'),
        ]);
    }

    public function cek(Request $request): View
    {
        $kode = Str::upper(trim((string) $request->query('kode', '')));
        $searched = $kode !== '';
        $permohonan = null;
        $kodeError = null;

        if ($searched) {
            $permohonan = SuratPermohonan::where('kode', $kode)->first();

            if (! $permohonan) {
                $kodeError = 'Kode permohonan tidak ditemukan. Periksa kembali kode yang Anda terima saat mengajukan surat.';
            }
        }

        return view('surat.cek', [
            'kode' => $kode,
            'searched' => $searched,
            'permohonan' => $permohonan,
            'kodeError' => $kodeError,
            'waKontak' => Setting::get('kontak_wa_1'),
        ]);
    }

    /** @return array<int, string> */
    private function lembagaOptions(): array
    {
        $defaults = ['MTs', 'MA', 'Madrasah Diniyah', 'Tahfidz', "Ma'had Aly"];

        try {
            $lembaga = Lembaga::query()
                ->where('is_aktif', true)
                ->orderBy('urutan')
                ->pluck('nama')
                ->all();
        } catch (\Throwable $e) {
            $lembaga = [];
        }

        $options = array_map(fn ($item) => trim((string) $item), array_merge($defaults, $lembaga));

        return array_values(array_unique(array_filter($options, fn ($item) => $item !== '')));
    }
}
