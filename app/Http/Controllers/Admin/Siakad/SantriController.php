<?php

namespace App\Http\Controllers\Admin\Siakad;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Santri;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SantriController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q'));
        $status = (string) $request->query('status');

        $santris = Santri::query()
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nama', 'like', "%{$q}%")
                        ->orWhere('nis', 'like', "%{$q}%")
                        ->orWhere('nik', 'like', "%{$q}%")
                        ->orWhere('asal_sekolah', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = [
            '' => Santri::count(),
            'aktif' => Santri::where('status', 'aktif')->count(),
            'lulus' => Santri::where('status', 'lulus')->count(),
            'keluar' => Santri::where('status', 'keluar')->count(),
            'nonaktif' => Santri::where('status', 'nonaktif')->count(),
        ];

        return view('admin.siakad.santri.index', compact('santris', 'q', 'status', 'counts'));
    }

    public function create(): View
    {
        return view('admin.siakad.santri.form', ['santri' => new Santri()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $santri = Santri::create($data);

        ActivityLog::catat(
            'siakad',
            'tambah-santri',
            $santri,
            'Menambahkan data santri '.$santri->nama.'.',
            [],
            $santri->only($this->logFields())
        );

        return redirect()->route('admin.siakad.santri.index')->with('sukses', 'Data santri ditambahkan.');
    }

    public function edit(Santri $santri): View
    {
        return view('admin.siakad.santri.form', compact('santri'));
    }

    public function update(Request $request, Santri $santri): RedirectResponse
    {
        $sebelum = $santri->only($this->logFields());
        $data = $this->validated($request, $santri);
        $santri->update($data);

        ActivityLog::catat(
            'siakad',
            'ubah-santri',
            $santri,
            'Memperbarui data santri '.$santri->nama.'.',
            $sebelum,
            $santri->only($this->logFields())
        );

        return redirect()->route('admin.siakad.santri.index')->with('sukses', 'Data santri diperbarui.');
    }

    public function destroy(Santri $santri): RedirectResponse
    {
        ActivityLog::catat(
            'siakad',
            'hapus-santri',
            $santri,
            'Menghapus data santri '.$santri->nama.'.',
            $santri->only($this->logFields()),
            []
        );

        $santri->delete();

        return redirect()->route('admin.siakad.santri.index')->with('sukses', 'Data santri dihapus.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, ?Santri $santri = null): array
    {
        $rules = [
            'nama' => ['required', 'string', 'max:150'],
            'nis' => ['nullable', 'string', 'max:30', Rule::unique('santris', 'nis')->ignore($santri?->id)],
            'nik' => ['nullable', 'digits:16', Rule::unique('santris', 'nik')->ignore($santri?->id)],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date', 'before_or_equal:today'],
            'alamat' => ['nullable', 'string', 'max:1000'],
            'no_hp' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]{9,20}$/'],
            'email' => ['nullable', 'email', 'max:150'],
            'asal_sekolah' => ['nullable', 'string', 'max:150'],
            'nama_wali' => ['nullable', 'string', 'max:150'],
            'hp_wali' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]{9,20}$/'],
            'status' => ['required', 'in:aktif,lulus,keluar,nonaktif'],
            'tanggal_masuk' => ['nullable', 'date'],
            'tanggal_lulus' => ['nullable', 'date'],
        ];

        if ($request->filled('tanggal_masuk')) {
            $rules['tanggal_lulus'][] = 'after_or_equal:tanggal_masuk';
        }

        $data = $request->validate($rules, [
            'nik.digits' => 'NIK harus 16 digit angka.',
            'nik.unique' => 'NIK sudah terdaftar pada data santri.',
            'nis.unique' => 'NIS sudah terdaftar pada data santri.',
            'no_hp.regex' => 'Nomor HP tidak valid.',
            'hp_wali.regex' => 'Nomor HP wali tidak valid.',
            'tanggal_lulus.after_or_equal' => 'Tanggal lulus tidak boleh sebelum tanggal masuk.',
        ]);

        $data['nik'] = ($data['nik'] ?? null) ?: null;
        $data['nis'] = ($data['nis'] ?? null) ?: null;

        return $data;
    }

    /** @return array<int, string> */
    private function logFields(): array
    {
        return ['nis', 'nik', 'nama', 'jenis_kelamin', 'status', 'tanggal_masuk', 'tanggal_lulus'];
    }
}
