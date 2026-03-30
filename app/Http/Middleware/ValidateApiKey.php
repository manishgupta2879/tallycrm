<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateApiKey
{
    /**
     * Handle an incoming request.
     * Validates the X-API-Key header against the configured API key.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip validation if explicitly disabled in config
        if (!config('app.api_key_enabled', false)) {
            return $next($request);
        }

        $apiKey = $request->header('X-API-Key') ?? $request->query('apiKey');

        if (!$apiKey || $apiKey !== config('app.api_key')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid or missing API key.',
            ], 401);
        }

        return $next($request);
    }
}
