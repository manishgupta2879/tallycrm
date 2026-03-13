<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.\
     */
    public function run(): void
    {
        // Get all permissions keyed by module name
        $permissions = Permission::all()->keyBy('module');

        $allPermissionIds = $permissions->pluck('id')->toArray();

        // Helper: get IDs for a list of module names (skip missing ones gracefully)
        $idsFor = function (array $modules) use ($permissions): array {
            $ids = [];
            foreach ($modules as $module) {
                if ($permissions->has($module)) {
                    $ids[] = $permissions->get($module)->id;
                }
            }
            return $ids;
        };

        // Modules considered "setup" (primary setup menu items)
        $setupModules = ['company', 'distributor', 'users', 'roles'];
        $reportsModules = [];   // extend when report permissions are added
        $importsModules = [];   // extend when import permissions are added

        // Define roles with their permission configurations
        $rolesConfig = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'permissionIds' => $allPermissionIds,
                'actions' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true, 'export' => true, 'import' => true],
            ],
            [
                'name' => 'Viewer',
                'slug' => 'viewer',
                'permissionIds' => $idsFor($reportsModules),
                'actions' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false, 'export' => false, 'import' => false],
            ],
        ];

        // Create roles and attach permissions
        foreach ($rolesConfig as $roleData) {
            $role = Role::firstOrCreate(
                ['slug' => $roleData['slug']],
                ['name' => $roleData['name']]
            );

            // Attach permissions with specific actions
            foreach ($roleData['permissionIds'] as $permissionId) {
                $role->permissions()->syncWithoutDetaching([
                    $permissionId => $roleData['actions'],
                ]);
            }
        }

        $this->command->info('✅ Roles created successfully!');
        $this->command->info('   • Admin: All permissions (Full Access)');
        $this->command->info('   • Manager: Setup permissions (Company, Distributor, Users, Roles)');
        $this->command->info('   • Staff: Imports + Reports permissions');
        $this->command->info('   • Viewer: Reports only');
    }
}
