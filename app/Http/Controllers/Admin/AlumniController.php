<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlumniController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q'));
        $angkatan = $request->query('angkatan');

        $alumnis = Alumni::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nama', 'like', "%{$q}%")
                        ->orWhere('lembaga', 'like', "%{$q}%")
                        ->orWhere('domisili', 'like', "%{$q}%")
                        ->orWhere('pekerjaan', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when($angkatan, fn ($query) => $query->where('angkatan', $angkatan))
            ->orderByDesc('angkatan')
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        $angkatans = Alumni::query()
            ->whereNotNull('angkatan')
            ->distinct()
            ->orderByDesc('angkatan')
            ->pluck('angkatan');

        return view('admin.alumni.index', compact('alumnis', 'angkatans', 'q', 'angkatan'));
    }

    public function create(): View
    {
        return view('admin.alumni.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_tampil'] = $request->boolean('is_tampil');

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('alumni', 'public');
        }

        Alumni::create($data);

        return redirect()->route('admin.alumni.index')->with('sukses', 'Data alumni ditambahkan.');
    }

    public function edit(Alumni $alumni): View
    {
        return view('admin.alumni.form', compact('alumni'));
    }

    public function update(Request $request, Alumni $alumni): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_tampil'] = $request->boolean('is_tampil');

        if ($request->hasFile('foto')) {
            $alumni->deleteStoredFile($alumni->foto);
            $data['foto'] = $request->file('foto')->store('alumni', 'public');
        } else {
            unset($data['foto']);
        }

        $alumni->update($data);

        return redirect()->route('admin.alumni.index')->with('sukses', 'Data alumni diperbarui.');
    }

    public function destroy(Alumni $alumni): RedirectResponse
    {
        $alumni->delete();

        return back()->with('sukses', 'Data alumni dihapus.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request): array
    {
        return $request->validate([
            'nama' => 'required|string|max:150',
            'angkatan' => 'nullable|integer|min:1900|max:2100',
            'tahun_lulus' => 'nullable|integer|min:1900|max:2100',
            'lembaga' => 'nullable|string|max:150',
            'domisili' => 'nullable|string|max:150',
            'pekerjaan' => 'nullable|string|max:150',
            'no_hp' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'tentang' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);
    }
}
