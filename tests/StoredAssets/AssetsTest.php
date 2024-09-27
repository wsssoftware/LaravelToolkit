<?php

use LaravelToolkit\StoredAssets\Asset;
use LaravelToolkit\StoredAssets\Assets;

it('test methods', function () {
    $uuid = Str::uuid()->toString();
    $assets = new Assets([
        'default' => new Asset($uuid, 'local', 'default', 'foo.zip', 'zip', 'app/zip', 12)
    ]);
    ray($assets);
    expect($assets->uuid)
        ->toEqual($uuid)
        ->and($assets->default)
        ->toBeInstanceOf(Asset::class);
});
