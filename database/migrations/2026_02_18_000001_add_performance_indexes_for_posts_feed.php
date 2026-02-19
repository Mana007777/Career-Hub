<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Improves /posts feed and filtering: composite (user_id, created_at), job_type, blocks lookup.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->index(['user_id', 'created_at']);
            $table->index('job_type');
        });

        Schema::table('blocks', function (Blueprint $table) {
            $table->index('blocked_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'created_at']);
            $table->dropIndex(['job_type']);
        });

        Schema::table('blocks', function (Blueprint $table) {
            $table->dropIndex(['blocked_id']);
        });
    }
};
