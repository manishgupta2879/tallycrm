<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Grant all permissions to Super Admin
        Gate::before(function ($user, $ability) {
            return $user->isSuperAdmin() ? true : null;
        });

        // Register permission gates dynamically
        $this->registerPermissionGates();
    }

    /**
     * Register permission gates for menu items recursively
     */
    protected function registerPermissionGates(): void
    {
        $menus = config('menu.menus', []);
        $this->registerItems($menus);
    }

    /**
     * Recursive helper to register items
     */
    protected function registerItems(array $items): void
    {
        $actions = ['view', 'create', 'edit', 'delete', 'export', 'import'];

        foreach ($items as $item) {
            if (isset($item['permission'])) {
                $module = $item['permission'];

                // Register granular gates for each action
                foreach ($actions as $action) {
                    $gateName = "{$module}.{$action}";

                    if (!Gate::has($gateName)) {
                        Gate::define($gateName, function ($user) use ($module, $action) {
                            if ($user->isSuperAdmin()) {
                                return true;
                            }

                            // Check primary role
                            if ($user->role && $user->role->hasPermission($module, $action)) {
                                return true;
                            }

                            // Check if user has the specific permission through their secondary roles
                            return $user->roles()
                                ->whereHas('permissions', function ($query) use ($module, $action) {
                                    $query->where('permissions.module', $module)
                                        ->where('role_permissions.' . $action, true);
                                })
                                ->exists();
                        });
                    }
                }
                
                // Keep the base module gate as an alias for .view
                if (!Gate::has($module)) {
                    Gate::define($module, function ($user) use ($module) {
                        return Gate::allows("{$module}.view", $user);
                    });
                }
            }

            // Recurse if there are sub-items
            if (isset($item['items']) && is_array($item['items'])) {
                $this->registerItems($item['items']);
            }
        }
    }
}

