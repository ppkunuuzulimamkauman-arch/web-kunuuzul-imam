<?php

namespace App\Models;

use App\Concerns\DeletesStoredFiles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory, DeletesStoredFiles;

    protected static array $storedFiles = ['gambar'];

    protected $fillable = ['nama','slug','kategori','deskripsi_singkat','deskripsi','ikon','gambar','is_aktif','urutan'];
}
