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
        Schema::table('verifications', function (Blueprint $table) {
            $table->string('fib_payment_id')->nullable()->after('document_url');
            $table->string('payment_status')->nullable()->after('fib_payment_id');
            $table->unsignedInteger('payment_amount')->nullable()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('payment_amount');

            $table->index('fib_payment_id');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verifications', function (Blueprint $table) {
            $table->dropIndex(['fib_payment_id']);
            $table->dropIndex(['payment_status']);
            $table->dropColumn(['fib_payment_id', 'payment_status', 'payment_amount', 'paid_at']);
        });
    }
};
