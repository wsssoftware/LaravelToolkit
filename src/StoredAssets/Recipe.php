<?php

namespace LaravelToolkit\StoredAssets;

use Illuminate\Contracts\Database\Eloquent\Castable;

abstract class Recipe implements Castable
{
    public static function castUsing(array $arguments): string
    {
        return StoredAssetCast::class;
    }
}
