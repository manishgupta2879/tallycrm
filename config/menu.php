<?php

/**
 * Application Navigation Menu Configuration
 *
 * This file defines all the application's navigation menus in a centralized,
 * easy-to-maintain format. Each menu can have multiple items with labels,
 * routes/URLs, and optional icons.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Main Navigation Menus
    |--------------------------------------------------------------------------
    |
    | Define primary navigation menus that appear in the application header.
    | Each menu can contain multiple items with labels, routes, and icons.
    |
    */

    'menus' => [
        'setup' => [
            'label' => 'SETUP',
            'icon' => null,
            'items' => [
                [
                    'label' => 'Primary Setup',
                    'icon' => null,
                    'items' => [
                        [
                            'label' => 'Company',
                            'route' => 'companies.index',
                            'icon' => 'building',
                            'permission' => 'company',
                        ],
                        [
                            'label' => 'Distributor',
                            'route' => 'distributors.index',
                            'icon' => 'building',
                            'permission' => 'distributor',
                        ],
                        [
                            'label' => 'Users',
                            'route' => 'users.index',
                            'icon' => 'users',
                            'permission' => 'users',
                        ],
                        [
                            'label' => 'Roles',
                            'route' => 'roles.index',
                            'icon' => 'users-cog',
                            'permission' => 'roles',
                        ]
                    ],
                ],
                [
                    'label' => 'Additional Opportunity',
                    'icon' => null,
                    'items' => [
                        [
                            'label' => 'Category',
                            'route' => 'categories.index',
                            'icon' => 'building',
                            'permission' => 'categories',
                        ],
                        [
                            'label' => 'Additional Opportunity',
                            'route' => 'additional-opportunities',
                            'icon' => 'building',
                            'permission' => 'additional-opportunities',
                        ],
                    ],
                ],
                // Removed Secondary Setup from here
                // New top-level entry for Secondary Setup added below
                // End of setup items

            ]

        ],

        'imports' => [
            'label' => 'IMPORTS',
            'icon' => null,
            'items' => [],
        ],

        'reports' => [
            'label' => 'REPORTS',
            'icon' => null,
            'items' => [],
        ],

        'admin' => [
            'label' => 'OTHER ADMIN',
            'icon' => null,
            'items' => [
                [
                    'label' => 'User Log',
                    'route' => 'user-logs.index',
                    'icon' => 'history',
                    'permission' => 'activity-log',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu (Right Side)
    |--------------------------------------------------------------------------
    |
    | Menu items that appear on the right side of the header (user profile menu)
    |
    */

    'user_menu' => [
        [
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'home',
        ],
        [
            'label' => 'Profile',
            'route' => 'profile.edit',
            'icon' => 'user',
        ],
        [
            'label' => 'Logout',
            'action' => 'logout',
            'icon' => 'sign-out',
            'method' => 'POST',
        ],
    ],
];
