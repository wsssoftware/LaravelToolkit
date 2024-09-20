<?php

namespace LaravelToolkit\Filepond;

use LaravelToolkit\Facades\Filepond;

class UploadedFile extends \Illuminate\Http\UploadedFile
{
    public static function fromId(?string $id): ?self
    {
        $disk = Filepond::disk();
        $files = $disk->files(Filepond::path($id ?? '0'));
        if (count($files) !== 1) {
            return null;
        }

        return new self(
            $disk->path($files[0]),
            basename($files[0]),
            $disk->mimeType($files[0]),
        );
    }
}
