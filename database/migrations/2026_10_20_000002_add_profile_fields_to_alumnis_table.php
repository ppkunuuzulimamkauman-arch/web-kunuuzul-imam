<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumnis', function (Blueprint $table) {
            $table->string('jenis_kelamin', 1)->nullable()->after('nama');
            $table->string('status_hidup', 20)->default('hidup')->after('jenis_kelamin');
            $table->string('tempat_lahir', 100)->nullable()->after('status_hidup');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->string('dusun', 100)->nullable()->after('tanggal_lahir');
            $table->string('rt', 5)->nullable()->after('dusun');
            $table->string('rw', 5)->nullable()->after('rt');
            $table->string('desa', 100)->nullable()->after('rw');
            $table->string('kecamatan', 100)->nullable()->after('desa');
            $table->string('kabupaten', 100)->nullable()->after('kecamatan');
            $table->string('provinsi', 100)->nullable()->after('kabupaten');
            $table->string('wilayah_pk', 20)->nullable()->after('provinsi');

            $table->index('wilayah_pk');
            $table->index('kabupaten');
            $table->index('kecamatan');
        });
    }

    public function down(): void
    {
        Schema::table('alumnis', function (Blueprint $table) {
            $table->dropIndex(['wilayah_pk']);
            $table->dropIndex(['kabupaten']);
            $table->dropIndex(['kecamatan']);
            $table->dropColumn([
                'jenis_kelamin',
                'status_hidup',
                'tempat_lahir',
                'tanggal_lahir',
                'dusun',
                'rt',
                'rw',
                'desa',
                'kecamatan',
                'kabupaten',
                'provinsi',
                'wilayah_pk',
            ]);
        });
    }
};
