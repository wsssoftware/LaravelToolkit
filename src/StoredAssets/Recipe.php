<?php

namespace LaravelToolkit\StoredAssets;

use Exception;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use LaravelToolkit\Facades\StoredAssets;
use LaravelToolkit\StoredAssets\Casts\StoredAssetCast;

abstract class Recipe implements Castable
{

    readonly private AssetIntent $baseAsset;

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
        $this->baseAsset = AssetIntent::create($pathname);
    }

    /**
     * @param  \LaravelToolkit\StoredAssets\AssetIntent  $baseAsset
     * @return \LaravelToolkit\StoredAssets\AssetIntent|\Illuminate\Support\Collection<int, \LaravelToolkit\StoredAssets\AssetIntent>
     */
    abstract protected function prepareForSave(AssetIntent $baseAsset): AssetIntent|Collection;

    public function save(): string|false
    {
        $result = $this->prepareForSave($this->baseAsset);
        $assets = ($result instanceof AssetIntent ? collect([$result]) : $result)
            ->ensure(AssetIntent::class);
        $assets->groupBy(fn(AssetIntent $intent) => $intent->getKey())
            ->each(fn(Collection $group, string $key) => throw_if($group->count() > 1, Exception::class, sprintf(
                'You may not has two asset with same name key. %s found on "%s"',
                $group->count(),
                $key
            )));

        $uuid = Str::uuid()->toString();

        $assets = $assets->reduce(
            fn(Assets $carry, AssetIntent $assetIntent) => $carry->put($assetIntent->getKey(),
                $assetIntent->store($uuid)),
            new Assets($uuid)
        );

        $storedAsset = StoredAssets::newModel([
            'id' => $uuid,
            'model' => $this->model::class,
            'assets' => $assets,
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
        if (!Str::isUuid($uuid)) {
            return $base.sprintf('the the provided value "%s" does not appears to be a valid uuid.', $uuid);
        }
        $model = StoredAssets::newModel();
        $storedAssetTable = $model->getTable();
        if (StoredAssets::modelQuery()->whereId($uuid)->doesntExist()) {
            return $base.sprintf('the uuid "%s" does not exists on "%s" table.', $uuid, $storedAssetTable);
        }

        return false;
    }

    public static function castUsing(array $arguments): StoredAssetCast
    {
        return new StoredAssetCast(static::class);
    }
}
