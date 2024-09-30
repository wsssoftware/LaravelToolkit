<?php

namespace LaravelToolkit\Tests\Model;

use Illuminate\Support\Collection;
use LaravelToolkit\StoredAssets\AssetIntent;
use LaravelToolkit\StoredAssets\FilenameStoreType;
use LaravelToolkit\StoredAssets\Recipe;

class ProductImageRecipe extends Recipe
{
    /**
     * {@inheritDoc}
     */
    protected function prepareForSave(AssetIntent $baseAsset): AssetIntent|Collection
    {
        //        return collect([
        //            $baseAsset->withDisk('local')->withKey('default')->withFilenameStoreType(FilenameStoreType::KEY),
        //            AssetIntent::create($baseAsset->pathname)->withDisk('local')
        //                ->withFilenameStoreType(FilenameStoreType::KEY)
        //                ->withKey('thumbnail_dsa_dsadsa'),
        //        ]); or

        return $baseAsset->withDisk('local')->withKey('default');
    }
}
