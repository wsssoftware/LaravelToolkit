<?php

namespace LaravelToolkit\Tests\Model;

use Illuminate\Support\Collection;
use LaravelToolkit\StoredAssets\AssetIntent;
use LaravelToolkit\StoredAssets\FilenameStoreType;
use LaravelToolkit\StoredAssets\Recipe;

class InvalidProductImageRecipe extends Recipe
{

    /**
     * @inheritDoc
     */
    protected function prepareForSave(AssetIntent $baseAsset): AssetIntent|Collection
    {
        return collect([
            $baseAsset->withKey('default'),
            AssetIntent::create($baseAsset->pathname)->withKey('default'),
        ]);
    }
}
