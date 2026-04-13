<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Services\RoleService; // Added
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(Request $request)
    {
        Gate::authorize('roles.view');

        $query = Role::search($request->input('search'));

        // Count users where role_id matches this role's id
        $query->selectRaw('roles.*, (SELECT COUNT(*) FROM users WHERE users.role_id = roles.id) as users_count');

        $roles = $query->paginate(25);

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

    public function store(StoreRoleRequest $request)
    {
        try {
            $data = $request->only(['name', 'slug']);
            $data['permissions'] = $request->input('permissions', []);

            $this->roleService->createRole($data);

            return redirect()->route('roles.index')->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
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

    public function update(UpdateRoleRequest $request, Role $role)
    {
        try {
            $data = $request->only(['name', 'slug']);
            $data['permissions'] = $request->input('permissions', []);

            $this->roleService->updateRole($role, $data);

            return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role)
    {
        Gate::authorize('roles.delete');

        try {
            $this->roleService->deleteRole($role);
            return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Get permissions organized hierarchically.
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

