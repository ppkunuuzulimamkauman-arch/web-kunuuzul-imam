<?php

namespace App\Models;

use App\Concerns\DeletesStoredFiles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    use HasFactory, DeletesStoredFiles;

    protected static array $storedFiles = ['gambar'];

    protected $fillable = [
        'label', 'badge', 'title', 'sub', 'desc', 'gambar',
        'cta1_text', 'cta1_url', 'cta2_text', 'cta2_url',
        'urutan', 'is_aktif',
    ];

    protected $casts = ['is_aktif' => 'boolean'];
}
