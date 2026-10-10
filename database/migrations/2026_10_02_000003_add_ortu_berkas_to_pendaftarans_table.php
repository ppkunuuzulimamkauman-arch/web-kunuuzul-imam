<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->string('nama_ayah')->nullable()->after('nama_wali');
            $table->string('pekerjaan_ayah')->nullable()->after('nama_ayah');
            $table->string('nama_ibu')->nullable()->after('pekerjaan_ayah');
            $table->string('pekerjaan_ibu')->nullable()->after('nama_ibu');
            $table->date('tanggal_pengisian')->nullable()->after('catatan');
            $table->string('file_ijazah')->nullable()->after('tanggal_pengisian');
            $table->string('file_kk')->nullable()->after('file_ijazah');
            $table->string('file_kip')->nullable()->after('file_kk');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn([
                'nama_ayah', 'pekerjaan_ayah', 'nama_ibu', 'pekerjaan_ibu',
                'tanggal_pengisian', 'file_ijazah', 'file_kk', 'file_kip',
            ]);
        });
    }
};
