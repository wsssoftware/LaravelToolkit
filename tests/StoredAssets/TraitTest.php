<?php

use Illuminate\Http\UploadedFile;
use LaravelToolkit\Facades\StoredAssets;
use LaravelToolkit\StoredAssets\Assets;
use LaravelToolkit\StoredAssets\StoredAssetModel;
use LaravelToolkit\Tests\Model\Product;
use LaravelToolkit\Tests\Model\ProductImageRecipe;

it('can cast a file to recipe', function () {
    $product = new Product;

    $product->image = UploadedFile::fake()->image('image.jpg');

    expect($product->image)->toBeInstanceOf(ProductImageRecipe::class);
});

it('do nothing on null value', function () {
    $product = new Product;

    $product->image = null;

    expect($product->image)->toBeNull();
});

it('can save a file', function () {
    Storage::fake('local');
    $product = new Product(['id' => 1]);
    $product->image = __DIR__.'/../TestCase.php';
    expect($product->save())
        ->toBeTrue()
        ->and($product->image_uuid)
        ->toBeUuid()
        ->and($product->image)
        ->toBeInstanceOf(Assets::class);
});

it('can fail on save', function () {
    Storage::fake('local');
    $product = new Product(['id' => 1]);
    $product->image = __DIR__.'/../TestCase.php';

    StoredAssets::partialMock()->shouldReceive('newModel')
        ->once()
        ->andReturn(new class extends StoredAssetModel
        {
            public function save(array $options = []): bool
            {
                return false;
            }
        });

    expect($product->save())
        ->toBeFalse();
});
