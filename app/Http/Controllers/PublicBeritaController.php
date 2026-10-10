<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\View\View;

class PublicBeritaController extends Controller
{
    public function index(): View
    {
        return $this->daftar('berita');
    }

    public function artikel(): View
    {
        return $this->daftar('artikel');
    }

    private function daftar(string $tipe): View
    {
        $q = trim((string) request('q', ''));
        $katAktif = trim((string) request('kategori', ''));
        $base = Berita::where('is_published', true);
        if ($tipe === 'artikel') {
            $base->where('kategori', 'artikel');
        } else {
            $base->where('kategori', '!=', 'artikel');
        }
        if ($q !== '') {
            $base->where(function ($w) use ($q) {
                $w->where('judul', 'like', "%{$q}%")
                  ->orWhere('excerpt', 'like', "%{$q}%")
                  ->orWhere('konten', 'like', "%{$q}%");
            });
        }
        if ($katAktif !== '') {
            $base->where('kategori', $katAktif);
        }
        $beritas = (clone $base)->latest('published_at')->paginate(9)->withQueryString();
        $rekomendasi = Berita::where('is_published', true)
            ->latest('published_at')
            ->take(5)
            ->get();
        $cats = Berita::where('is_published', true);
        if ($tipe === 'artikel') {
            $cats->where('kategori', 'artikel');
        } else {
            $cats->where('kategori', '!=', 'artikel');
        }
        $cats = $cats->selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get();
        $judulHal = $tipe === 'artikel' ? 'Artikel' : 'Berita';
        $routeIdx = $tipe === 'artikel' ? 'artikel.index' : 'berita.index';
        return view('berita-index', compact('beritas', 'rekomendasi', 'cats', 'q', 'katAktif', 'judulHal', 'routeIdx'));
    }

    public function show(string $slug): View
    {
        $berita = Berita::where('slug', $slug)->where('is_published', true)->firstOrFail();
        // Hitung 1x view per sesi agar refresh tidak menggelembungkan angka
        $seenKey = 'berita_dibaca_'.$berita->id;
        if (! session()->has($seenKey)) {
            $berita->increment('views');
            session()->put($seenKey, true);
        }
        $terkait = Berita::where('is_published', true)->where('id', '!=', $berita->id)->latest()->take(3)->get();
        return view('berita-show', compact('berita', 'terkait'));
    }
}
