<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AkademikNilai extends Model
{
    use HasFactory;

    protected $table = 'akademik_nilais';

    protected $fillable = [
        'santri_id',
        'mapel_id',
        'tahun_ajaran_id',
        'semester',
        'nilai',
        'catatan',
    ];

    protected $casts = [
        'semester' => 'integer',
        'nilai' => 'decimal:2',
    ];

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(AkademikMapel::class, 'mapel_id');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(AkademikTahunAjaran::class, 'tahun_ajaran_id');
    }
}
