<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Models\HeroSlide;
use App\Models\Lembaga;
use App\Models\Pendaftaran;
use App\Models\Pesan;
use App\Models\Pengasuh;
use App\Models\Program;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GaleriController extends Controller
{
    public function index(): View
    {
        return view('admin.galeri.index', ['galeris' => Galeri::latest()->paginate(12)]);
    }

    public function create(): View
    {
        return view('admin.galeri.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'judul' => 'required|string|max:150',
            'kategori' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string|max:500',
            'gambar' => 'nullable|image|max:4096',
        ]);
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('galeri', 'public');
        }
        Galeri::create($data);
        return redirect()->route('admin.galeri.index')->with('sukses', 'Foto galeri ditambahkan.');
    }

    public function edit(Galeri $galeri): View
    {
        return view('admin.galeri.form', compact('galeri'));
    }

    public function update(Request $request, Galeri $galeri): RedirectResponse
    {
        $data = $request->validate([
            'judul' => 'required|string|max:150',
            'kategori' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string|max:500',
            'gambar' => 'nullable|image|max:4096',
        ]);
        if ($request->hasFile('gambar')) {
            $galeri->deleteStoredFile($galeri->gambar);
            $data['gambar'] = $request->file('gambar')->store('galeri', 'public');
        } else {
            unset($data['gambar']);
        }
        $galeri->update($data);
        return redirect()->route('admin.galeri.index')->with('sukses', 'Galeri diperbarui.');
    }

    public function destroy(Galeri $galeri): RedirectResponse
    {
        $galeri->delete();
        return back()->with('sukses', 'Galeri dihapus.');
    }
}
