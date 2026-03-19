<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AutoLogout
{
    /**
     * Handle an incoming request.
     * - Logs out user if inactive for more than configured timeout.
     * - Logs out user if their session_token doesn't match the stored one
     *   (browser was closed / new login on another browser).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $timeoutSeconds = config('auto_logout.timeout', 30) * 60;
        $now = time();

        // ── 1. INACTIVITY CHECK ───────────────────────────────────────────
        $lastActivity = session('_last_activity');

        if ($lastActivity && ($now - $lastActivity) > $timeoutSeconds) {
            return $this->forceLogout($request, 'Your session expired due to inactivity. Please log in again.');
        }

        // Update heartbeat
        session(['_last_activity' => $now]);

        // ── 2. SINGLE-SESSION CHECK ───────────────────────────────────────
        if (config('auto_logout.single_session', true)) {
            $user = Auth::user();
            $storedToken = $user->session_token;          // from users table
            $sessionToken = session('_session_token');     // from current session

            // If there's a stored token and it doesn't match current session → another login happened
            if ($storedToken && $sessionToken !== $storedToken) {
                return $this->forceLogout($request, 'You were logged in from another location. Please log in again.');
            }
        }

        return $next($request);
    }

    // ─────────────────────────────────────────────────────────────────────────

    private function forceLogout(Request $request, string $message): Response
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('error', $message);
    }
}
