<?php

namespace App\Helpers;

use Illuminate\Support\Str;

final class ConvertArrayKeys
{
    public static function toCamelCase(array $data): array
    {
        return self::convert($data, 'camel');
    }

    public static function toSnakeCase(array $data): array
    {
        return self::convert($data, 'snake');
    }

    private static function convert(array $data, string $typeOfKey): array
    {
        foreach ($data as $key => $item) {
            $preparedKey = Str::$typeOfKey($key);
            if ($preparedKey !== $key) {
                $data[Str::$typeOfKey($key)] = $item;

                unset($data[$key]);
            }
        }

        return $data;
    }
}
