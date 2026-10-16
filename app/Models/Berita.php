<?php

namespace App\Models;

use App\Concerns\DeletesStoredFiles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory, DeletesStoredFiles;

    protected static array $storedFiles = ['gambar'];

    protected $fillable = ['judul','slug','kategori','excerpt','konten','gambar','penulis','is_published','is_hero','published_at'];

    protected $casts = ['published_at' => 'datetime', 'is_published' => 'boolean', 'is_hero' => 'boolean'];

    public const MAX_HERO = 1;
}
