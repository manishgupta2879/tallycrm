<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CaptchaController extends Controller
{
    /**
     * Refresh the captcha image and return new HTML.
     * This endpoint is called via AJAX when user clicks refresh button.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            /**
             * Generate new captcha image
             * mews/captcha offers several styles: 'flat', 'gd' (default)
             * 'flat' - Simple flat design
             * 'gd' - GD library with distortion
             */
            $captcha = \Mews\Captcha\Facades\Captcha::img('flat');

            return response()->json([
                'success' => true,
                'captcha' => $captcha
            ])->header('Cache-Control', 'no-cache, no-store, must-revalidate')
             ->header('Pragma', 'no-cache')
             ->header('Expires', '0');
        } catch (\Exception $e) {
            \Log::error('Captcha refresh error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate captcha'
            ], 500);
        }
    }

    /**
     * Validate captcha code via AJAX.
     * This provides real-time validation feedback.
     *
     * Best Practice Benefits:
     * - Real-time validation reduces user friction
     * - Detailed error messages help users understand the issue
     * - Prevents unnecessary form submission
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function validate(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'captcha' => ['required', 'captcha_api']
            ], [
                'captcha.required' => 'Verification code is required',
                'captcha.captcha_api' => 'The verification code is incorrect'
            ]);

            return response()->json([
                'valid' => true,
                'message' => 'Verification code is correct'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'valid' => false,
                'message' => $e->errors()['captcha'][0] ?? 'Validation failed'
            ], 422);
        }
    }
}
