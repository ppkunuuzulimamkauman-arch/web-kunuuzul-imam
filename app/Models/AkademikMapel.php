<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AkademikMapel extends Model
{
    use HasFactory;

    protected $table = 'akademik_mapels';

    protected $fillable = ['kode', 'nama', 'jenjang', 'is_aktif'];

    protected $casts = ['is_aktif' => 'boolean'];

    public function nilais(): HasMany
    {
        return $this->hasMany(AkademikNilai::class, 'mapel_id');
    }
}
