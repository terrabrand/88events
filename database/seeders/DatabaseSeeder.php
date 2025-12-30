<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        // Admin
        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        // Organizer
        $organizer = User::factory()->create([
            'name' => 'Event Organizer',
            'email' => 'organizer@example.com',
            'password' => bcrypt('password'),
        ]);
        $organizer->assignRole('organizer');

        // Scanner
        $scanner = User::factory()->create([
            'name' => 'Gate Keeper',
            'email' => 'scanner@example.com',
            'password' => bcrypt('password'),
        ]);
        $scanner->assignRole('scanner');

        // Attendee
        $attendee = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'attendee@example.com',
            'password' => bcrypt('password'),
        ]);
        $attendee->assignRole('attendee');
    }
}
