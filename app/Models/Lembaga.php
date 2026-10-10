<?php

namespace App\Models;

use App\Concerns\DeletesStoredFiles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lembaga extends Model
{
    use HasFactory, DeletesStoredFiles;

    protected static array $storedFiles = ['gambar'];

    protected $fillable = ['nama', 'deskripsi', 'ikon', 'gambar', 'url', 'urutan', 'is_aktif'];

    protected $casts = ['is_aktif' => 'boolean'];
}
