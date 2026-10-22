<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Galeri;
use App\Models\HeroSlide;
use App\Models\Lembaga;
use App\Models\Pendaftaran;
use App\Models\Pesan;
use App\Models\Pengasuh;
use App\Models\Program;
use App\Models\Setting;
use App\Services\SantriService;
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

    public function updateStatus(Request $request, Pendaftaran $pendaftaran, SantriService $santriService): RedirectResponse
    {
        $request->validate(['status' => 'required|in:menunggu,diproses,diterima,ditolak,lunas']);

        $sebelum = $pendaftaran->only(['status', 'santri_id']);
        $pendaftaran->update(['status' => $request->status]);

        if (in_array($request->status, ['diterima', 'lunas'], true)) {
            $santriService->fromPendaftaran($pendaftaran);
            $pendaftaran->refresh();
        }

        ActivityLog::catat(
            'psb',
            'ubah-status',
            $pendaftaran,
            'Status pendaftar diubah menjadi '.$request->status.'.',
            $sebelum,
            $pendaftaran->only(['status', 'santri_id'])
        );

        return back()->with('sukses', 'Status pendaftar diubah ke '.$request->status.'.');
    }

    public function destroy(Pendaftaran $pendaftaran): RedirectResponse
    {
        $pendaftaran->delete();
        return redirect()->route('admin.pendaftaran.index')->with('sukses', 'Data pendaftar dihapus.');
    }

    public function export(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $rows = Pendaftaran::when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->q, fn ($q) => $q->where(fn ($w) => $w->where('nama_lengkap', 'like', '%'.$request->q.'%')->orWhere('nomor_pendaftaran', 'like', '%'.$request->q.'%')))
            ->orderBy('created_at')->get();
        $filename = 'ppdb-'.now()->format('Ymd-His').'.csv';
        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['nomor', 'nama', 'nik', 'jk', 'ttl', 'alamat', 'ayah', 'ibu', 'hp', 'jenjang', 'asal_sekolah', 'status', 'dibuat']);
            foreach ($rows as $p) {
                fputcsv($out, [$p->nomor_pendaftaran, $p->nama_lengkap, $p->nik, $p->jenis_kelamin, ($p->tempat_lahir ?? '').', '.($p->tanggal_lahir?->format('Y-m-d') ?? ''), $p->alamat, $p->nama_ayah ?? $p->nama_wali, $p->nama_ibu, $p->no_hp ?? $p->hp_wali, $p->jenjang, $p->asal_sekolah, $p->status, $p->created_at?->format('Y-m-d H:i')]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
