<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_permohonan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 30)->unique(); // kode lacak permohonan, mis. SRT-2610-AB12CD
            $table->string('jenis', 40);
            $table->string('jenis_lainnya', 150)->nullable();
            $table->string('nama', 150);
            $table->string('nik', 20)->nullable();
            $table->string('no_hp', 30);
            $table->string('email', 150)->nullable();
            $table->string('lembaga', 150)->nullable();
            $table->text('alamat')->nullable();
            $table->text('keperluan');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('tujuan', 150)->nullable();
            $table->string('ditujukan', 150)->nullable();
            $table->string('lampiran')->nullable();
            $table->string('status', 20)->default('menunggu');
            $table->string('nomor_surat', 100)->nullable();
            $table->text('catatan_admin')->nullable();
            $table->string('file_hasil')->nullable();
            $table->timestamp('selesai_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('nama');
            $table->index('no_hp');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_permohonan');
    }
};
