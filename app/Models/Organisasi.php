<?php

namespace App\Models;

use App\Concerns\DeletesStoredFiles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organisasi extends Model
{
    use HasFactory, DeletesStoredFiles;

    protected static array $storedFiles = ['logo'];

    protected $table = 'organisasis';

    protected $fillable = [
        'singkatan', 'nama', 'slug', 'kategori',
        'deskripsi_singkat', 'deskripsi', 'struktur',
        'logo', 'urutan', 'is_aktif',
    ];

    protected $casts = ['is_aktif' => 'boolean'];
}
