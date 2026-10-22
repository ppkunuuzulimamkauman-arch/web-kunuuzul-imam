<?php

namespace App\Http\Controllers\Admin\Siakad;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\AkademikKelas;
use App\Models\AkademikTahunAjaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KelasController extends Controller
{
    public function index(Request $request): View
    {
        $tahunAjaranId = $request->query('tahun_ajaran_id');
        $q = trim((string) $request->query('q'));

        $kelas = AkademikKelas::with('tahunAjaran')
            ->withCount('santris')
            ->when($tahunAjaranId, fn ($query) => $query->where('tahun_ajaran_id', $tahunAjaranId))
            ->when($q !== '', fn ($query) => $query->where('nama', 'like', "%{$q}%"))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $tahunAjarans = AkademikTahunAjaran::orderByDesc('mulai')->get();

        return view('admin.siakad.kelas.index', compact('kelas', 'tahunAjarans', 'tahunAjaranId', 'q'));
    }

    public function create(): View
    {
        return view('admin.siakad.kelas.form', [
            'kelas' => new AkademikKelas(),
            'tahunAjarans' => AkademikTahunAjaran::orderByDesc('mulai')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $kelas = AkademikKelas::create($data);

        ActivityLog::catat('siakad', 'tambah-kelas', $kelas, 'Menambahkan kelas '.$kelas->nama.'.', [], $kelas->only(['tahun_ajaran_id', 'nama', 'jenjang', 'tingkat', 'wali_kelas', 'is_aktif']));

        return redirect()->route('admin.siakad.kelas.index')->with('sukses', 'Kelas ditambahkan.');
    }

    public function edit(AkademikKelas $kelas): View
    {
        return view('admin.siakad.kelas.form', [
            'kelas' => $kelas,
            'tahunAjarans' => AkademikTahunAjaran::orderByDesc('mulai')->get(),
        ]);
    }

    public function update(Request $request, AkademikKelas $kelas): RedirectResponse
    {
        $sebelum = $kelas->only(['tahun_ajaran_id', 'nama', 'jenjang', 'tingkat', 'wali_kelas', 'is_aktif']);
        $kelas->update($this->validated($request, $kelas));

        ActivityLog::catat('siakad', 'ubah-kelas', $kelas, 'Memperbarui kelas '.$kelas->nama.'.', $sebelum, $kelas->only(['tahun_ajaran_id', 'nama', 'jenjang', 'tingkat', 'wali_kelas', 'is_aktif']));

        return redirect()->route('admin.siakad.kelas.index')->with('sukses', 'Kelas diperbarui.');
    }

    public function destroy(AkademikKelas $kelas): RedirectResponse
    {
        if ($kelas->santris()->exists()) {
            return back()->withErrors(['kelas' => 'Kelas masih memiliki santri. Pindahkan santri terlebih dahulu.']);
        }

        ActivityLog::catat('siakad', 'hapus-kelas', $kelas, 'Menghapus kelas '.$kelas->nama.'.', $kelas->only(['tahun_ajaran_id', 'nama', 'jenjang', 'tingkat', 'wali_kelas', 'is_aktif']), []);
        $kelas->delete();

        return redirect()->route('admin.siakad.kelas.index')->with('sukses', 'Kelas dihapus.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, ?AkademikKelas $kelas = null): array
    {
        $data = $request->validate([
            'tahun_ajaran_id' => ['required', 'exists:akademik_tahun_ajarans,id'],
            'nama' => [
                'required', 'string', 'max:100',
                Rule::unique('akademik_kelas', 'nama')
                    ->where(fn ($query) => $query->where('tahun_ajaran_id', $request->input('tahun_ajaran_id')))
                    ->ignore($kelas?->id),
            ],
            'jenjang' => ['nullable', 'string', 'max:50'],
            'tingkat' => ['nullable', 'integer', 'min:1', 'max:12'],
            'wali_kelas' => ['nullable', 'string', 'max:150'],
            'is_aktif' => ['sometimes', 'boolean'],
        ], [
            'nama.unique' => 'Nama kelas sudah ada pada tahun ajaran tersebut.',
            'tahun_ajaran_id.exists' => 'Tahun ajaran tidak ditemukan.',
        ]);

        $data['is_aktif'] = $request->boolean('is_aktif');

        return $data;
    }
}
