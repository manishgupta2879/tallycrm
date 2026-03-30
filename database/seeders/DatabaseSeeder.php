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
        $this->call(CompanySeeder::class);  
        // Create permissions first
        $this->call(PermissionsSeeder::class);

        // Create roles with permissions
        $this->call(RolesSeeder::class);

        // Create admin user with company
        $this->call(AdminUserSeeder::class);

        // Required default records
        $this->call(DefaultCompanySeeder::class);

    // Optionally create test users
    // User::factory(10)->create();
    }
}
