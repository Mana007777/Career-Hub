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
        Schema::create('career_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->foreignId('specialty_id')->constrained();
            $table->foreignId('sub_specialty_id')->constrained();
            $table->string('location')->nullable();
            $table->string('job_type');
            $table->timestamps();
            
            $table->index('job_type');
            $table->index('location');
            $table->index(['specialty_id', 'sub_specialty_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_jobs');
    }
};
