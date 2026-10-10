<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = ['judul', 'tanggal', 'tempat', 'deskripsi', 'is_aktif'];

    protected $casts = ['tanggal' => 'date', 'is_aktif' => 'boolean'];

    public static function bulanSingkat(int $bulan): string
    {
        return [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'][$bulan] ?? '';
    }
}
