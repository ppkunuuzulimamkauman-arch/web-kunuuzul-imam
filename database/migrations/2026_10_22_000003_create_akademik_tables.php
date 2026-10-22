<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('akademik_tahun_ajarans', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50)->unique();
            $table->date('mulai')->nullable();
            $table->date('selesai')->nullable();
            $table->boolean('is_aktif')->default(false);
            $table->timestamps();
        });

        Schema::create('akademik_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('akademik_tahun_ajarans')->cascadeOnDelete();
            $table->string('nama', 100);
            $table->string('jenjang', 50)->nullable();
            $table->unsignedTinyInteger('tingkat')->nullable();
            $table->string('wali_kelas', 150)->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();

            $table->unique(['tahun_ajaran_id', 'nama']);
        });

        Schema::create('akademik_kelas_santri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('akademik_kelas')->cascadeOnDelete();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
            $table->string('status', 20)->default('aktif');
            $table->timestamps();

            $table->unique(['kelas_id', 'santri_id']);
        });

        Schema::create('akademik_mapels', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 30)->nullable()->unique();
            $table->string('nama', 150);
            $table->string('jenjang', 50)->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('akademik_nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
            $table->foreignId('mapel_id')->constrained('akademik_mapels')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('akademik_tahun_ajarans')->cascadeOnDelete();
            $table->unsignedTinyInteger('semester');
            $table->decimal('nilai', 5, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['santri_id', 'mapel_id', 'tahun_ajaran_id', 'semester'], 'akademik_nilai_unik');
        });

        Schema::create('akademik_absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('status', 20)->default('hadir');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['santri_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akademik_absensis');
        Schema::dropIfExists('akademik_nilais');
        Schema::dropIfExists('akademik_mapels');
        Schema::dropIfExists('akademik_kelas_santri');
        Schema::dropIfExists('akademik_kelas');
        Schema::dropIfExists('akademik_tahun_ajarans');
    }
};
