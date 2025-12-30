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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('event_id')->constrained();
            $table->foreignId('ticket_type_id')->nullable()->constrained(); // Link to what was bought
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('payment_method'); // mpesa, card, etc
            $table->string('transaction_ref')->unique(); // Internal Ref
            $table->string('external_ref')->nullable(); // Gateway Ref
            $table->string('status')->default('pending'); // pending, completed, failed, refunded
            $table->json('meta_data')->nullable(); // Store gateway payload
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
