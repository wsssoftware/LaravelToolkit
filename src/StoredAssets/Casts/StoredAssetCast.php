<?php

namespace LaravelToolkit\StoredAssets\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

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
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $this->recipe::parse($model, $key, $value);
    }
}
