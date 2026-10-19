<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumnis', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->unsignedSmallInteger('angkatan')->nullable(); // tahun masuk
            $table->unsignedSmallInteger('tahun_lulus')->nullable();
            $table->string('lembaga', 150)->nullable();
            $table->string('domisili', 150)->nullable();
            $table->string('pekerjaan', 150)->nullable();
            $table->string('no_hp', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('foto')->nullable();
            $table->text('tentang')->nullable();
            $table->boolean('is_tampil')->default(true);
            $table->timestamps();

            $table->index('nama');
            $table->index('angkatan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumnis');
    }
};
