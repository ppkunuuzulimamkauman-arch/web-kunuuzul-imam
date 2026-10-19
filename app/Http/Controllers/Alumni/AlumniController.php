<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlumniController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q'));
        $angkatan = $request->query('angkatan');

        $alumnis = Alumni::tampil()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nama', 'like', "%{$q}%")
                        ->orWhere('lembaga', 'like', "%{$q}%")
                        ->orWhere('domisili', 'like', "%{$q}%")
                        ->orWhere('pekerjaan', 'like', "%{$q}%");
                });
            })
            ->when($angkatan, fn ($query) => $query->where('angkatan', $angkatan))
            ->orderByDesc('angkatan')
            ->orderBy('nama')
            ->paginate(12)
            ->withQueryString();

        $angkatans = Alumni::tampil()
            ->whereNotNull('angkatan')
            ->distinct()
            ->orderByDesc('angkatan')
            ->pluck('angkatan');

        return view('alumni.index', compact('alumnis', 'angkatans', 'q', 'angkatan'));
    }

    public function show(Alumni $alumni): View
    {
        abort_unless($alumni->is_tampil, 404);

        return view('alumni.show', compact('alumni'));
    }
}
