<?php

namespace App\Services;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleService
{
    /**
     * Create a new role with permissions.
     *
     * @param array $data
     * @return Role
     * @throws \Exception
     */
    public function createRole(array $data): Role
    {
        return DB::transaction(function () use ($data) {
            $role = Role::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
            ]);

            $this->syncPermissions($role, $data['permissions'] ?? []);

            return $role;
        });
    }

    /**
     * Update an existing role and its permissions.
     *
     * @param Role $role
     * @param array $data
     * @return Role
     * @throws \Exception
     */
    public function updateRole(Role $role, array $data): Role
    {
        return DB::transaction(function () use ($role, $data) {
            $role->update([
                'name' => $data['name'],
                'slug' => $data['slug'],
            ]);

            $this->syncPermissions($role, $data['permissions'] ?? []);

            return $role;
        });
    }

    /**
     * Delete a role if no users are assigned.
     *
     * @param Role $role
     * @return bool|null
     * @throws \Exception
     */
    public function deleteRole(Role $role)
    {
        return DB::transaction(function () use ($role) {
            $usersCount = \App\Models\User::where('role_id', $role->id)->count();

            if ($usersCount > 0) {
                throw new \Exception("Cannot delete this role because {$usersCount} user(s) are assigned to it.");
            }

            return $role->delete();
        });
    }

    /**
     * Sync permissions for a role.
     *
     * @param Role $role
     * @param array $inputPermissions
     * @return void
     */
    protected function syncPermissions(Role $role, array $inputPermissions): void
    {
        $permissions = Permission::all();
        $permissionData = [];

        foreach ($permissions as $permission) {
            $view = isset($inputPermissions[$permission->id]['view']);
            $create = isset($inputPermissions[$permission->id]['create']);
            $edit = isset($inputPermissions[$permission->id]['edit']);
            $delete = isset($inputPermissions[$permission->id]['delete']);
            $export = isset($inputPermissions[$permission->id]['export']);
            $import = isset($inputPermissions[$permission->id]['import']);

            // Only attach if at least one permission is granted
            if ($view || $create || $edit || $delete || $export || $import) {
                $permissionData[$permission->id] = [
                    'view' => $view,
                    'create' => $create,
                    'edit' => $edit,
                    'delete' => $delete,
                    'export' => $export,
                    'import' => $import,
                ];
            }
        }

        $role->permissions()->sync($permissionData);
    }
}
