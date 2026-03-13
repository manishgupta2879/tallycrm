<?php

namespace App\Helpers;

class Theme
{
    /**
     * Get color from config
     */
    public static function color($path, $default = null)
    {
        return config("colors.$path", $default);
    }

    /**
     * Get primary color
     */
    public static function primary($shade = '600')
    {
        return self::color("primary.$shade");
    }

    /**
     * Get all primary colors
     */
    public static function primaryColors()
    {
        return config('colors.primary', []);
    }

    /**
     * Get light mode color
     */
    public static function lightColor($key)
    {
        return self::color("light.$key");
    }

    /**
     * Get dark mode color
     */
    public static function darkColor($key)
    {
        return self::color("dark.$key");
    }
}
