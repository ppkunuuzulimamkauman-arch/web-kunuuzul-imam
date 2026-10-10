<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable(); // mis: TAHFIDZ QURAN
            $table->string('badge')->nullable();
            $table->string('title'); // boleh pakai <br>
            $table->text('sub')->nullable();
            $table->text('desc')->nullable();
            $table->string('gambar')->nullable(); // path storage / URL / /images/hero/...
            $table->string('cta1_text')->nullable();
            $table->string('cta1_url')->nullable();
            $table->string('cta2_text')->nullable();
            $table->string('cta2_url')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
