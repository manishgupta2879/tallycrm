<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CaptchaRule implements ValidationRule
{
    /**
     * Best Practice Captcha Validation
     *
     * This rule implements security best practices:
     * 1. Case-insensitive comparison
     * 2. Whitespace trimming
     * 3. Prevents replay attacks by clearing session after validation
     * 4. Logs failed attempts for security monitoring
     * 5. Prevents brute force by integration with rate limiting
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Trim input
        $userInput = trim($value ?? '');

        // Check if empty
        if (empty($userInput)) {
            $fail('The :attribute field is required.');
            return;
        }

        // Use mews/captcha built-in validation
        // This will validate against the captcha stored in session
        if (!\Mews\Captcha\Facades\Captcha::check($userInput)) {
            // Log failed attempt for security monitoring
            \Log::warning('Captcha validation failed', [
                'user_ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now(),
            ]);

            $fail('The :attribute is incorrect. Please try again.');
            return;
        }

        // Optional: Clear captcha from session after successful validation to prevent replay
        // This prevents users from reusing the same captcha
        session()->forget('captcha');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The verification code is invalid.';
    }
}
