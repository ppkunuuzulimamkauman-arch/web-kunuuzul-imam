<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrganisasiController extends Controller
{
    public function index(): View
    {
        return view('admin.organisasi.index', ['organisasis' => Organisasi::orderBy('kategori')->orderBy('urutan')->get()]);
    }

    public function create(): View
    {
        return view('admin.organisasi.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'singkatan' => 'required|string|max:50',
            'nama' => 'nullable|string|max:255',
            'kategori' => 'required|in:dalam,luar',
            'deskripsi_singkat' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'struktur' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'urutan' => 'nullable|integer|min:0',
        ]);
        $data['slug'] = Str::slug($data['singkatan']);
        if (Organisasi::where('slug', $data['slug'])->exists()) {
            $data['slug'] .= '-'.time();
        }
        $data['is_aktif'] = $request->boolean('is_aktif', true);
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('organisasi', 'public');
        }
        Organisasi::create($data);
        return redirect()->route('admin.organisasi.index')->with('sukses', 'Organisasi ditambahkan.');
    }

    public function edit(Organisasi $organisasi): View
    {
        return view('admin.organisasi.form', compact('organisasi'));
    }

    public function update(Request $request, Organisasi $organisasi): RedirectResponse
    {
        $data = $request->validate([
            'singkatan' => 'required|string|max:50',
            'nama' => 'nullable|string|max:255',
            'kategori' => 'required|in:dalam,luar',
            'deskripsi_singkat' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'struktur' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'urutan' => 'nullable|integer|min:0',
        ]);
        $slug = Str::slug($data['singkatan']);
        if ($slug !== $organisasi->slug && Organisasi::where('slug', $slug)->exists()) {
            $slug .= '-'.time();
        }
        $data['slug'] = $slug;
        $data['is_aktif'] = $request->boolean('is_aktif');
        if ($request->hasFile('logo')) {
            $organisasi->deleteStoredFile($organisasi->logo);
            $data['logo'] = $request->file('logo')->store('organisasi', 'public');
        } else {
            unset($data['logo']);
        }
        $organisasi->update($data);
        return redirect()->route('admin.organisasi.index')->with('sukses', 'Organisasi diperbarui.');
    }

    public function destroy(Organisasi $organisasi): RedirectResponse
    {
        $organisasi->delete();
        return back()->with('sukses', 'Organisasi dihapus.');
    }
}
