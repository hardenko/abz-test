<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final readonly class ValidPhone implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('/^\+380\d{9}$/', $value)) {
            $fail('The phone number must be in the format +380XXXXXXXXX.');
        }
    }
}
