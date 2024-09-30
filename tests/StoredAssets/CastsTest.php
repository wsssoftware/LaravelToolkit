<?php

use Illuminate\Http\UploadedFile;
use LaravelToolkit\StoredAssets\Casts\StoredAssetCast;
use LaravelToolkit\Tests\Model\Product;
use LaravelToolkit\Tests\Model\ProductImageRecipe;

it('can get and set StoredAssetCast', function () {
    $product = new Product;
    $cast = new StoredAssetCast;
    $recipe = ProductImageRecipe::parse($product, 'image', UploadedFile::fake()->image('image.jpg'));

    expect($cast->get($product, 'image', $recipe, []))
        ->toBeInstanceOf(ProductImageRecipe::class)
        ->and($cast->get($product, 'image', '', []))
        ->toEqual('');
});
