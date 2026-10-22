<?php

namespace App\Http\Controllers\Admin\Siakad;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\AkademikTahunAjaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TahunAjaranController extends Controller
{
    public function index(): View
    {
        $tahunAjarans = AkademikTahunAjaran::withCount('kelas')
            ->orderByDesc('mulai')
            ->orderByDesc('id')
            ->paginate(12);

        return view('admin.siakad.tahun-ajaran.index', compact('tahunAjarans'));
    }

    public function create(): View
    {
        return view('admin.siakad.tahun-ajaran.form', ['tahunAjaran' => new AkademikTahunAjaran()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $tahunAjaran = DB::transaction(function () use ($data) {
            if (! empty($data['is_aktif'])) {
                AkademikTahunAjaran::query()->update(['is_aktif' => false]);
            }

            return AkademikTahunAjaran::create($data);
        });

        ActivityLog::catat('siakad', 'tambah-tahun-ajaran', $tahunAjaran, 'Menambahkan tahun ajaran '.$tahunAjaran->nama.'.', [], $tahunAjaran->only(['nama', 'mulai', 'selesai', 'is_aktif']));

        return redirect()->route('admin.siakad.tahun-ajaran.index')->with('sukses', 'Tahun ajaran ditambahkan.');
    }

    public function edit(AkademikTahunAjaran $tahunAjaran): View
    {
        return view('admin.siakad.tahun-ajaran.form', compact('tahunAjaran'));
    }

    public function update(Request $request, AkademikTahunAjaran $tahunAjaran): RedirectResponse
    {
        $sebelum = $tahunAjaran->only(['nama', 'mulai', 'selesai', 'is_aktif']);
        $data = $this->validated($request, $tahunAjaran);

        DB::transaction(function () use ($data, $tahunAjaran) {
            if (! empty($data['is_aktif'])) {
                AkademikTahunAjaran::where('id', '!=', $tahunAjaran->id)->update(['is_aktif' => false]);
            }

            $tahunAjaran->update($data);
        });

        ActivityLog::catat('siakad', 'ubah-tahun-ajaran', $tahunAjaran, 'Memperbarui tahun ajaran '.$tahunAjaran->nama.'.', $sebelum, $tahunAjaran->only(['nama', 'mulai', 'selesai', 'is_aktif']));

        return redirect()->route('admin.siakad.tahun-ajaran.index')->with('sukses', 'Tahun ajaran diperbarui.');
    }

    public function destroy(AkademikTahunAjaran $tahunAjaran): RedirectResponse
    {
        if ($tahunAjaran->kelas()->exists()) {
            return back()->withErrors(['tahun_ajaran' => 'Tahun ajaran masih memiliki kelas. Hapus kelas terlebih dahulu.']);
        }

        ActivityLog::catat('siakad', 'hapus-tahun-ajaran', $tahunAjaran, 'Menghapus tahun ajaran '.$tahunAjaran->nama.'.', $tahunAjaran->only(['nama', 'mulai', 'selesai', 'is_aktif']), []);
        $tahunAjaran->delete();

        return redirect()->route('admin.siakad.tahun-ajaran.index')->with('sukses', 'Tahun ajaran dihapus.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, ?AkademikTahunAjaran $tahunAjaran = null): array
    {
        $rules = [
            'nama' => ['required', 'string', 'max:50', Rule::unique('akademik_tahun_ajarans', 'nama')->ignore($tahunAjaran?->id)],
            'mulai' => ['nullable', 'date'],
            'selesai' => ['nullable', 'date'],
            'is_aktif' => ['sometimes', 'boolean'],
        ];

        if ($request->filled('mulai')) {
            $rules['selesai'][] = 'after_or_equal:mulai';
        }

        $data = $request->validate($rules, [
            'nama.unique' => 'Nama tahun ajaran sudah ada.',
            'selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        $data['is_aktif'] = $request->boolean('is_aktif');

        return $data;
    }
}
