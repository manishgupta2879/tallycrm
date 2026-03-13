<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CAPTCHA Defaults
    |--------------------------------------------------------------------------
    |
    | This value determines the "Captcha Driver" that will be used
    | to create captchas. By default this is set to "gd" driver.
    |
    */

    'default' => env('CAPTCHA_DRIVER', 'gd'),

    /*
    |--------------------------------------------------------------------------
    | CAPTCHA Drivers
    |--------------------------------------------------------------------------
    |
    | Supported: "gd"
    |
    */

    'gd' => [
        'driver' => 'gd',
        'middleware' => ['web'],
        'config' => [
            'numbers' => '0123456789',
            'characters' => '0123456789',

            'default' => [
                'length' => 5,
                'width' => 120,
                'height' => 40,
                'quality' => 90,
                'math' => false,
                'expire' => 60,
                'encrypt' => false,
            ],

            'flat' => [
                'length' => 5,
                'width' => 120,
                'height' => 40,
                'quality' => 90,
                'math' => false,
                'expire' => 60,
                'encrypt' => false,
                'font_size' => 20,
                'font' => __DIR__ . '/../../../vendor/mews/captcha/src/fonts/Acme.ttf',
            ],

            'mini' => [
                'length' => 3,
                'width' => 80,
                'height' => 30,
                'quality' => 90,
                'math' => false,
                'expire' => 60,
                'encrypt' => false,
            ],

            'inverse' => [
                'length' => 5,
                'width' => 120,
                'height' => 40,
                'quality' => 90,
                'math' => false,
                'expire' => 60,
                'encrypt' => false,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | CAPTCHA Routes
    |--------------------------------------------------------------------------
    |
    | Routes that serve CAPTCHA
    |
    */

    'routes' => [
        'captcha' => 'captcha',
        'captcha_api' => 'captcha/api',
        'captcha_ascii' => 'captcha/ascii',
        'captcha_math' => 'captcha/math',
    ],

    /*
    |--------------------------------------------------------------------------
    | CAPTCHA Cache
    |--------------------------------------------------------------------------
    |
    | Cache settings for storing captcha data
    |
    */

    'cache' => [
        'enabled' => true,
        'ttl' => 60, // seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | CAPTCHA Security Settings
    |--------------------------------------------------------------------------
    |
    | Best practice security settings for production
    |
    */

    'security' => [
        'sensitive' => true,
        'case_sensitive' => false,
        'log_attempts' => true,
        'max_attempts_per_hour' => 50,
        'lock_after_failed_attempts' => 5,
        'lock_duration_minutes' => 15,
    ],

];
