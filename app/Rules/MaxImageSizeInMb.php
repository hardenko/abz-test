<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

final readonly class MaxImageSizeInMb implements ValidationRule
{
    public function __construct(private int $maxMb = 5)
    {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!($value instanceof UploadedFile)) {
            return;
        }

        if ($value->getSize() > $this->maxMb * 1024 * 1024) {
            $fail("The $attribute may not be greater than $this->maxMb Mbytes.");
        }
    }
}
