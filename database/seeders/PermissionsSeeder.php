<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions
        $permissions = [
            // Company module - full CRUD
            [
                'module' => 'company',
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'export' => true,
                'import' => true,
            ],
            // Distributor module - full CRUD
            [
                'module' => 'distributor',
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'export' => true,
                'import' => true,
            ],
            // Users module - full CRUD
            [
                'module' => 'users',
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'export' => true,
                'import' => true,
            ],
            // Roles module - full CRUD
            [
                'module' => 'roles',
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'export' => true,
                'import' => true,
            ],
            // Activity Log - view only
            [
                'module' => 'activity-log',
                'view' => true,
                'create' => false,
                'edit' => false,
                'delete' => false,
                'export' => false,
                'import' => false,
            ],
            // Categories module - full CRUD
            [
                'module' => 'categories',
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'export' => true,
                'import' => true,
            ],
            // Additional Opportunities module - full CRUD
            [
                'module' => 'additional-opportunities',
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'export' => true,
                'import' => true,
            ],
            // TallyLog module - full CRUD
            [
                'module' => 'tally-log',
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'export' => true,
                'import' => true,
            ],
        ];

        // Create permissions
        foreach ($permissions as $permissionData) {
            Permission::firstOrCreate(
                ['module' => $permissionData['module']],
                $permissionData
            );
        }

        $this->command->info('✅ Permissions created successfully!');
        $this->command->info('   • 8 permissions total');
        $this->command->info('   • Company: View, Create, Edit, Delete, Export, Import');
        $this->command->info('   • Distributor: View, Create, Edit, Delete, Export, Import');
        $this->command->info('   • Users: View, Create, Edit, Delete, Export, Import');
        $this->command->info('   • Roles: View, Create, Edit, Delete, Export, Import');
        $this->command->info('   • Activity Log: View only');
        $this->command->info('   • Categories: View, Create, Edit, Delete, Export, Import');
        $this->command->info('   • Additional Opportunities: View, Create, Edit, Delete, Export, Import');
        $this->command->info('   • Tally Log: View, Create, Edit, Delete, Export, Import');
    }
}
