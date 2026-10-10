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

class PendaftaranAdminController extends Controller
{
    public function index(Request $request): View
    {
        $pendaftarans = Pendaftaran::when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->q, fn ($q) => $q->where(fn ($w) => $w->where('nama_lengkap', 'like', '%'.$request->q.'%')->orWhere('nomor_pendaftaran', 'like', '%'.$request->q.'%')))
            ->latest()->paginate(12)->withQueryString();
        return view('admin.ppdb.index', compact('pendaftarans'));
    }

    public function show(Pendaftaran $pendaftaran): View
    {
        return view('admin.ppdb.show', compact('pendaftaran'));
    }

    public function updateStatus(Request $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $request->validate(['status' => 'required|in:menunggu,diproses,diterima,ditolak,lunas']);
        $pendaftaran->update(['status' => $request->status]);
        return back()->with('sukses', 'Status pendaftar diubah ke '.$request->status.'.');
    }

    public function destroy(Pendaftaran $pendaftaran): RedirectResponse
    {
        $pendaftaran->delete();
        return redirect()->route('admin.pendaftaran.index')->with('sukses', 'Data pendaftar dihapus.');
    }
}
