<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'view admin dashboard',
            'manage users',
            'approve events',
            
            'create events',
            'edit events',
            'delete events',
            'manage tickets',
            'view sales analytics',
            
            'scan tickets',
            
            'purchase tickets',
            'view live events',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles and Assign Permissions
        
        // 1. Admin
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // 2. Organizer
        $organizer = Role::create(['name' => 'organizer']);
        $organizer->givePermissionTo([
            'create events',
            'edit events',
            'delete events',
            'manage tickets',
            'view sales analytics',
            'scan tickets',
        ]);

        // 3. Attendee
        $attendee = Role::create(['name' => 'attendee']);
        $attendee->givePermissionTo([
            'purchase tickets',
            'view live events',
        ]);

        // 4. Gate / Scanner
        $scanner = Role::create(['name' => 'scanner']);
        $scanner->givePermissionTo([
            'scan tickets',
        ]);
    }
}
