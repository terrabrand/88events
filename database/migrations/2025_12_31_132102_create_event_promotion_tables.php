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
        // 1. Add credits to users
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('credits', 10, 2)->default(0)->after('email');
        });

        // 2. Ad Packages
        Schema::create('ad_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Daily Boost", "Weekly Feature"
            $table->integer('duration_days');
            $table->decimal('price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Promotions
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Organizer who bought it
            $table->foreignId('ad_package_id')->nullable()->constrained('ad_packages')->nullOnDelete();
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->enum('status', ['pending', 'active', 'paused', 'ended', 'rejected'])->default('pending');
            $table->decimal('cost', 10, 2);
            $table->string('payment_method')->default('credit'); // credit, cash, direct
            $table->string('transaction_id')->nullable(); 
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        // 4. Credit Transactions
        Schema::create('credit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2); // Positive for purchase, negative for spend
            $table->string('type'); // purchase, spend, refund, bonus
            $table->string('description')->nullable();
            $table->string('reference_id')->nullable(); // External txn ID or Promotion ID
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_transactions');
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('ad_packages');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('credits');
        });
    }
};
