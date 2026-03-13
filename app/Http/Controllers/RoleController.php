<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('roles.view');

        $query = Role::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Count users where role_id matches this role's id
        $query->selectRaw('roles.*, (SELECT COUNT(*) FROM users WHERE users.role_id = roles.id) as users_count');

        $roles = $query->paginate(20);

        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        Gate::authorize('roles.create');

        // Get permissions organized hierarchically
        $permissionsHierarchy = $this->getHierarchicalPermissions();

        return view('roles.create', compact('permissionsHierarchy'));
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('roles.create');

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'slug' => 'required|string|max:255|unique:roles,slug',
        ], [
            'name.required' => 'Role name is required.',
            'name.unique' => 'This role name already exists.',
            'slug.required' => 'Role slug is required.',
            'slug.unique' => 'This role slug already exists.',
        ]);

        $role = Role::create($request->only(['name', 'slug']));

        // Save permissions
        $this->saveRolePermissions($role, $request);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        Gate::authorize('roles.edit');

        $usersCount = User::where('role_id', $role->id)->count();
        // Get permissions organized hierarchically
        $permissionsHierarchy = $this->getHierarchicalPermissions();
        // Get role's current permissions with their pivot data
        $rolePermissions = $role->permissions()->get()->keyBy('id');

        return view('roles.edit', compact('role', 'usersCount', 'permissionsHierarchy', 'rolePermissions'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role)
    {
        Gate::authorize('roles.edit');

        $usersCount = User::where('role_id', $role->id)->count();

        $rules = [
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ];

        // Only allow slug update if no users are assigned
        if ($usersCount === 0) {
            $rules['slug'] = 'required|string|max:255|unique:roles,slug,' . $role->id;
        } else {
            $rules['slug'] = 'required|string|max:255|in:' . $role->slug;
        }

        $request->validate($rules, [
            'name.required' => 'Role name is required.',
            'name.unique' => 'This role name already exists.',
            'slug.required' => 'Role slug is required.',
            'slug.unique' => 'This role slug already exists.',
            'slug.in' => 'You cannot change the slug when users are assigned to this role.',
        ]);

        $role->update($request->only(['name', 'slug']));

        // Save permissions
        $this->saveRolePermissions($role, $request);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role)
    {
        Gate::authorize('roles.delete');

        $usersCount = User::where('role_id', $role->id)->count();

        if ($usersCount > 0) {
            return redirect()->route('roles.index')
                ->with('error', "Cannot delete this role because {$usersCount} user(s) are assigned to it. Please reassign users first.");
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }

    /**
     * Save role permissions from request data.
     */
    private function saveRolePermissions(Role $role, Request $request)
    {
        $permissions = Permission::all();
        $permissionData = [];
        $inputPermissions = $request->input('permissions', []);

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

        // Sync permissions (detach all, then attach selected ones)
        $role->permissions()->sync($permissionData);
    }

    /**
     * Get permissions organized in hierarchical structure.
     * Returns: Setup > Primary Setup > Users/Roles and Activity > Logs > Activity Log
     */
    /**
     * Get permissions organized in hierarchical structure based on menu config.
     */
    private function getHierarchicalPermissions()
    {
        $permissions = Permission::all();
        $menuConfig = config('menu.menus', []);
        $hierarchy = [];

        foreach ($menuConfig as $menu) {
            if (empty($menu['items']))
                continue;

            $menuHierarchy = [
                'category' => $menu['label'],
                'icon' => $menu['icon'] ?? 'folder',
                'subcategories' => []
            ];

            foreach ($menu['items'] as $subcategory) {
                if (isset($subcategory['permission'])) {
                    // Item directly under main menu
                    $module = $subcategory['permission'];
                    $modulePermissions = $permissions->where('module', $module);

                    if ($modulePermissions->isNotEmpty()) {
                        $menuHierarchy['subcategories'][] = [
                            'name' => '', // Empty means no additional nesting
                            'items' => [
                                [
                                    'module' => $module,
                                    'label' => $subcategory['label'],
                                    'permissions' => $modulePermissions,
                                    'icon' => $subcategory['icon'] ?? 'circle',
                                    'availableActions' => $this->getAvailableActions($module),
                                ]
                            ]
                        ];
                    }
                } elseif (isset($subcategory['items'])) {
                    $categoryItems = [];
                    foreach ($subcategory['items'] as $item) {
                        $module = $item['permission'] ?? '';
                        if (!$module)
                            continue;

                        $modulePermissions = $permissions->where('module', $module);

                        if ($modulePermissions->isNotEmpty()) {
                            // Only include if module exists in permissions or is defined in menu
                            $categoryItems[] = [
                                'module' => $module,
                                'label' => $item['label'],
                                'permissions' => $modulePermissions,
                                'icon' => $item['icon'] ?? 'circle',
                                'availableActions' => $this->getAvailableActions($module),
                            ];
                        }
                    }

                    if (!empty($categoryItems)) {
                        $menuHierarchy['subcategories'][] = [
                            'name' => $subcategory['label'],
                            'items' => $categoryItems,
                        ];
                    }
                }
            }

            if (!empty($menuHierarchy['subcategories'])) {
                $hierarchy[] = $menuHierarchy;
            }
        }

        return $hierarchy;
    }

    /**
     * Define available actions for each module.
     */
    private function getAvailableActions($module)
    {
        if (in_array($module, ['activity-log', 'user-log'])) {
            return ['view'];
        }
        return ['view', 'create', 'edit', 'delete', 'export', 'import'];
    }
}

