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

class ProgramController extends Controller
{
    public function index(): View
    {
        return view('admin.program.index', ['programs' => Program::orderBy('urutan')->paginate(12)]);
    }

    public function create(): View
    {
        return view('admin.program.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => 'required|string|max:150',
            'kategori' => 'required|string|max:50',
            'deskripsi_singkat' => 'nullable|string|max:500',
            'deskripsi' => 'nullable|string',
            'ikon' => 'nullable|string|max:20',
            'gambar' => 'nullable|image|max:2048',
            'is_aktif' => 'boolean',
            'urutan' => 'nullable|integer|min:0',
        ]);
        $data['slug'] = Str::slug($data['nama']).'-'.time();
        $data['is_aktif'] = $request->boolean('is_aktif', true);
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('program', 'public');
        }
        Program::create($data);
        return redirect()->route('admin.program.index')->with('sukses', 'Program ditambahkan.');
    }

    public function edit(Program $program): View
    {
        return view('admin.program.form', compact('program'));
    }

    public function update(Request $request, Program $program): RedirectResponse
    {
        $data = $request->validate([
            'nama' => 'required|string|max:150',
            'kategori' => 'required|string|max:50',
            'deskripsi_singkat' => 'nullable|string|max:500',
            'deskripsi' => 'nullable|string',
            'ikon' => 'nullable|string|max:20',
            'gambar' => 'nullable|image|max:2048',
            'urutan' => 'nullable|integer|min:0',
        ]);
        $data['slug'] = Str::slug($data['nama']).'-'.$program->id;
        $data['is_aktif'] = $request->boolean('is_aktif');
        if ($request->hasFile('gambar')) {
            $program->deleteStoredFile($program->gambar);
            $data['gambar'] = $request->file('gambar')->store('program', 'public');
        } else {
            unset($data['gambar']);
        }
        $program->update($data);
        return redirect()->route('admin.program.index')->with('sukses', 'Program diperbarui.');
    }

    public function destroy(Program $program): RedirectResponse
    {
        $program->delete();
        return back()->with('sukses', 'Program dihapus.');
    }
}
