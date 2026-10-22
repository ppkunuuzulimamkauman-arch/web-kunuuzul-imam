<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Pesan;
use App\Models\Program;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $now = Carbon::now();
        $bulanIni = $now->copy()->startOfMonth();
        $bulanLalu = $now->copy()->subMonth()->startOfMonth();
        $akhirBulanLalu = $now->copy()->startOfMonth();

        $delta = function (string $model) use ($bulanIni, $bulanLalu, $akhirBulanLalu) {
            $cur = $model::where('created_at', '>=', $bulanIni)->count();
            $prev = $model::where('created_at', '>=', $bulanLalu)->where('created_at', '<', $akhirBulanLalu)->count();

            if ($prev <= 0) {
                return ['nilai' => $cur > 0 ? 100.0 : 0.0];
            }

            return ['nilai' => round(($cur - $prev) / $prev * 100, 1)];
        };

        $namaBulan = [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'];
        $labels = [];
        $beritaSeries = [];
        $artikelSeries = [];

        for ($i = 5; $i >= 0; $i--) {
            $m = $now->copy()->subMonths($i);
            $labels[] = $namaBulan[$m->month];
            $beritaSeries[] = Berita::where('kategori', '!=', 'artikel')->whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count();
            $artikelSeries[] = Berita::where('kategori', 'artikel')->whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count();
        }

        $pesanTerbaru = Pesan::latest()->take(5)->get();
        $beritaTerbaru = Berita::latest()->take(5)->get();

        $aktivitas = collect()
            ->merge($pesanTerbaru->map(fn ($m) => [
                'judul' => 'Pesan dari '.$m->nama,
                'sub' => \Str::limit($m->subjek ?? $m->pesan, 60),
                'waktu' => $m->created_at,
                'ikon' => 'fa-envelope', 'warna' => '#2563eb',
            ]))
            ->merge($beritaTerbaru->map(fn ($b) => [
                'judul' => 'Berita: '.\Str::limit($b->judul, 55),
                'sub' => $b->kategori.($b->is_published ? ' - publish' : ' - draft'),
                'waktu' => $b->created_at,
                'ikon' => 'fa-newspaper', 'warna' => '#059669',
            ]))
            ->sortByDesc('waktu')->take(6)->values();

        $lastAdminLogin = User::where('is_active', true)
            ->whereNotNull('last_login_at')
            ->orderBy('last_login_at', 'desc')
            ->first();

        return view('admin.dashboard', [
            'totalBerita' => Berita::where('is_published', true)->count(),
            'totalPesan' => Pesan::count(),
            'pesanBelumDibaca' => Pesan::where('is_dibaca', false)->count(),
            'totalGaleri' => Galeri::count(),
            'totalProgram' => Program::count(),
            'dBerita' => $delta(Berita::class),
            'dPesan' => $delta(Pesan::class),
            'dGaleri' => $delta(Galeri::class),
            'dProgram' => $delta(Program::class),
            'chartLabels' => $labels,
            'chartBerita' => $beritaSeries,
            'chartArtikel' => $artikelSeries,
            'beritaTerbaru' => $beritaTerbaru,
            'pesanTerbaru' => $pesanTerbaru,
            'aktivitas' => $aktivitas,
            'lastAdminLogin' => $lastAdminLogin,
        ]);
    }
}
