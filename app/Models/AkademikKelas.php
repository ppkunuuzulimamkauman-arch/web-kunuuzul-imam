<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AkademikKelas extends Model
{
    use HasFactory;

    protected $table = 'akademik_kelas';

    protected $fillable = [
        'tahun_ajaran_id',
        'nama',
        'jenjang',
        'tingkat',
        'wali_kelas',
        'is_aktif',
    ];

    protected $casts = [
        'tingkat' => 'integer',
        'is_aktif' => 'boolean',
    ];

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(AkademikTahunAjaran::class, 'tahun_ajaran_id');
    }

    public function santris(): BelongsToMany
    {
        return $this->belongsToMany(Santri::class, 'akademik_kelas_santri', 'kelas_id', 'santri_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }
}
