<?php

namespace App\Helpers;

class MenuHelper
{
    /**
     * Get all navigation menus
     */
    public static function getMenus()
    {
        return config('menu.menus', []);
    }

    /**
     * Get a specific menu by name
     */
    public static function getMenu($menuName)
    {
        return config("menu.menus.{$menuName}", []);
    }

    /**
     * Get user menu items
     */
    public static function getUserMenu()
    {
        return config('menu.user_menu', []);
    }

    /**
     * Get menu items for a specific menu, filtered by user permissions
     */
    public static function getMenuItems($menuName, $user = null)
    {
        $menu = self::getMenu($menuName);
        $items = $menu['items'] ?? [];

        // If no user provided, return all items
        if (!$user) {
            return $items;
        }

        // Filter items based on user permissions
        return collect($items)->filter(function ($item) use ($user) {
            // If no permission required, include the item
            if (!isset($item['permission'])) {
                return true;
            }

            // Check if user has the required permission
            return $user->can($item['permission']);
        })->values()->toArray();
    }

    /**
     * Get all menu items filtered by user permissions
     */
    public static function getFilteredMenus($user = null)
    {
        $menus = self::getMenus();
        $filtered = [];

        foreach ($menus as $key => $menu) {
            $filtered[$key] = $menu;
            $filtered[$key]['items'] = self::getMenuItems($key, $user);
        }

        return $filtered;
    }

    /**
     * Check if user can access a menu item
     */
    public static function canAccess($menuName, $itemIndex, $user = null)
    {
        if (!$user) {
            return true;
        }

        $items = self::getMenuItems($menuName, $user);

        return isset($items[$itemIndex]);
    }

    /**
     * Get breadcrumb for current page
     */
    public static function getBreadcrumb($page = 'Dashboard', $icon = null)
    {
        return [
            'label' => $page,
            'icon' => $icon,
        ];
    }
}
