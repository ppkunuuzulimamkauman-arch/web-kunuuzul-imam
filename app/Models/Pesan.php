<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    use HasFactory;

    protected $fillable = ['nama','email','no_hp','subjek','pesan','is_dibaca','jawaban','dijawab_at','berita_id'];

    protected $casts = ['is_dibaca' => 'boolean', 'dijawab_at' => 'datetime'];

    public function berita()
    {
        return $this->belongsTo(Berita::class);
    }
}
