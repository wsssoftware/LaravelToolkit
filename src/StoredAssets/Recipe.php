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

    public function ensureNotDuplicated(Collection $assets): void
    {
        $assets->unique(fn($o) => spl_object_id($o))
            ->groupBy(fn(AssetIntent $intent) => $intent->getKey())
            ->each(fn(Collection $group, string $key) => throw_if($group->count() > 1, Exception::class, sprintf(
                'You may not has two asset with same name key. %s found on "%s"',
                $group->count(),
                $key
            )));
    }

    public function save(): string|false
    {
        $result = $this->prepareForSave($this->baseAsset);
        $assets = ($result instanceof AssetIntent ? collect([$result]) : $result)->ensure(AssetIntent::class);
        $this->ensureNotDuplicated($assets);
        $uuid = Str::uuid()->toString();

        $assets = $assets->reduce(
            fn(Assets $carry, AssetIntent $intent) => $carry->put($intent->getKey(), $intent->store($uuid)),
            new Assets()
        );

        $data = ['id' => $uuid, 'model' => $this->model::class, 'field' => $this->field, 'assets' => $assets];
        return StoredAssets::newModel($data)->save()
            ? $uuid
            : false;
    }

    public static function parse(Model $model, string $field, mixed $source): self|string
    {
        if (is_file($source) || $source instanceof File || $source instanceof UploadedFile) {
            return new static($model, $field, $source);
        } elseif ($source instanceof self) {
            return $source;
        }
        throw_if(!StoredAssets::isValidUuidAsset($source), Exception::class, sprintf(
            'On field "%s" from model "%s", the the provided value "%s" does not appears to be a valid uuid or does not exists on "%s" table.',
            $field,
            $model::class,
            $source,
            StoredAssets::newModel()->getTable()
        ));

        return $source;
    }

    public static function castUsing(array $arguments): StoredAssetCast
    {
        return new StoredAssetCast(static::class);
    }
}
