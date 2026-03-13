<?php

/**
 * Get captcha image HTML
 *
 * @param string $style Captcha style (flat, gd, etc)
 * @return string
 */
if (!function_exists('captcha_img')) {
    function captcha_img(string $style = 'flat'): string
    {
        try {
            return \Mews\Captcha\Facades\Captcha::img($style);
        } catch (\Exception $e) {
            \Log::error('Captcha error: ' . $e->getMessage());
            return '<div class="bg-gray-200 p-4 rounded text-center text-sm text-gray-600">Captcha unavailable</div>';
        }
    }
}

/**
 * Generate HTML image tag for captcha
 *
 * @param string|null $style
 * @return string
 */
if (!function_exists('captcha_image_tag')) {
    function captcha_image_tag(string $style = 'flat'): string
    {
        return captcha_img($style);
    }
}
