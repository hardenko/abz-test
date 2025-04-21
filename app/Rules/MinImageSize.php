<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

final readonly class MinImageSize implements ValidationRule
{
    private const MIN_WIDTH = 70;

    private const MIN_HEIGHT = 70;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! ($value instanceof UploadedFile)) {
            $fail('The photo must be a valid image.');

            return;
        }

        $dimensions = getimagesize($value->getPathname());

        if (! $dimensions) {
            $fail('The photo must be a valid image.');

            return;
        }

        [$width, $height] = $dimensions;

        if ($width < self::MIN_WIDTH || $height < self::MIN_HEIGHT) {
            $fail('The photo must be at least '.self::MIN_WIDTH.'x'.self::MIN_HEIGHT.' pixels.');
        }
    }
}
