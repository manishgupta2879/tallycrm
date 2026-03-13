<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create permissions first
        $this->call(PermissionsSeeder::class);

        // Create roles with permissions
        $this->call(RolesSeeder::class);

        // Create admin user with company
        $this->call(AdminUserSeeder::class);

        // Optionally create test users
        // User::factory(10)->create();
    }
}
