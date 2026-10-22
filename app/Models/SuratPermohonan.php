<?php

namespace App\Models;

use App\Concerns\DeletesStoredFiles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratPermohonan extends Model
{
    use HasFactory, DeletesStoredFiles;

    public const STATUS_MENUNGGU = 'menunggu';
    public const STATUS_DIPROSES = 'diproses';
    public const STATUS_SELESAI = 'selesai';
    public const STATUS_DITOLAK = 'ditolak';

    protected $table = 'surat_permohonan';

    protected static array $storedFiles = ['lampiran', 'file_hasil'];

    protected $fillable = [
        'santri_id',
        'kode',
        'jenis',
        'jenis_lainnya',
        'nama',
        'nik',
        'no_hp',
        'email',
        'lembaga',
        'alamat',
        'keperluan',
        'tanggal_mulai',
        'tanggal_selesai',
        'tujuan',
        'ditujukan',
        'lampiran',
        'status',
        'nomor_surat',
        'catatan_admin',
        'file_hasil',
        'selesai_at',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'selesai_at' => 'datetime',
    ];

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    /** @return array<string, string> */
    public static function jenisOptions(): array
    {
        return [
            'keterangan_aktif' => 'Surat Keterangan Aktif Santri',
            'izin_pulang' => 'Surat Izin Pulang / Bepergian',
            'rekomendasi' => 'Surat Rekomendasi',
            'pengantar' => 'Surat Pengantar',
            'keterangan_lain' => 'Surat Keterangan Lainnya',
        ];
    }

    /**
     * Metadata tampilan tiap jenis surat (ikon, ringkasan, syarat).
     *
     * @return array<string, array{icon: string, desc: string, syarat: array<int, string>}>
     */
    public static function jenisMeta(): array
    {
        return [
            'keterangan_aktif' => [
                'icon' => 'fa-solid fa-id-card',
                'desc' => 'Bukti resmi bahwa santri masih aktif belajar di pondok.',
                'syarat' => ['Nama lengkap & NIK santri', 'Lembaga / unit asal', 'Keperluan surat'],
            ],
            'izin_pulang' => [
                'icon' => 'fa-solid fa-person-walking-luggage',
                'desc' => 'Permohonan izin meninggalkan pondok untuk keperluan tertentu.',
                'syarat' => ['Tanggal mulai & selesai', 'Tujuan izin', 'Alasan / keperluan'],
            ],
            'rekomendasi' => [
                'icon' => 'fa-solid fa-award',
                'desc' => 'Rekomendasi untuk melanjutkan pendidikan, beasiswa, atau kegiatan.',
                'syarat' => ['Instansi / lembaga tujuan', 'Keperluan rekomendasi', 'Data diri pemohon'],
            ],
            'pengantar' => [
                'icon' => 'fa-solid fa-paper-plane',
                'desc' => 'Surat pengantar kepada instansi atau lembaga tujuan.',
                'syarat' => ['Instansi / lembaga tujuan', 'Keperluan pengantar', 'Data diri pemohon'],
            ],
            'keterangan_lain' => [
                'icon' => 'fa-solid fa-file-lines',
                'desc' => 'Kebutuhan surat lain yang tidak tercantum pada pilihan di atas.',
                'syarat' => ['Sebutkan jenis surat', 'Keperluan surat', 'Data diri pemohon'],
            ],
        ];
    }

    /** @return array<string, string> */
    public static function statusOptions(): array
    {
        return [
            self::STATUS_MENUNGGU => 'Menunggu Verifikasi',
            self::STATUS_DIPROSES => 'Sedang Diproses',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_DITOLAK => 'Ditolak',
        ];
    }

    public function jenisLabel(): string
    {
        $label = self::jenisOptions()[$this->jenis] ?? ucfirst((string) $this->jenis);

        if ($this->jenis === 'keterangan_lain' && $this->jenis_lainnya) {
            return $label.' — '.$this->jenis_lainnya;
        }

        return $label;
    }

    public function jenisIcon(): string
    {
        return self::jenisMeta()[$this->jenis]['icon'] ?? 'fa-solid fa-file-lines';
    }

    public function statusLabel(): string
    {
        return self::statusOptions()[$this->status] ?? ucfirst((string) $this->status);
    }

    public function statusPillClass(): string
    {
        return match ($this->status) {
            self::STATUS_MENUNGGU => 'p-gold',
            self::STATUS_DIPROSES => 'p-blue',
            self::STATUS_SELESAI => 'p-green',
            self::STATUS_DITOLAK => 'p-red',
            default => 'p-gray',
        };
    }

    /** Langkah timeline status yang sudah / sedang dilalui. */
    public function progressStep(): int
    {
        return match ($this->status) {
            self::STATUS_MENUNGGU => 1,
            self::STATUS_DIPROSES => 2,
            self::STATUS_SELESAI => 3,
            self::STATUS_DITOLAK => 2,
            default => 1,
        };
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->whereIn('status', [self::STATUS_MENUNGGU, self::STATUS_DIPROSES]);
    }

    public static function generateKode(): string
    {
        // Hindari karakter mirip (0/O, 1/I) agar mudah dibaca ulang.
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        do {
            $suffix = '';
            for ($i = 0; $i < 6; $i++) {
                $suffix .= $alphabet[random_int(0, strlen($alphabet) - 1)];
            }
            $kode = 'SRT-'.now()->format('ym').'-'.$suffix;
        } while (static::where('kode', $kode)->exists());

        return $kode;
    }

    public function fileHasilUrl(): ?string
    {
        return $this->storedFileUrl($this->file_hasil);
    }

    public function lampiranUrl(): ?string
    {
        return $this->storedFileUrl($this->lampiran);
    }

    private function storedFileUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http') || str_starts_with($path, '/')) {
            return $path;
        }

        return asset('storage/'.$path);
    }

    public function waUrl(): ?string
    {
        if (! $this->no_hp) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $this->no_hp);
        if (! $digits) {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }

        return 'https://wa.me/'.$digits;
    }
}
