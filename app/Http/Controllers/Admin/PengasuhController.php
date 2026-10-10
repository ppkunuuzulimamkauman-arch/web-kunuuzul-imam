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

class PengasuhController extends Controller
{
    public function index(): View
    {
        return view('admin.pengasuh.index', ['pengasuhs' => Pengasuh::orderBy('urutan')->get()]);
    }

    public function create(): View
    {
        return view('admin.pengasuh.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => 'required|string|max:150', 'jabatan' => 'nullable|string|max:150',
            'bio' => 'nullable|string', 'foto' => 'nullable|image|max:2048',
            'urutan' => 'nullable|integer|min:0',
        ]);
        $data['is_aktif'] = $request->boolean('is_aktif', true);
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('pengasuh', 'public');
        }
        Pengasuh::create($data);
        return redirect()->route('admin.pengasuh.index')->with('sukses', 'Data pengasuh ditambahkan.');
    }

    public function edit(Pengasuh $pengasuh): View
    {
        return view('admin.pengasuh.form', compact('pengasuh'));
    }

    public function update(Request $request, Pengasuh $pengasuh): RedirectResponse
    {
        $data = $request->validate([
            'nama' => 'required|string|max:150', 'jabatan' => 'nullable|string|max:150',
            'bio' => 'nullable|string', 'foto' => 'nullable|image|max:2048',
            'urutan' => 'nullable|integer|min:0',
        ]);
        $data['is_aktif'] = $request->boolean('is_aktif');
        if ($request->hasFile('foto')) {
            $pengasuh->deleteStoredFile($pengasuh->foto);
            $data['foto'] = $request->file('foto')->store('pengasuh', 'public');
        } else {
            unset($data['foto']);
        }
        $pengasuh->update($data);
        return redirect()->route('admin.pengasuh.index')->with('sukses', 'Data pengasuh diperbarui.');
    }

    public function destroy(Pengasuh $pengasuh): RedirectResponse
    {
        $pengasuh->delete();
        return back()->with('sukses', 'Data pengasuh dihapus.');
    }
}
