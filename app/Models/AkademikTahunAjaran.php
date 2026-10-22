<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AkademikTahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'akademik_tahun_ajarans';

    protected $fillable = ['nama', 'mulai', 'selesai', 'is_aktif'];

    protected $casts = [
        'mulai' => 'date',
        'selesai' => 'date',
        'is_aktif' => 'boolean',
    ];

    public function kelas(): HasMany
    {
        return $this->hasMany(AkademikKelas::class, 'tahun_ajaran_id');
    }

    public function nilais(): HasMany
    {
        return $this->hasMany(AkademikNilai::class, 'tahun_ajaran_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }
}
