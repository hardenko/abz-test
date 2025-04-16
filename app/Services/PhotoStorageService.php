<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

final readonly class PhotoStorageService
{
    public function store(UploadedFile $file): string
    {
        $path = $file->store('public/photos');
        return asset(str_replace('public/', 'storage/', $path));
    }
}

