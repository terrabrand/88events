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
        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('tax_type', ['inclusive', 'exclusive', 'none'])->default('none');
            $table->decimal('tax_rate', 5, 2)->default(0); // 0-100%
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->string('coupon_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'tax_type', 'tax_rate']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['tax_amount', 'discount_amount', 'coupon_code']);
        });
    }
};
