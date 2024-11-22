<?php

use Illuminate\Http\UploadedFile;
use LaravelToolkit\Facades\StoredAssets;
use LaravelToolkit\StoredAssets\Jobs\GarbageCollectorManager;
use LaravelToolkit\Tests\Model\Product;


it('can run all jobs', function () {
    \Illuminate\Support\Facades\Log::spy();
    $disk = Storage::fake('local');

    $validFakeTrashBinUuid = StoredAssets::trashBinDeadlineTimestamp().'-'.Str::uuid()->toString();
    $validFakeTrashBinPath = StoredAssets::trashBinPath($validFakeTrashBinUuid);
    $validFakeTrashBinPathname = $validFakeTrashBinPath.'test.txt';
    $disk->put($validFakeTrashBinPathname, '');

    $invalidFakeTrashBinUuid = StoredAssets::trashBinDeadlineTimestamp(now()->subMonth()).'-'.Str::uuid()->toString();
    $invalidFakeTrashBinPath = StoredAssets::trashBinPath($invalidFakeTrashBinUuid);
    $invalidFakeTrashBinPathname = $invalidFakeTrashBinPath.'test.txt';
    $disk->put($invalidFakeTrashBinPathname, '');

    Product::create([
        'id' => 1,
        'image' => UploadedFile::fake()->image('image.jpg'),
    ]);
    Product::create([
        'id' => 2,
        'image' => UploadedFile::fake()->image('image.jpg'),
    ]);
    Product::create([
        'id' => 3,
        'image' => UploadedFile::fake()->image('image.jpg'),
    ]);
    $product = Product::create([
        'id' => 4,
        'image' => UploadedFile::fake()->image('image.jpg'),
    ]);
    $uuid = $product->image_uuid;
    $assetPath = StoredAssets::path($uuid);
    $assetSubDir1 = $assetPath.'../';
    $assetSubDir2 = $assetSubDir1.'../';
    $product->delete();

    expect($disk->exists($assetPath))->toBeTrue()
        ->and($disk->exists($assetSubDir1))->toBeTrue()
        ->and($disk->exists($assetSubDir2))->toBeTrue()
        ->and($disk->exists($validFakeTrashBinPathname))->toBeTrue()
        ->and($disk->exists($invalidFakeTrashBinPathname))->toBeTrue();
    GarbageCollectorManager::dispatch();

    expect($disk->exists($assetPath))->toBeFalse()
        ->and($disk->exists($assetSubDir1))->toBeTrue()
        ->and($disk->exists($assetSubDir2))->toBeTrue()
        ->and($disk->exists($validFakeTrashBinPath))->toBeTrue()
        ->and($disk->exists($invalidFakeTrashBinPathname))->toBeFalse();
    GarbageCollectorManager::dispatch();

    expect($disk->exists($assetPath))->toBeFalse()
        ->and($disk->exists($assetSubDir1))->toBeFalse()
        ->and($disk->exists($assetSubDir2))->toBeTrue();
    GarbageCollectorManager::dispatch();

    expect($disk->exists($assetPath))->toBeFalse()
        ->and($disk->exists($assetSubDir1))->toBeFalse()
        ->and($disk->exists($assetSubDir2))->toBeFalse();

    Log::shouldHaveReceived('info')->with('In the trash bin on the "local" disk, 2 items were found, of which 1 item was deleted because its deadline had reached.');
    Log::shouldHaveReceived('info')->with('On stored assets, 1 item(s) was moved to trash bin.');
});
