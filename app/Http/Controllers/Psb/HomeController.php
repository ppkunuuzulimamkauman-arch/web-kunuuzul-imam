<?php

namespace App\Http\Controllers\Psb;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $tahunAjaran = trim((string) Setting::get('psb_tahun_ajaran', ''));

        // Narahubung khusus PSB; fallback ke WA asrama.
        $wa = trim((string) Setting::get('psb_narahubung', ''));
        if ($wa === '') {
            $wa = trim((string) Setting::get('kontak_wa_1', ''));
        }

        return view('psb.index', [
            'mainUrl' => rtrim((string) config('app.url'), '/'),
            'tahunAjaran' => $tahunAjaran !== '' ? $tahunAjaran : $this->defaultTahunAjaran(),
            'status' => trim((string) Setting::get('psb_status', '')),
            'gelombang' => $this->gelombang(),
            'syarat' => $this->lines(Setting::get('psb_syarat', '')),
            'biayaInfo' => trim((string) Setting::get('psb_biaya_info', '')),
            'wa' => $wa,
            'waUrl' => $this->waUrl($wa),
            'alamat' => $this->cleanPlaceholder(Setting::get('kontak_alamat_1', '')),
            'jam' => trim((string) Setting::get('kontak_jam', '')),
        ]);
    }

    /**
     * Tahun ajaran berjalan (Juli–Juni), dipakai bila admin belum mengisi.
     */
    private function defaultTahunAjaran(): string
    {
        $tahun = now()->month >= 7 ? now()->year : now()->year - 1;

        return $tahun.'/'.($tahun + 1);
    }

    /**
     * Pecah teks textarea menjadi baris bersih.
     *
     * @return array<int, string>
     */
    private function lines(?string $raw): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $raw))
            ->map(fn ($line) => trim((string) $line))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Gelombang: "Nama|Periode|Catatan" per baris.
     *
     * @return array<int, array{nama: string, periode: string, catatan: string}>
     */
    private function gelombang(): array
    {
        return collect($this->lines(Setting::get('psb_gelombang', '')))
            ->map(function (string $line) {
                $part = array_map('trim', explode('|', $line));

                return [
                    'nama' => $part[0] ?? '',
                    'periode' => $part[1] ?? '',
                    'catatan' => $part[2] ?? '',
                ];
            })
            ->filter(fn (array $g) => $g['nama'] !== '')
            ->values()
            ->all();
    }

    /**
     * Sembunyikan teks placeholder bawaan agar tidak tampil di halaman publik.
     */
    private function cleanPlaceholder(?string $value): string
    {
        $value = trim((string) $value);

        return str_contains($value, 'ganti di Pengaturan') ? '' : $value;
    }

    private function waUrl(string $nomor): ?string
    {
        $digit = preg_replace('/[^0-9]/', '', $nomor);

        if ($digit === '' || strlen($digit) < 10 || preg_match('/[a-z]/i', $nomor)) {
            return null;
        }

        if (str_starts_with($digit, '0')) {
            $digit = '62'.substr($digit, 1);
        }

        return 'https://wa.me/'.$digit;
    }
}
