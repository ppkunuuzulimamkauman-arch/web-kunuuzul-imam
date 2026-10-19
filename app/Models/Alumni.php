<?php

namespace App\Models;

use App\Concerns\DeletesStoredFiles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    use HasFactory, DeletesStoredFiles;

    public const STATUS_PENDING = 'menunggu';
    public const STATUS_APPROVED = 'disetujui';
    public const STATUS_REJECTED = 'ditolak';

    protected $table = 'alumnis';

    protected static array $storedFiles = ['foto'];

    protected $fillable = [
        'nama',
        'nik',
        'angkatan',
        'tahun_lulus',
        'lembaga',
        'domisili',
        'pekerjaan',
        'no_hp',
        'email',
        'foto',
        'tentang',
        'status',
        'catatan_admin',
        'is_tampil',
    ];

    protected $casts = [
        'angkatan' => 'integer',
        'tahun_lulus' => 'integer',
        'is_tampil' => 'boolean',
    ];

    /** Hanya data yang sudah disetujui dan boleh tampil di direktori publik. */
    public function scopeTampil(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_APPROVED)->where('is_tampil', true);
    }

    public function isPubliclyVisible(): bool
    {
        return $this->status === self::STATUS_APPROVED && (bool) $this->is_tampil;
    }

    /** @return array<string, string> */
    public static function statusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
        ];
    }

    public function statusLabel(): string
    {
        return self::statusOptions()[$this->status] ?? ucfirst((string) $this->status);
    }

    public function statusPillClass(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'p-gold',
            self::STATUS_REJECTED => 'p-red',
            self::STATUS_APPROVED => 'p-green',
            default => 'p-gray',
        };
    }

    public function angkatanLabel(): string
    {
        $parts = [];
        if ($this->angkatan) {
            $parts[] = 'Angkatan '.$this->angkatan;
        }
        if ($this->tahun_lulus) {
            $parts[] = 'Lulus '.$this->tahun_lulus;
        }

        return implode(' - ', $parts);
    }

    public function kodeAlumni(): string
    {
        return 'ALM-'.str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }

    /** @return array<int, string> */
    public static function lembagaOptions(): array
    {
        $defaults = ['MTs', 'MA', 'Madrasah Diniyah', 'Tahfidz', "Ma'had Aly"];

        try {
            $lembaga = Lembaga::query()
                ->where('is_aktif', true)
                ->orderBy('urutan')
                ->pluck('nama')
                ->all();
        } catch (\Throwable $e) {
            $lembaga = [];
        }

        return self::uniqueOptions(array_merge($defaults, $lembaga, self::existingValues('lembaga')));
    }

    /** @return array<int, string> */
    public static function pekerjaanOptions(): array
    {
        $defaults = [
            'Pelajar/Mahasiswa',
            'Guru/Dosen',
            'PNS/ASN',
            'TNI/Polri',
            'Karyawan Swasta',
            'Wirausaha',
            'Petani/Peternak',
            'Nelayan',
            'Buruh',
            'Ibu Rumah Tangga',
            'Belum Bekerja',
        ];

        return self::uniqueOptions(array_merge($defaults, self::existingValues('pekerjaan')));
    }

    /** @return array<int, string> */
    public static function domisiliOptions(): array
    {
        $defaults = [
            'Jombang', 'Surabaya', 'Malang', 'Kediri', 'Sidoarjo', 'Gresik',
            'Mojokerto', 'Blitar', 'Madiun', 'Jakarta', 'Bandung', 'Semarang',
            'Yogyakarta', 'Denpasar', 'Makassar', 'Medan', 'Palembang',
            'Banjarmasin', 'Pontianak', 'Manado', 'Mataram', 'Bekasi', 'Depok',
            'Tangerang', 'Bogor',
        ];

        return self::uniqueOptions(array_merge($defaults, self::existingValues('domisili')));
    }

    /** @return array<int, string> */
    private static function existingValues(string $column): array
    {
        try {
            return self::query()
                ->whereNotNull($column)
                ->where($column, '!=', '')
                ->distinct()
                ->orderBy($column)
                ->pluck($column)
                ->all();
        } catch (\Throwable $e) {
            return [];
        }
    }

    /** @param array<int, mixed> $options */
    private static function uniqueOptions(array $options): array
    {
        $options = array_map(fn ($option) => trim((string) $option), $options);

        return array_values(array_unique(array_filter($options, fn ($option) => $option !== '')));
    }

    public function fotoUrl(): ?string
    {
        if (! $this->foto) {
            return null;
        }

        if (str_starts_with($this->foto, 'http') || str_starts_with($this->foto, '/')) {
            return $this->foto;
        }

        return asset('storage/'.$this->foto);
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
