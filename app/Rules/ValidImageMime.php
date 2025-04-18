<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

final readonly class ValidImageMime implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value instanceof UploadedFile) {
            $fail("The $attribute must be a valid image file.");
        }

        $allowedMimeTypes = ['image/jpeg', 'image/jpg'];

        if (!in_array($value->getMimeType(), $allowedMimeTypes)) {
            $fail("The $attribute must be a file of type: jpeg, jpg.");
        }
    }
}
