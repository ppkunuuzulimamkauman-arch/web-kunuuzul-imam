<?php

namespace App\Http\Controllers\Admin\Siakad;

use App\Http\Controllers\Controller;
use App\Models\AkademikKelas;
use App\Models\AkademikTahunAjaran;
use App\Models\Alumni;
use App\Models\Pendaftaran;
use App\Models\Santri;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $tahunAktif = AkademikTahunAjaran::aktif()->latest('mulai')->first();

        $stats = [
            'santri_aktif' => Santri::where('status', 'aktif')->count(),
            'santri_lulus' => Santri::where('status', 'lulus')->count(),
            'pendaftar_belum_terhubung' => Pendaftaran::whereIn('status', ['diterima', 'lunas'])
                ->whereNull('santri_id')->count(),
            'alumni_belum_terhubung' => Alumni::whereNull('santri_id')->count(),
            'kelas_aktif' => AkademikKelas::where('is_aktif', true)->count(),
        ];

        $santriTerbaru = Santri::latest()->take(6)->get();
        $kelasAktif = AkademikKelas::with('tahunAjaran')
            ->withCount('santris')
            ->where('is_aktif', true)
            ->latest()
            ->take(6)
            ->get();

        return view('admin.siakad.dashboard', compact('stats', 'santriTerbaru', 'kelasAktif', 'tahunAktif'));
    }
}
