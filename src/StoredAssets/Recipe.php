<?php

namespace LaravelToolkit\StoredAssets;

use Exception;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use LaravelToolkit\StoredAssets\AssetIntents\AssetIntent;
use LaravelToolkit\StoredAssets\AssetIntents\PathAssetIntent;

abstract class Recipe implements Castable
{

    readonly private PathAssetIntent $baseAsset;

    protected function __construct(
        readonly protected Model $model,
        readonly protected string $field,
        mixed $source
    ) {
        $pathname = match (true) {
            $source instanceof File => $source->getPathname(),
            $source instanceof UploadedFile => $source->getPathname(),
            is_file($source) => new $source,
        };

        $this->baseAsset = new PathAssetIntent($pathname);
    }

    /**
     * @param  \LaravelToolkit\StoredAssets\AssetIntents\AssetIntent  $baseAsset
     * @return \LaravelToolkit\StoredAssets\AssetIntents\AssetIntent|\Illuminate\Support\Collection<int, \LaravelToolkit\StoredAssets\AssetIntents\AssetIntent>
     */
    abstract protected function prepareForSave(AssetIntent $baseAsset): AssetIntent|Collection;

    public function save(): string|false
    {
        $result = $this->prepareForSave($this->baseAsset);
        $assets =  ($result instanceof AssetIntent ? collect([$result]) : $result)
            ->ensure(AssetIntent::class);

        $uuid = Str::uuid()->toString();

        $storedAsset = StoredAsset::getFinalStoredAssetModel()::newModelInstance([
            'id' => $uuid,
            'model' => $this->model::class,
            'files' => [],
        ]);

        return $storedAsset->save() ? $uuid : false;
    }

    public static function parse(Model $model, string $field, mixed $source): self|string
    {
        if (is_file($source) || $source instanceof File || $source instanceof UploadedFile) {
            return new static($model, $field, $source);
        }
        throw_if($message = self::isInvalidUuid($model, $field, $source), Exception::class, $message);

        return $source;
    }

    private static function isInvalidUuid(Model $model, string $field, string $uuid): false|string
    {
        $base = sprintf('On field "%s" form model "%s", ', $field, $model::class);
        if (! Str::isUuid($uuid)) {
            return $base . sprintf('the the provided value "%s" does not appears to be a valid uuid.', $uuid);
        }
        $storedAssetTable = (new (StoredAsset::getFinalStoredAssetModel())())->getTable();
        if (StoredAsset::getFinalStoredAssetModel()::query()->whereId($uuid)->doesntExist()) {
            return $base . sprintf('the uuid "%s" does not exists on "%s" table.', $uuid, $storedAssetTable);
        }

        return false;
    }

    public static function castUsing(array $arguments): StoredAssetCast
    {
        return new StoredAssetCast(static::class);
    }
}
