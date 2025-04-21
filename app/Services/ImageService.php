<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

use function Tinify\fromFile;
use function Tinify\setKey;

final class ImageService
{
    public function store(UploadedFile $file): string
    {
        $filename = uniqid('user_').'.jpg';
        $tempPath = storage_path("app/temp/{$filename}");
        $optimizedPath = storage_path("app/public/photos/{$filename}");

        if (! is_dir(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }

        if (! is_dir(dirname($optimizedPath))) {
            mkdir(dirname($optimizedPath), 0755, true);
        }

        $file->move(dirname($tempPath), $filename);

        setKey(config('services.tinify.key'));

        fromFile($tempPath)->resize([
            'method' => 'cover',
            'width' => 70,
            'height' => 70,
        ])->toFile($optimizedPath);

        unlink($tempPath);

        return asset("storage/photos/{$filename}");
    }
}
