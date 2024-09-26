<?php

namespace LaravelToolkit\StoredAssets;

use Illuminate\Support\Str;

enum FilenameStoreType: string
{
    case UUID = 'uuid';
    case KEY = 'key';

    public function getFilename(AssetIntent $intent, string $extension = null): string
    {
        $name = ($this === self::KEY ? $intent->getKey() : Str::uuid()->toString());
        return $name . (!empty($extension) ? '.' . $extension : '');
    }
}
