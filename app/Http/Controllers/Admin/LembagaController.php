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

class LembagaController extends Controller
{
    public function index(): View
    {
        return view('admin.lembaga.index', ['lembagas' => Lembaga::orderBy('urutan')->get()]);
    }

    public function create(): View
    {
        return view('admin.lembaga.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => 'required|string|max:150', 'deskripsi' => 'nullable|string',
            'ikon' => 'nullable|string|max:20', 'gambar' => 'nullable|image|max:2048',
            'url' => 'nullable|string|max:255', 'urutan' => 'nullable|integer|min:0',
        ]);
        $data['is_aktif'] = $request->boolean('is_aktif', true);
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('lembaga', 'public');
        }
        Lembaga::create($data);
        return redirect()->route('admin.lembaga.index')->with('sukses', 'Lembaga ditambahkan.');
    }

    public function edit(Lembaga $lembaga): View
    {
        return view('admin.lembaga.form', compact('lembaga'));
    }

    public function update(Request $request, Lembaga $lembaga): RedirectResponse
    {
        $data = $request->validate([
            'nama' => 'required|string|max:150', 'deskripsi' => 'nullable|string',
            'ikon' => 'nullable|string|max:20', 'gambar' => 'nullable|image|max:2048',
            'url' => 'nullable|string|max:255', 'urutan' => 'nullable|integer|min:0',
        ]);
        $data['is_aktif'] = $request->boolean('is_aktif');
        if ($request->hasFile('gambar')) {
            $lembaga->deleteStoredFile($lembaga->gambar);
            $data['gambar'] = $request->file('gambar')->store('lembaga', 'public');
        } else {
            unset($data['gambar']);
        }
        $lembaga->update($data);
        return redirect()->route('admin.lembaga.index')->with('sukses', 'Lembaga diperbarui.');
    }

    public function destroy(Lembaga $lembaga): RedirectResponse
    {
        $lembaga->delete();
        return back()->with('sukses', 'Lembaga dihapus.');
    }
}
