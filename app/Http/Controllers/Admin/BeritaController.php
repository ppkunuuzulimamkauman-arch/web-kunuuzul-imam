<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function index(Request $request): View
    {
        $beritas = Berita::when($request->q, fn ($q) => $q->where('judul', 'like', '%'.$request->q.'%'))
            ->when($request->kategori, fn ($q) => $q->where('kategori', $request->kategori))
            ->latest()->paginate(10)->withQueryString();

        return view('admin.berita.index', compact('beritas'));
    }

    public function create(): View
    {
        return view('admin.berita.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'judul' => 'required|string|max:200',
            'kategori' => 'required|string|max:50',
            'excerpt' => 'nullable|string|max:500',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|max:2048',
            'penulis' => 'nullable|string|max:100',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $data['slug'] = Str::slug($data['judul']).'-'.time();
        $data['penulis'] = $data['penulis'] ?? auth()->user()->name;
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published'] ? ($data['published_at'] ?? now()) : null;

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        Berita::create($data);

        return redirect()->route('admin.berita.index')->with('sukses', 'Berita berhasil ditambahkan.');
    }

    public function edit(Berita $berita): View
    {
        return view('admin.berita.form', compact('berita'));
    }

    public function update(Request $request, Berita $berita): RedirectResponse
    {
        $data = $request->validate([
            'judul' => 'required|string|max:200',
            'kategori' => 'required|string|max:50',
            'excerpt' => 'nullable|string|max:500',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|max:2048',
            'penulis' => 'nullable|string|max:100',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($berita->judul !== $data['judul']) {
            $data['slug'] = Str::slug($data['judul']).'-'.$berita->id;
        } else {
            unset($data['slug']);
        }
        $data['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('gambar')) {
            $berita->deleteStoredFile($berita->gambar);
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        } else {
            unset($data['gambar']);
        }

        $berita->update($data);

        return redirect()->route('admin.berita.index')->with('sukses', 'Berita berhasil diperbarui.');
    }

    public function destroy(Berita $berita): RedirectResponse
    {
        $berita->delete();
        return back()->with('sukses', 'Berita dihapus.');
    }

    public function toggle(Berita $berita): RedirectResponse
    {
        $berita->update([
            'is_published' => ! $berita->is_published,
            'published_at' => ! $berita->is_published ? now() : $berita->published_at,
        ]);
        return back()->with('sukses', 'Status publikasi diubah.');
    }

    public function toggleHero(Berita $berita): RedirectResponse
    {
        if (! $berita->is_hero && Berita::where('is_hero', true)->where('id', '!=', $berita->id)->count() >= Berita::MAX_HERO) {
            return back()->withErrors(['hero' => 'Sorotan Utama maksimal '.Berita::MAX_HERO.' berita. Matikan bintang pada berita lain dulu.']);
        }
        $berita->update(['is_hero' => ! $berita->is_hero]);
        return back()->with('sukses', $berita->is_hero ? 'Ditampilkan sebagai Sorotan Utama.' : 'Dihapus dari Sorotan Utama.');
    }
}
