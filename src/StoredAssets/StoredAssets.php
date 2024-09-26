<?php

namespace LaravelToolkit\StoredAssets;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * @see \LaravelToolkit\Facades\StoredAssets
 */
class StoredAssets
{

    public function basePath(): string
    {
        return config('laraveltoolkit.stored_assets.path');
    }

    public function defaultDisk(): string
    {
        return config('laraveltoolkit.stored_assets.disk');
    }

    public function defaultFilenameStoreType(): FilenameStoreType
    {
        $value = config('laraveltoolkit.stored_assets.filename_store_type');
        return $value instanceof FilenameStoreType ? $value : FilenameStoreType::from($value);
    }

    public function isValidUuidImage(string $uuid): bool
    {
        if (!Str::isUuid($uuid)) {
            return false;
        }
       return StoredAssets::modelQuery()->where('id', $uuid)->exists();
    }

    public function newModel(array $attributes = []): StoredAssetModel
    {
        return $this->modelFQN()::newModelInstance($attributes);
    }

    /**
     * @return class-string<\LaravelToolkit\StoredAssets\StoredAssetModel>
     */
    public function modelFQN(): string
    {
        return config('laraveltoolkit.stored_assets.model');
    }

    public function modelQuery(): Builder
    {
        return $this->modelFQN()::query();
    }

    public function path(string $uuid, ?string $path = null): string
    {
        $part1 = substr($uuid, 0, $this->subdirectoryChars());
        $part2 = substr($uuid, $this->subdirectoryChars() * -1, $this->subdirectoryChars());
        return str($this->basePath())
            ->append(DIRECTORY_SEPARATOR)
            ->append($part1)
            ->append(DIRECTORY_SEPARATOR)
            ->append($part2)
            ->append(DIRECTORY_SEPARATOR)
            ->append($uuid)
            ->append(DIRECTORY_SEPARATOR)
            ->append($path ?? '')
            ->deduplicate(DIRECTORY_SEPARATOR);
    }

    public function subdirectoryChars(): int
    {
        return config('laraveltoolkit.stored_assets.subdirectory_chars');
    }
}
