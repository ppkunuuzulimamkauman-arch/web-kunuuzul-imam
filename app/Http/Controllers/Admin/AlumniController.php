<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Support\Nik;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AlumniController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q'));
        $angkatan = $request->query('angkatan');
        $status = $request->query('status');

        $alumnis = Alumni::query()
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nama', 'like', "%{$q}%")
                        ->orWhere('nik', 'like', "%{$q}%")
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

        $counts = [
            '' => Alumni::count(),
            Alumni::STATUS_PENDING => Alumni::where('status', Alumni::STATUS_PENDING)->count(),
            Alumni::STATUS_APPROVED => Alumni::where('status', Alumni::STATUS_APPROVED)->count(),
            Alumni::STATUS_REJECTED => Alumni::where('status', Alumni::STATUS_REJECTED)->count(),
        ];

        return view('admin.alumni.index', compact('alumnis', 'angkatans', 'q', 'angkatan', 'status', 'counts'));
    }

    public function export(Request $request): StreamedResponse
    {
        $q = trim((string) $request->query('q'));
        $angkatan = $request->query('angkatan');
        $status = $request->query('status');

        $rows = Alumni::query()
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nama', 'like', "%{$q}%")
                        ->orWhere('nik', 'like', "%{$q}%")
                        ->orWhere('lembaga', 'like', "%{$q}%")
                        ->orWhere('domisili', 'like', "%{$q}%")
                        ->orWhere('pekerjaan', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when($angkatan, fn ($query) => $query->where('angkatan', $angkatan))
            ->orderByDesc('angkatan')
            ->orderBy('nama')
            ->get();

        $filename = 'alumni-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8 dengan benar
            fputcsv($out, ['Nama', 'NIK', 'Angkatan', 'Tahun Lulus', 'Lembaga', 'Domisili', 'Pekerjaan', 'No. HP', 'Email', 'Status', 'Tampil', 'Tentang', 'Catatan Admin', 'Dibuat']);
            foreach ($rows as $a) {
                fputcsv($out, [
                    $a->nama,
                    $a->nik,
                    $a->angkatan,
                    $a->tahun_lulus,
                    $a->lembaga,
                    $a->domisili,
                    $a->pekerjaan,
                    $a->no_hp,
                    $a->email,
                    $a->statusLabel(),
                    $a->is_tampil ? 'ya' : 'tidak',
                    $a->tentang,
                    $a->catatan_admin,
                    $a->created_at?->format('Y-m-d H:i'),
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function importForm(): View
    {
        return view('admin.alumni.import');
    }

    public function importTemplate(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Nama', 'NIK', 'Angkatan', 'Tahun Lulus', 'Lembaga', 'Domisili', 'Pekerjaan', 'No. HP', 'Email', 'Status', 'Tampil', 'Tentang', 'Catatan Admin']);
            fclose($out);
        }, 'template-import-alumni.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|max:2048',
        ], [
            'file.required' => 'Pilih file CSV terlebih dahulu.',
            'file.max' => 'Ukuran file maksimal 2MB.',
        ]);

        $upload = $request->file('file');
        if (! in_array(strtolower($upload->getClientOriginalExtension()), ['csv', 'txt'], true)) {
            return back()->withErrors(['file' => 'File harus berformat CSV.']);
        }

        $handle = fopen($upload->getRealPath(), 'r');
        if (! $handle) {
            return back()->withErrors(['file' => 'File tidak bisa dibaca.']);
        }

        // Deteksi pemisah: koma (CSV standar) atau titik koma (Excel lokal Indonesia).
        $firstLine = fgets($handle);
        $delimiter = ',';
        if ($firstLine !== false) {
            $delimiter = substr_count($firstLine, ';') > substr_count($firstLine, ',') ? ';' : ',';
            rewind($handle);
        }

        $header = fgetcsv($handle, null, $delimiter, '"', '');
        if (! $header) {
            fclose($handle);

            return back()->withErrors(['file' => 'File CSV kosong.']);
        }

        $header = array_map(fn ($h) => $this->normalizeCsvHeader((string) $h), $header);
        if (! in_array('nama', $header, true)) {
            fclose($handle);

            return back()->withErrors(['file' => 'Header CSV tidak dikenali. Gunakan template import.']);
        }

        $index = array_flip($header);
        $imported = 0;
        $skipped = 0;
        $errors = [];
        $rowNumber = 1;

        while (($row = fgetcsv($handle, null, $delimiter, '"', '')) !== false) {
            $rowNumber++;

            if ($rowNumber > 2001) {
                $errors[] = 'Maksimal 2000 baris per import.';
                break;
            }

            $row = array_map(fn ($v) => $this->cleanCsvValue((string) $v), $row);
            if (count(array_filter($row, fn ($v) => $v !== '')) === 0) {
                continue;
            }

            $get = function (string $key) use ($row, $index): string {
                return isset($index[$key], $row[$index[$key]]) ? trim((string) $row[$index[$key]]) : '';
            };

            $nama = $get('nama');
            if ($nama === '') {
                $errors[] = "Baris {$rowNumber}: nama kosong, dilewati.";
                $skipped++;
                continue;
            }

            if (mb_strlen($nama) > 150) {
                $errors[] = "Baris {$rowNumber}: nama terlalu panjang (maks 150 karakter), dilewati.";
                $skipped++;
                continue;
            }

            $nik = Nik::normalize($get('nik'));
            if ($nik !== null && ! Nik::isValid($nik)) {
                $errors[] = "Baris {$rowNumber}: NIK tidak valid, dilewati.";
                $skipped++;
                continue;
            }

            $email = $get('email');
            if ($email !== '' && (mb_strlen($email) > 150 || ! filter_var($email, FILTER_VALIDATE_EMAIL))) {
                $errors[] = "Baris {$rowNumber}: email tidak valid ({$email}), dilewati.";
                $skipped++;
                continue;
            }

            $noHp = $get('no_hp');
            if (mb_strlen($noHp) > 30) {
                $errors[] = "Baris {$rowNumber}: no. HP terlalu panjang (maks 30 karakter), dilewati.";
                $skipped++;
                continue;
            }

            $angkatan = $this->parseCsvYear($get('angkatan'));
            $tahunLulus = $this->parseCsvYear($get('tahun_lulus'));
            $status = $this->parseCsvStatus($get('status'));
            $isTampil = $status === Alumni::STATUS_APPROVED
                ? $this->parseCsvBool($get('is_tampil'), true)
                : false;

            if ($nik !== null && Alumni::where('nik', $nik)->exists()) {
                $skipped++;
                continue;
            }

            $duplicate = Alumni::where('nama', $nama);
            if ($angkatan) {
                $duplicate->where('angkatan', $angkatan);
            } else {
                $duplicate->whereNull('angkatan');
            }
            if ($duplicate->exists()) {
                $skipped++;
                continue;
            }

            Alumni::create([
                'nama' => $nama,
                'nik' => $nik,
                'angkatan' => $angkatan,
                'tahun_lulus' => $tahunLulus,
                'lembaga' => mb_substr($get('lembaga'), 0, 150) ?: null,
                'domisili' => mb_substr($get('domisili'), 0, 150) ?: null,
                'pekerjaan' => mb_substr($get('pekerjaan'), 0, 150) ?: null,
                'no_hp' => $noHp ?: null,
                'email' => $email ?: null,
                'tentang' => mb_substr($get('tentang'), 0, 2000) ?: null,
                'catatan_admin' => mb_substr($get('catatan_admin'), 0, 1000) ?: null,
                'status' => $status,
                'is_tampil' => $isTampil,
            ]);

            $imported++;
        }

        fclose($handle);

        return redirect()->route('admin.alumni.import')
            ->with('sukses', "Import selesai: {$imported} data ditambahkan, {$skipped} dilewati.")
            ->with('import_errors', array_slice($errors, 0, 20));
    }

    public function create(): View
    {
        return view('admin.alumni.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_tampil'] = $data['status'] === Alumni::STATUS_APPROVED && $request->boolean('is_tampil');

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
        $data = $this->validated($request, $alumni);
        $data['is_tampil'] = $data['status'] === Alumni::STATUS_APPROVED && $request->boolean('is_tampil');

        if ($request->hasFile('foto')) {
            $alumni->deleteStoredFile($alumni->foto);
            $data['foto'] = $request->file('foto')->store('alumni', 'public');
        } else {
            unset($data['foto']);
        }

        $alumni->update($data);

        return redirect()->route('admin.alumni.index')->with('sukses', 'Data alumni diperbarui.');
    }

    public function approve(Alumni $alumni): RedirectResponse
    {
        $alumni->update([
            'status' => Alumni::STATUS_APPROVED,
            'is_tampil' => true,
            'catatan_admin' => null,
        ]);

        return back()->with('sukses', 'Pendaftaran alumni disetujui dan tampil di direktori.');
    }

    public function reject(Request $request, Alumni $alumni): RedirectResponse
    {
        $request->validate([
            'catatan_admin' => 'nullable|string|max:1000',
        ]);

        $data = [
            'status' => Alumni::STATUS_REJECTED,
            'is_tampil' => false,
        ];

        if ($request->filled('catatan_admin')) {
            $data['catatan_admin'] = $request->input('catatan_admin');
        }

        $alumni->update($data);

        return back()->with('sukses', 'Pendaftaran alumni ditolak.');
    }

    public function destroy(Alumni $alumni): RedirectResponse
    {
        $alumni->delete();

        return back()->with('sukses', 'Data alumni dihapus.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, ?Alumni $alumni = null): array
    {
        if ($request->filled('nik')) {
            $request->merge([
                'nik' => preg_replace('/\D+/', '', (string) $request->input('nik')),
            ]);
        }

        $uniqueNik = Rule::unique('alumnis', 'nik');
        if ($alumni) {
            $uniqueNik->ignore($alumni->id);
        }

        $data = $request->validate([
            'nama' => 'required|string|max:150',
            'nik' => ['nullable', 'digits:16', $uniqueNik],
            'angkatan' => 'nullable|integer|min:1900|max:2100',
            'tahun_lulus' => 'nullable|integer|min:1900|max:2100',
            'lembaga' => 'nullable|string|max:150',
            'domisili' => 'nullable|string|max:150',
            'pekerjaan' => 'nullable|string|max:150',
            'no_hp' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'tentang' => 'nullable|string',
            'status' => 'required|in:menunggu,disetujui,ditolak',
            'catatan_admin' => 'nullable|string|max:1000',
            'foto' => 'nullable|image|max:2048',
        ]);

        if (! empty($data['nik']) && ! Nik::isValid($data['nik'])) {
            throw ValidationException::withMessages(['nik' => 'NIK tidak valid.']);
        }

        return $data;
    }

    private function normalizeCsvHeader(string $header): string
    {
        $header = strtolower($this->cleanCsvValue($header));
        $header = (string) preg_replace('/[^a-z0-9]+/', '_', $header);
        $header = trim($header, '_');

        return match ($header) {
            'no_hp_wa', 'no_hp_whatsapp', 'no_whatsapp', 'hp', 'whatsapp' => 'no_hp',
            'tampil' => 'is_tampil',
            'tentang_catatan' => 'tentang',
            default => $header,
        };
    }

    private function cleanCsvValue(string $value): string
    {
        $value = preg_replace('/^\xEF\xBB\xBF/', '', $value) ?? $value;
        $value = trim($value);

        if ($value !== '' && ! mb_check_encoding($value, 'UTF-8')) {
            $value = mb_convert_encoding($value, 'UTF-8', 'Windows-1252');
        }

        return $value;
    }

    private function parseCsvYear(string $value): ?int
    {
        $value = trim($value);
        if ($value === '' || ! ctype_digit($value)) {
            return null;
        }

        $year = (int) $value;

        return ($year >= 1900 && $year <= 2100) ? $year : null;
    }

    private function parseCsvStatus(string $value): string
    {
        $value = strtolower(trim($value));

        return match (true) {
            in_array($value, ['menunggu', 'pending', 'waiting'], true) => Alumni::STATUS_PENDING,
            in_array($value, ['ditolak', 'rejected', 'reject'], true) => Alumni::STATUS_REJECTED,
            default => Alumni::STATUS_APPROVED,
        };
    }

    private function parseCsvBool(string $value, bool $default): bool
    {
        $value = strtolower(trim($value));
        if ($value === '') {
            return $default;
        }
        if (in_array($value, ['1', 'ya', 'yes', 'true', 'y', 'tampil'], true)) {
            return true;
        }
        if (in_array($value, ['0', 'tidak', 'no', 'false', 'n', 'sembunyi'], true)) {
            return false;
        }

        return $default;
    }
}
