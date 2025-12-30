<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
            
            $table->unique(['organizer_id', 'email']);
            $table->unique(['organizer_id', 'phone']);
        });

        Schema::create('event_guest', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('guest_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('invited'); // invited, attending, checked-in
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_guest');
        Schema::dropIfExists('guests');
    }
};
