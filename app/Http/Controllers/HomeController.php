<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\HeroSlide;
use App\Models\Lembaga;
use App\Models\Organisasi;
use App\Models\Pengasuh;
use App\Models\Program;
use App\Models\Setting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        try { $heroSlides = HeroSlide::where('is_aktif', true)->orderBy('urutan')->get(); }
        catch (\Throwable $e) { $heroSlides = collect(); }
        try {
            $agendas = Agenda::where('is_aktif', true)->whereDate('tanggal', '>=', now()->subDay())->orderBy('tanggal')->take(3)->get();
        } catch (\Throwable $e) { $agendas = collect(); }
        if (! isset($agendas) || $agendas->isEmpty()) {
            try { $agendas = Agenda::where('is_aktif', true)->orderBy('tanggal')->take(3)->get(); }
            catch (\Throwable $e) { $agendas = collect(); }
        }
        $kat = function (string $k, int $n = 3) {
            try { return Berita::where('is_published', true)->where('kategori', $k)->latest('published_at')->take($n)->get(); }
            catch (\Throwable $e) { return collect(); }
        };
        try { $topiks = Berita::where('is_published', true)->select('kategori')->distinct()->pluck('kategori')->filter()->take(10); }
        catch (\Throwable $e) { $topiks = collect(); }
        // Pool utama (artikel/maklumat/dawuh/bahtsul punya blok sendiri) dibagi jadi
        // potongan unik per blok agar tidak ada artikel yang tampil dua kali.
        // Paling Dibaca = ranking views asli, dikeluarkan dari pool agar tidak dobel.
        try {
            $palingdibaca = Berita::where('is_published', true)
                ->whereNotIn('kategori', ['artikel', 'maklumat', 'dawuh', 'bahtsul'])
                ->orderByDesc('views')->latest('published_at')->take(2)->get();
        } catch (\Throwable $e) { $palingdibaca = collect(); }
        $topIds = $palingdibaca->pluck('id')->all();
        try {
            $pool = Berita::where('is_published', true)
                ->whereNotIn('kategori', ['artikel', 'maklumat', 'dawuh', 'bahtsul'])
                ->whereNotIn('id', $topIds)
                ->latest('published_at')->take(35)->get();
        } catch (\Throwable $e) { $pool = collect(); }
        try { $programs = Program::where('is_aktif', true)->orderBy('urutan')->take(6)->get(); }
        catch (\Throwable $e) { $programs = collect(); }
        try { $beritas = Berita::where('is_published', true)->latest('published_at')->take(7)->get(); }
        catch (\Throwable $e) { $beritas = collect(); }
        try { $galeris = Galeri::latest()->take(8)->get(); }
        catch (\Throwable $e) { $galeris = collect(); }
        return view('home', [
            'programs' => $programs,
            'beritas' => $beritas,
            'galeris' => $galeris,
            'heroSlides' => $heroSlides,
            'agendas' => $agendas,
            'maklumats' => $kat('maklumat'),
            'dawuhs' => $kat('dawuh'),
            'bahtsul' => $kat('bahtsul'),
            'topiks' => $topiks,
            'sorotan' => $pool->slice(0, 4)->values(),
            'linimasa' => $pool->slice(4, 3)->values(),
            'cerita' => $pool->slice(7, 4)->values(),
            'palingdibaca' => $palingdibaca,
            'recentList' => $pool->slice(11, 4)->values(),
            'popularList' => $pool->slice(15, 4)->values(),
            'featSlides' => $pool->slice(19, 3)->values(),
            'featSubs' => $pool->slice(22, 2)->values(),
            'terbaruList' => $pool->slice(24, 3)->values(),
            'rekomendasi' => $pool->slice(27, 2)->values(),
            'populerList' => $pool->slice(29, 3)->values(),
        ]);
    }

    public function profil(): View
    {
        return view('profil');
    }

    public function sejarah(): View
    {
        return view('profil-sejarah');
    }

    public function struktur(): View
    {
        try { $pengasuhs = Pengasuh::where('is_aktif', true)->orderBy('urutan')->get(); }
        catch (\Throwable $e) { $pengasuhs = collect(); }
        return view('profil-struktur', compact('pengasuhs'));
    }

    public function visi(): View
    {
        return view('profil-visi');
    }

    public function lembaga(): View
    {
        try { $lembagas = Lembaga::where('is_aktif', true)->orderBy('urutan')->get(); }
        catch (\Throwable $e) { $lembagas = collect(); }
        return view('lembaga', compact('lembagas'));
    }

    public function organisasi(): View
    {
        try {
            $orgDalam = Organisasi::where('is_aktif', true)->where('kategori', 'dalam')->orderBy('urutan')->get();
            $orgLuar = Organisasi::where('is_aktif', true)->where('kategori', 'luar')->orderBy('urutan')->get();
        } catch (\Throwable $e) { $orgDalam = collect(); $orgLuar = collect(); }
        return view('organisasi', compact('orgDalam', 'orgLuar'));
    }

    public function organisasiShow(string $slug): View
    {
        $organisasi = Organisasi::where('slug', $slug)->where('is_aktif', true)->firstOrFail();
        $lainnya = Organisasi::where('is_aktif', true)->where('id', '!=', $organisasi->id)->orderBy('urutan')->take(4)->get();
        return view('organisasi-show', compact('organisasi', 'lainnya'));
    }

    public function santri(): View
    {
        return view('santri');
    }

    public function program(): View
    {
        return view('program-index', [
            'programs' => Program::where('is_aktif', true)->orderBy('urutan')->get(),
        ]);
    }

    public function programShow(string $slug): View
    {
        $program = Program::where('slug', $slug)->firstOrFail();
        return view('program-show', compact('program'));
    }
}
