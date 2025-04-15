<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SyrianPhoneNumberRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Syrian phone numbers start with +963 or 09 followed by 8 digits
        if (!preg_match('/^(\+9639|09)\d{8}$/', $value)) {
            $fail("The $attribute must be a valid Syrian phone number : start with +963 or 09 followed by 8 digits");
        }
    }
}
