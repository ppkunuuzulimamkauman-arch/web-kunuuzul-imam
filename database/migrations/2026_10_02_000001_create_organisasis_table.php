<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organisasis', function (Blueprint $table) {
            $table->id();
            $table->string('singkatan', 50);
            $table->string('nama')->nullable(); // nama panjang
            $table->string('slug')->unique();
            $table->string('kategori', 20)->default('dalam'); // dalam | luar
            $table->text('deskripsi_singkat')->nullable(); // tampil di kartu list
            $table->longText('deskripsi')->nullable(); // tampil di halaman detail
            $table->longText('struktur')->nullable(); // susunan pengurus, satu baris satu jabatan
            $table->string('logo')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organisasis');
    }
};
