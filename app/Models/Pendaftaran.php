<?php

namespace App\Models;

use App\Concerns\DeletesStoredFiles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory, DeletesStoredFiles;

    protected static array $storedFiles = ['file_ijazah', 'file_kk', 'file_kip'];

    protected $fillable = ['nomor_pendaftaran','nama_lengkap','nik','tempat_lahir','tanggal_lahir','jenis_kelamin','alamat','asal_sekolah','jenjang','nama_wali','hp_wali','no_hp','program_pilihan','catatan','status','nama_ayah','pekerjaan_ayah','nama_ibu','pekerjaan_ibu','tanggal_pengisian','file_ijazah','file_kk','file_kip'];

    protected $casts = ['tanggal_lahir' => 'date', 'tanggal_pengisian' => 'date'];
}
