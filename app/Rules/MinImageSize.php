<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

final readonly class MinImageSize implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!($value instanceof UploadedFile)) {
            $fail('The photo must be a valid image.');
            return;
        }

        $dimensions = getimagesize($value->getPathname());

        if (!$dimensions) {
            $fail('The photo must be a valid image.');
            return;
        }

        [$width, $height] = $dimensions;

        if ($width < 70 || $height < 70) {
            $fail('The photo must be at least 70x70 pixels.');
        }
    }
}
