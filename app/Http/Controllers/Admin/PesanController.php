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

class PesanController extends Controller
{
    public function index(): View
    {
        return view('admin.pesan.index', [
            'pesans' => Pesan::latest()->paginate(12),
            'belumDibaca' => Pesan::where('is_dibaca', false)->count(),
        ]);
    }

    public function show(Pesan $pesan): View
    {
        $pesan->update(['is_dibaca' => true]);
        return view('admin.pesan.show', compact('pesan'));
    }

    public function destroy(Pesan $pesan): RedirectResponse
    {
        $pesan->delete();
        return redirect()->route('admin.pesan.index')->with('sukses', 'Pesan dihapus.');
    }

    public function tandaiDibaca(Pesan $pesan): RedirectResponse
    {
        $pesan->update(['is_dibaca' => true]);
        return back()->with('sukses', 'Ditandai sudah dibaca.');
    }
}
