<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PanNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 10 characters PAN format: 5 letters, 4 digits, 1 letter
        $regex = "/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/";

        if (!preg_match($regex, $value)) {
            $fail('The :attribute must be a valid Indian PAN number.');
        }
    }
}
