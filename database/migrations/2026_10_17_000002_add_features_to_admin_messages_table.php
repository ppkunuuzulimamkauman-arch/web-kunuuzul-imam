<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admin_messages', function (Blueprint $table) {
            $table->string('type')->default('text')->after('body');
            $table->string('attachment_path')->nullable()->after('type');
            $table->string('attachment_name')->nullable()->after('attachment_path');
            $table->unsignedBigInteger('attachment_size')->nullable()->after('attachment_name');
            $table->string('attachment_mime')->nullable()->after('attachment_size');
            $table->unsignedBigInteger('reply_to_id')->nullable()->after('attachment_mime');
            $table->json('starred_by')->nullable()->after('reply_to_id');
            $table->timestamp('pinned_at')->nullable()->after('starred_by');
            $table->softDeletes();

            $table->index('reply_to_id');
        });
    }

    public function down(): void
    {
        Schema::table('admin_messages', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn([
                'type', 'attachment_path', 'attachment_name', 'attachment_size',
                'attachment_mime', 'reply_to_id', 'starred_by', 'pinned_at',
            ]);
        });
    }
};
