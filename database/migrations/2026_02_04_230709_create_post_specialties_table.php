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
        Schema::create('post_specialties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('specialty_id')->constrained();
            $table->foreignId('sub_specialty_id')->constrained();
            $table->timestamps();
            
            // Ensure a post can't have the same specialty/sub-specialty combination twice
            $table->unique(['post_id', 'specialty_id', 'sub_specialty_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_specialties');
    }
};
