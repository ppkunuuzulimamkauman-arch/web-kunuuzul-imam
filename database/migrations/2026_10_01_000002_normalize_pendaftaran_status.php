<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite tidak mendukung MODIFY ENUM — buat ulang via string agar fleksibel.
        if (DB::getDriverName() === 'sqlite') {
            // Biarkan kolom apa adanya; validasi di level aplikasi.
            // Normalisasi data lama bila ada.
            try {
                DB::table('pendaftarans')->whereNotIn('status', ['menunggu', 'diproses', 'diterima', 'ditolak', 'lunas'])
                    ->update(['status' => 'menunggu']);
            } catch (\Throwable $e) {
            }
            return;
        }

        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->string('status', 20)->default('menunggu')->change();
        });
    }

    public function down(): void
    {
    }
};
