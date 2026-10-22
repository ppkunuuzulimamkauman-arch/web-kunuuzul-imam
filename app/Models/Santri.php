<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Santri extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis',
        'nik',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'email',
        'asal_sekolah',
        'nama_wali',
        'hp_wali',
        'status',
        'tanggal_masuk',
        'tanggal_lulus',
        'foto',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'tanggal_lulus' => 'date',
    ];

    public function pendaftaran(): HasOne
    {
        return $this->hasOne(Pendaftaran::class);
    }

    public function alumni(): HasOne
    {
        return $this->hasOne(Alumni::class);
    }

    public function suratPermohonan(): HasMany
    {
        return $this->hasMany(SuratPermohonan::class);
    }

    public function kelas(): BelongsToMany
    {
        return $this->belongsToMany(AkademikKelas::class, 'akademik_kelas_santri', 'santri_id', 'kelas_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function nilais(): HasMany
    {
        return $this->hasMany(AkademikNilai::class);
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(AkademikAbsensi::class);
    }

    /** @return array<string, string> */
    public static function statusOptions(): array
    {
        return [
            'aktif' => 'Aktif',
            'lulus' => 'Lulus',
            'keluar' => 'Keluar',
            'nonaktif' => 'Nonaktif',
        ];
    }

    public function statusLabel(): string
    {
        return self::statusOptions()[$this->status] ?? ucfirst((string) $this->status);
    }

    public function statusPillClass(): string
    {
        return match ($this->status) {
            'aktif' => 'p-green',
            'lulus' => 'p-blue',
            'keluar' => 'p-gold',
            default => 'p-gray',
        };
    }

    public function jenisKelaminLabel(): ?string
    {
        return match ($this->jenis_kelamin) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => null,
        };
    }
}
