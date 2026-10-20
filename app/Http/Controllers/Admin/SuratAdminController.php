<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratPermohonan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuratAdminController extends Controller
{
    public function index(Request $request): View
    {
        $permohonans = SuratPermohonan::query()
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('jenis'), fn ($q) => $q->where('jenis', $request->jenis))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = trim((string) $request->q);
                $q->where(fn ($w) => $w
                    ->where('kode', 'like', "%{$term}%")
                    ->orWhere('nama', 'like', "%{$term}%")
                    ->orWhere('no_hp', 'like', "%{$term}%"));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $perStatus = SuratPermohonan::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $stats = [
            'total' => (int) $perStatus->sum(),
            'menunggu' => (int) ($perStatus[SuratPermohonan::STATUS_MENUNGGU] ?? 0),
            'diproses' => (int) ($perStatus[SuratPermohonan::STATUS_DIPROSES] ?? 0),
            'selesai' => (int) ($perStatus[SuratPermohonan::STATUS_SELESAI] ?? 0),
            'ditolak' => (int) ($perStatus[SuratPermohonan::STATUS_DITOLAK] ?? 0),
        ];

        return view('admin.surat.index', [
            'permohonans' => $permohonans,
            'stats' => $stats,
            'jenisOptions' => SuratPermohonan::jenisOptions(),
        ]);
    }

    public function show(SuratPermohonan $permohonan): View
    {
        return view('admin.surat.show', [
            'permohonan' => $permohonan,
            'statusOptions' => SuratPermohonan::statusOptions(),
        ]);
    }

    public function update(Request $request, SuratPermohonan $permohonan): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(SuratPermohonan::statusOptions()))],
            'nomor_surat' => ['nullable', 'string', 'max:100'],
            'catatan_admin' => ['nullable', 'string', 'max:2000'],
            'file_hasil' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:8192'],
        ], [
            'file_hasil.mimes' => 'Berkas surat harus berupa PDF, JPG, atau PNG.',
            'file_hasil.max' => 'Ukuran berkas surat maksimal 8 MB.',
        ]);

        if ($request->hasFile('file_hasil')) {
            $permohonan->deleteStoredFile($permohonan->getRawOriginal('file_hasil'));
            $data['file_hasil'] = $request->file('file_hasil')->store('surat/hasil', 'public');
        }

        $data['selesai_at'] = $data['status'] === SuratPermohonan::STATUS_SELESAI
            ? ($permohonan->selesai_at ?? now())
            : null;

        $permohonan->update($data);

        return back()->with('sukses', 'Permohonan '.$permohonan->kode.' berhasil diperbarui.');
    }

    public function destroy(SuratPermohonan $permohonan): RedirectResponse
    {
        $kode = $permohonan->kode;
        $permohonan->delete();

        return redirect()->route('admin.surat.index')->with('sukses', 'Permohonan '.$kode.' dihapus.');
    }

    public function export(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $rows = SuratPermohonan::query()
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('jenis'), fn ($q) => $q->where('jenis', $request->jenis))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = trim((string) $request->q);
                $q->where(fn ($w) => $w
                    ->where('kode', 'like', "%{$term}%")
                    ->orWhere('nama', 'like', "%{$term}%")
                    ->orWhere('no_hp', 'like', "%{$term}%"));
            })
            ->orderBy('created_at')
            ->get();

        $filename = 'surat-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['kode', 'jenis', 'nama', 'nik', 'no_hp', 'email', 'lembaga', 'keperluan', 'status', 'nomor_surat', 'diajukan', 'selesai']);
            foreach ($rows as $row) {
                fputcsv($out, [
                    $row->kode,
                    $row->jenisLabel(),
                    $row->nama,
                    $row->nik,
                    $row->no_hp,
                    $row->email,
                    $row->lembaga,
                    str_replace(["\r", "\n"], ' ', (string) $row->keperluan),
                    $row->statusLabel(),
                    $row->nomor_surat,
                    $row->created_at?->format('Y-m-d H:i'),
                    $row->selesai_at?->format('Y-m-d H:i'),
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
