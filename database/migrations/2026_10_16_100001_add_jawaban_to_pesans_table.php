<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesans', function (Blueprint $table) {
            $table->text('jawaban')->nullable()->after('pesan');
            $table->timestamp('dijawab_at')->nullable()->after('jawaban');
            $table->unsignedBigInteger('berita_id')->nullable()->after('dijawab_at');
        });
    }

    public function down(): void
    {
        Schema::table('pesans', function (Blueprint $table) {
            $table->dropColumn(['jawaban', 'dijawab_at', 'berita_id']);
        });
    }
};
