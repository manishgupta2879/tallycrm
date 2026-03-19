<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Inactivity Timeout (minutes)
    |--------------------------------------------------------------------------
    | User is auto-logged out if no HTTP request is made for this many minutes.
    | You can also override this via .env:  AUTO_LOGOUT_TIMEOUT=30
    */
    'timeout' => (int) env('AUTO_LOGOUT_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Single Session (Browser-Close Enforcement)
    |--------------------------------------------------------------------------
    | When true, logging in on a new browser / new session will invalidate any
    | previously active session for that user account.
    */
    'single_session' => (bool) env('AUTO_LOGOUT_SINGLE_SESSION', true),

    /*
    |--------------------------------------------------------------------------
    | Warning Before Logout (seconds)
    |--------------------------------------------------------------------------
    | Seconds before timeout at which the JS countdown modal is shown.
    | Set to 0 to disable the warning modal entirely.
    */
    'warning_before' => (int) env('AUTO_LOGOUT_WARNING', 60),
];
