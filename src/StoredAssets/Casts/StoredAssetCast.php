<?php

namespace LaravelToolkit\StoredAssets\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use LaravelToolkit\StoredAssets\AssetIntent;
use LaravelToolkit\StoredAssets\HasStoredAssets;

class StoredAssetCast implements CastsAttributes
{

    /**
     * @param  class-string<\LaravelToolkit\StoredAssets\Recipe>  $recipe
     */
    public function __construct(
        protected string $recipe
    ) {
        //
    }

    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value instanceof AssetIntent) {
            return $value;
        }
        if ($model->isRelation($key) && in_array(HasStoredAssets::class, class_uses_recursive($model::class))) {
            return $model->getRelationValue($key)->assets;
        }

        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }
}
