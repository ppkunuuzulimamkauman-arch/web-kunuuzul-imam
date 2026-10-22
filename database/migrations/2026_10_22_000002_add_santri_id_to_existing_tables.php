<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->foreignId('santri_id')->nullable()->after('id')
                ->constrained('santris')->nullOnDelete();
        });

        Schema::table('alumnis', function (Blueprint $table) {
            $table->foreignId('santri_id')->nullable()->after('id')
                ->constrained('santris')->nullOnDelete();
        });

        Schema::table('surat_permohonan', function (Blueprint $table) {
            $table->foreignId('santri_id')->nullable()->after('id')
                ->constrained('santris')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('surat_permohonan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('santri_id');
        });

        Schema::table('alumnis', function (Blueprint $table) {
            $table->dropConstrainedForeignId('santri_id');
        });

        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('santri_id');
        });
    }
};
