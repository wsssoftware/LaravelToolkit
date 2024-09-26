<?php

namespace LaravelToolkit\StoredAssets\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use LaravelToolkit\StoredAssets\Asset;
use LaravelToolkit\StoredAssets\Assets;

class AssetsCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (json_validate($value)) {
            $uuid = $model->id;
            $assets = new Assets(
                $uuid,
                collect(json_decode($value, true))
                ->mapWithKeys(fn(array $asset) => [$asset['k'] => Asset::fromDatabase($uuid, $asset)])
                ->all()
            );
            return $assets;
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
        if ($value instanceof Assets) {
            return json_encode($value->toDatabase());
        }
        return $value;
    }
}
