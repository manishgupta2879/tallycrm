<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default company
        $company = Company::firstOrCreate(
            ['pid' => 'HQ-001'],
            [
                'name'   => 'Head Office',
                'status' => 'Active',
            ]
        );

        // Get Admin role
        $adminRole = Role::where('slug', 'admin')->first();

        if (!$adminRole) {
            $this->command->error('❌ Admin role not found! Please run RolesSeeder first.');
            return;
        }

        // Create or update admin user
        $admin = User::firstOrCreate(
            ['email' => 'support@tallychamps.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'), // Change this in production
                'status' => true,
                'email_verified_at' => now(),
                'role_id' => $adminRole->id,
            ]
        );

        // Attach admin role to user
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        // Attach to company
        $admin->companies()->syncWithoutDetaching([$company->id]);

        $this->command->info('✅ Admin user created successfully!');
        $this->command->info('   Email: support@tallychamps.com');
        $this->command->info('   Password: password (⚠️  Change this immediately in production!)');
        $this->command->info('   Company: ' . $company->name);
        $this->command->info('   Role: ' . $adminRole->name);
    }
}
