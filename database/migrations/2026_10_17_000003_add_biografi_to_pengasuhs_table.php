<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengasuhs', function (Blueprint $table) {
            if (! Schema::hasColumn('pengasuhs', 'biografi')) {
                $table->text('biografi')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengasuhs', function (Blueprint $table) {
            if (Schema::hasColumn('pengasuhs', 'biografi')) {
                $table->dropColumn('biografi');
            }
        });
    }
};
