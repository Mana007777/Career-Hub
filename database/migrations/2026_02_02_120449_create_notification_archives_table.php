<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notification_archives', function (Blueprint $table) {
            $table->foreignId('notification_id')->primary()->constrained('user_notifications')->cascadeOnDelete();
            $table->timestamp('archived_at');
            
            $table->index('archived_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_archives');
    }
};
