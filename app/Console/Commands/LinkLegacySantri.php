<?php

namespace App\Console\Commands;

use App\Models\Alumni;
use App\Models\Pendaftaran;
use App\Models\SuratPermohonan;
use App\Services\SantriService;
use Illuminate\Console\Command;

class LinkLegacySantri extends Command
{
    protected $signature = 'siakad:link-legacy {--dry-run : Hitung kandidat tanpa menyimpan perubahan}';

    protected $description = 'Hubungkan data PSB, alumni, dan surat lama ke identitas santri berdasarkan NIK.';

    public function handle(SantriService $santriService): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $stats = [
            'pendaftaran' => 0,
            'alumni' => 0,
            'surat' => 0,
            'gagal' => 0,
        ];

        Pendaftaran::whereIn('status', ['diterima', 'lunas'])
            ->whereNull('santri_id')
            ->orderBy('id')
            ->each(function (Pendaftaran $pendaftaran) use ($santriService, $dryRun, &$stats) {
                if ($dryRun) {
                    $stats['pendaftaran']++;

                    return;
                }

                try {
                    if ($santriService->fromPendaftaran($pendaftaran)) {
                        $stats['pendaftaran']++;
                    }
                } catch (\Throwable $e) {
                    $stats['gagal']++;
                    $this->warn("PSB #{$pendaftaran->id}: {$e->getMessage()}");
                }
            });

        Alumni::whereNull('santri_id')
            ->whereNotNull('nik')
            ->orderBy('id')
            ->each(function (Alumni $alumni) use ($santriService, $dryRun, &$stats) {
                if ($dryRun) {
                    $stats['alumni']++;

                    return;
                }

                try {
                    if ($santriService->linkAlumni($alumni)) {
                        $stats['alumni']++;
                    }
                } catch (\Throwable $e) {
                    $stats['gagal']++;
                    $this->warn("Alumni #{$alumni->id}: {$e->getMessage()}");
                }
            });

        SuratPermohonan::whereNull('santri_id')
            ->whereNotNull('nik')
            ->orderBy('id')
            ->each(function (SuratPermohonan $surat) use ($santriService, $dryRun, &$stats) {
                if ($dryRun) {
                    $stats['surat']++;

                    return;
                }

                try {
                    if ($santriService->linkSurat($surat)) {
                        $stats['surat']++;
                    }
                } catch (\Throwable $e) {
                    $stats['gagal']++;
                    $this->warn("Surat #{$surat->id}: {$e->getMessage()}");
                }
            });

        $label = $dryRun ? 'Kandidat' : 'Berhasil';
        $this->info("{$label} terhubung — PSB: {$stats['pendaftaran']}, Alumni: {$stats['alumni']}, Surat: {$stats['surat']}, Gagal: {$stats['gagal']}.");

        if ($dryRun) {
            $this->line('Jalankan tanpa --dry-run untuk menyimpan perubahan.');
        }

        return self::SUCCESS;
    }
}
