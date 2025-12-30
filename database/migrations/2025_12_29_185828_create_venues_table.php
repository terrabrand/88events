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
        Schema::create('venues', function (Blueprint $row) {
            $row->id();
            $row->string('name');
            $row->string('address')->nullable();
            $row->integer('capacity')->default(0);
            $row->string('seat_map_image')->nullable();
            $row->boolean('is_global')->default(false);
            $row->foreignId('organizer_id')->nullable()->constrained('users')->onDelete('cascade');
            $row->text('seat_numbers')->nullable(); // JSON or CSV list of valid seat numbers
            $row->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
