<?php

namespace LaravelToolkit\Facades;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Facade;
use LaravelToolkit\StoredAssets\FilenameStoreType;
use LaravelToolkit\StoredAssets\StoredAssetModel;

/**
 * @method static string basePath()
 * @method static string defaultDisk()
 * @method static FilenameStoreType defaultFilenameStoreType()
 * @method static bool isValidUuidImage(string $uuid)
 * @method static StoredAssetModel newModel(array $attributes = [])
 * @method static string modelFQN()
 * @method static Builder modelQuery()
 * @method static string path(string $uuid, ?string $path = null)
 * @method static int subdirectoryChars()
 *
 * @see \LaravelToolkit\StoredAssets\StoredAssets
 */
class StoredAssets extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \LaravelToolkit\StoredAssets\StoredAssets::class;
    }
}
