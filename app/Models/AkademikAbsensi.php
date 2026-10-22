<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AkademikAbsensi extends Model
{
    use HasFactory;

    protected $table = 'akademik_absensis';

    protected $fillable = ['santri_id', 'tanggal', 'status', 'keterangan'];

    protected $casts = ['tanggal' => 'date'];

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }
}
