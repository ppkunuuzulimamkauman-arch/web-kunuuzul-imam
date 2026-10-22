<?php

namespace App\Services;

use App\Models\Alumni;
use App\Models\Pendaftaran;
use App\Models\Santri;
use App\Models\SuratPermohonan;
use App\Support\Nik;
use Illuminate\Support\Facades\DB;

class SantriService
{
    /**
     * Ubah pendaftar PSB yang diterima menjadi santri, atau hubungkan ke
     * santri yang sudah ada berdasarkan NIK. Operasi ini idempoten.
     */
    public function fromPendaftaran(Pendaftaran $pendaftaran): Santri
    {
        return DB::transaction(function () use ($pendaftaran) {
            $pendaftaran->loadMissing('santri');

            if ($pendaftaran->santri) {
                return $pendaftaran->santri;
            }

            $nik = Nik::normalize($pendaftaran->nik);
            $nikValid = $nik && Nik::isValid($nik) ? $nik : null;

            $santri = $nikValid ? Santri::where('nik', $nikValid)->first() : null;

            if (! $santri) {
                $santri = Santri::create([
                    'nik' => $nikValid,
                    'nama' => $pendaftaran->nama_lengkap,
                    'jenis_kelamin' => in_array($pendaftaran->jenis_kelamin, ['L', 'P'], true)
                        ? $pendaftaran->jenis_kelamin
                        : null,
                    'tempat_lahir' => $pendaftaran->tempat_lahir,
                    'tanggal_lahir' => $pendaftaran->tanggal_lahir,
                    'alamat' => $pendaftaran->alamat,
                    'no_hp' => $pendaftaran->no_hp ?: $pendaftaran->hp_wali,
                    'asal_sekolah' => $pendaftaran->asal_sekolah,
                    'nama_wali' => $pendaftaran->nama_wali,
                    'hp_wali' => $pendaftaran->hp_wali,
                    'status' => 'aktif',
                    'tanggal_masuk' => $pendaftaran->tanggal_pengisian?->toDateString() ?? now()->toDateString(),
                ]);
            }

            $pendaftaran->forceFill(['santri_id' => $santri->id])->save();

            return $santri;
        });
    }

    public function linkByNik(?string $nik): ?Santri
    {
        $nik = Nik::normalize($nik);

        if (! $nik || ! Nik::isValid($nik)) {
            return null;
        }

        return Santri::where('nik', $nik)->first();
    }

    public function linkAlumni(Alumni $alumni): ?Santri
    {
        if ($alumni->santri_id) {
            return $alumni->santri;
        }

        $santri = $this->linkByNik($alumni->nik);

        if ($santri) {
            $alumni->forceFill(['santri_id' => $santri->id])->save();
        }

        return $santri;
    }

    public function linkSurat(SuratPermohonan $surat): ?Santri
    {
        if ($surat->santri_id) {
            return $surat->santri;
        }

        $santri = $this->linkByNik($surat->nik);

        if ($santri) {
            $surat->forceFill(['santri_id' => $santri->id])->save();
        }

        return $santri;
    }
}
