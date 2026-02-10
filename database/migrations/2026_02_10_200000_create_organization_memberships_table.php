<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('organization_memberships', function (Blueprint $table) {
            $table->id();
            // Company is also a user record with role = 'company'
            $table->foreignId('company_id')
                ->constrained('users')
                ->cascadeOnDelete();
            // The user who is (or will be) a member of the organization
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            // pending = invitation sent, accepted = user accepted, rejected = user rejected
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            // Which user sent the invitation (usually the company account or an admin)
            $table->foreignId('invited_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_memberships');
    }
};

