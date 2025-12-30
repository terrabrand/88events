<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure the role doesn't already exist
        if (!Role::where('name', 'promoter')->exists()) {
            $promoter = Role::create(['name' => 'promoter']);
            
            // Assign some default permissions if needed
            // For now, promoters might just need to view their own stats
            // $promoter->givePermissionTo(['view sales analytics']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $role = Role::where('name', 'promoter')->first();
        if ($role) {
            $role->delete();
        }
    }
};
