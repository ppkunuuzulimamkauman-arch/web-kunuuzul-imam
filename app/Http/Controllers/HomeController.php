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
        // Hero slide diambil dari Berita/Artikel yang ditandai ⭐ di admin
        // (maks. Berita::MAX_HERO). Fallback ke HeroSlide bila belum ada.
        try {
            $flagged = Berita::where('is_published', true)->where('is_hero', true)
                ->latest('published_at')->take(Berita::MAX_HERO)->get();
            $heroSlides = $flagged->map(function ($b) {
                $img = $b->gambar ? 'storage/' . ltrim($b->gambar, '/') : null;
                return (object) [
                    'label' => strtoupper($b->kategori ?? 'PONDOK'),
                    'desc' => \Str::limit(trim((string) ($b->excerpt ?? '')), 80),
                    'badge' => '📰 ' . ucfirst($b->kategori ?? 'Berita'),
                    'title' => $b->judul,
                    'sub' => \Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($b->excerpt ?? $b->konten ?? ''))), 140),
                    'gambar' => $img ? asset($img) : '/images/hero/slide-1.jpg',
                    'cta1_text' => 'BACA SELENGKAPNYA',
                    'cta1_url' => route('berita.show', $b->slug),
                    'cta2_text' => $b->kategori === 'artikel' ? 'SEMUA ARTIKEL' : 'SEMUA BERITA',
                    'cta2_url' => $b->kategori === 'artikel' ? route('artikel.index') : route('berita.index'),
                ];
            });
        } catch (\Throwable $e) { $heroSlides = collect(); }
        if ($heroSlides->isEmpty()) {
            try { $heroSlides = HeroSlide::where('is_aktif', true)->orderBy('urutan')->get(); }
            catch (\Throwable $e) { $heroSlides = collect(); }
        }
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
        // Sorotan Utama = berita bertanda ⭐ (is_hero), maks. Berita::MAX_HERO
        // dan tampil bergantian di slider. Kalau belum ada yang ditandai,
        // fallback ke berita terbaru. Linimasa = berita terbaru berikutnya
        // (semua sorotan dikeluarkan dari pool agar tidak dobel).
        try {
            $sorotanFlagged = Berita::where('is_published', true)->where('is_hero', true)
                ->whereNotIn('kategori', ['artikel', 'maklumat', 'dawuh', 'bahtsul'])
                ->latest('published_at')->take(Berita::MAX_HERO)->get();
        } catch (\Throwable $e) { $sorotanFlagged = collect(); }
        $sorotan = $sorotanFlagged;
        if ($sorotan->isEmpty() && $pool->isNotEmpty()) {
            $sorotan = collect([$pool->first()]);
        }
        $sorotanIds = $sorotan->pluck('id')->all();
        $rest = $pool->reject(fn ($b) => in_array($b->id, $sorotanIds, true))->values();
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
            'sorotan' => $sorotan,
            'linimasa' => $rest->slice(0, 3)->values(),
            'cerita' => $rest->slice(3, 4)->values(),
            'palingdibaca' => $palingdibaca,
            'recentList' => $rest->slice(7, 4)->values(),
            'popularList' => $rest->slice(11, 4)->values(),
            'featSlides' => $rest->slice(15, 3)->values(),
            'featSubs' => $rest->slice(18, 2)->values(),
            'terbaruList' => $rest->slice(20, 3)->values(),
            'rekomendasi' => $rest->slice(23, 2)->values(),
            'populerList' => $rest->slice(25, 3)->values(),
        ]);
    }

    public function profil(): View
    {
        try { $pengasuhs = Pengasuh::where('is_aktif', true)->orderBy('urutan')->take(3)->get(); }
        catch (\Throwable $e) { $pengasuhs = collect(); }
        return view('profil', compact('pengasuhs'));
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

    public function masyayikh(Pengasuh $pengasuh): View
    {
        try {
            $lainnya = Pengasuh::where('is_aktif', true)->where('id', '!=', $pengasuh->id)->orderBy('urutan')->take(4)->get();
        } catch (\Throwable $e) { $lainnya = collect(); }
        return view('profil-masyayikh', compact('pengasuh', 'lainnya'));
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

    public function galeri(): View
    {
        $galeris = Galeri::latest()->paginate(12);

        return view('galeri-index', compact('galeris'));
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
