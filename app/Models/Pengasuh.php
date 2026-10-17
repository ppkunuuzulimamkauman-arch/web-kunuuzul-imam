<?php

namespace App\Models;

use App\Concerns\DeletesStoredFiles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengasuh extends Model
{
    use HasFactory, DeletesStoredFiles;

    protected static array $storedFiles = ['foto'];

    protected $fillable = ['nama', 'jabatan', 'bio', 'biografi', 'foto', 'urutan', 'is_aktif'];

    protected $casts = ['is_aktif' => 'boolean'];
}
