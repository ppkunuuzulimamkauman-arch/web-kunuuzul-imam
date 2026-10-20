<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PsbController extends Controller
{
    /**
     * Kunci setting yang dikelola dari halaman admin PSB.
     */
    private const KEYS = [
        'psb_tahun_ajaran',
        'psb_status',
        'psb_gelombang',
        'psb_syarat',
        'psb_biaya_info',
        'psb_narahubung',
    ];

    public function index(): View
    {
        $statuses = ['menunggu', 'diproses', 'diterima', 'ditolak', 'lunas'];

        $counts = ['total' => Pendaftaran::count()];
        foreach ($statuses as $status) {
            $counts[$status] = Pendaftaran::where('status', $status)->count();
        }

        $values = collect(self::KEYS)
            ->mapWithKeys(fn (string $key) => [$key => (string) Setting::get($key, '')])
            ->all();

        $scheme = parse_url((string) config('app.url'), PHP_URL_SCHEME) ?: 'https';
        $psbUrl = $scheme.'://psb.'.config('pondok.domain');

        $recent = Pendaftaran::latest()->take(5)->get();

        return view('admin.psb.index', compact('counts', 'statuses', 'values', 'psbUrl', 'recent'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'psb_tahun_ajaran' => 'nullable|string|max:20',
            'psb_status' => 'nullable|string|max:30',
            'psb_gelombang' => 'nullable|string|max:2000',
            'psb_syarat' => 'nullable|string|max:2000',
            'psb_biaya_info' => 'nullable|string|max:1000',
            'psb_narahubung' => 'nullable|string|max:25',
        ]);

        foreach (self::KEYS as $key) {
            Setting::set($key, $data[$key] ?? '');
        }

        return back()->with('sukses', 'Informasi PSB berhasil disimpan.');
    }
}
