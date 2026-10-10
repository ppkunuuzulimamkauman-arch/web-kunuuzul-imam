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

class HeroSlideController extends Controller
{
    public function index(): View
    {
        return view('admin.hero.index', ['slides' => HeroSlide::orderBy('urutan')->get()]);
    }

    public function create(): View
    {
        return view('admin.hero.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'label' => 'nullable|string|max:100', 'badge' => 'nullable|string|max:150',
            'title' => 'required|string|max:255', 'sub' => 'nullable|string|max:500',
            'desc' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|max:4096',
            'cta1_text' => 'nullable|string|max:50', 'cta1_url' => 'nullable|string|max:255',
            'cta2_text' => 'nullable|string|max:50', 'cta2_url' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer|min:0',
        ]);
        $data['is_aktif'] = $request->boolean('is_aktif', true);
        if ($request->hasFile('gambar')) {
            $data['gambar'] = '/storage/'.$request->file('gambar')->store('hero', 'public');
        }
        HeroSlide::create($data);
        return redirect()->route('admin.hero.index')->with('sukses', 'Slide ditambahkan.');
    }

    public function edit(HeroSlide $hero): View
    {
        $slide = $hero;
        return view('admin.hero.form', compact('slide'));
    }

    public function update(Request $request, HeroSlide $hero): RedirectResponse
    {
        $data = $request->validate([
            'label' => 'nullable|string|max:100', 'badge' => 'nullable|string|max:150',
            'title' => 'required|string|max:255', 'sub' => 'nullable|string|max:500',
            'desc' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|max:4096',
            'cta1_text' => 'nullable|string|max:50', 'cta1_url' => 'nullable|string|max:255',
            'cta2_text' => 'nullable|string|max:50', 'cta2_url' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer|min:0',
        ]);
        $data['is_aktif'] = $request->boolean('is_aktif');
        if ($request->hasFile('gambar')) {
            $hero->deleteStoredFile($hero->gambar);
            $data['gambar'] = '/storage/'.$request->file('gambar')->store('hero', 'public');
        } else {
            unset($data['gambar']);
        }
        $hero->update($data);
        return redirect()->route('admin.hero.index')->with('sukses', 'Slide diperbarui.');
    }

    public function destroy(HeroSlide $hero): RedirectResponse
    {
        $hero->delete();
        return back()->with('sukses', 'Slide dihapus.');
    }
}
