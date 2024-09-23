<?php

use Illuminate\Http\Request;
use LaravelToolkit\Facades\Filepond;
use LaravelToolkit\Filepond\UploadedFile;

it('can clear all files', function () {
    $disk = \Illuminate\Support\Facades\Storage::fake(Filepond::diskName());
    $disk->put(Filepond::path(Str::uuid(), 'text1.txt'), '');
    $disk->put(Filepond::path(Str::uuid(), 'text1.txt'), '');
    $disk->put(Filepond::path(Str::uuid(), 'text1.txt'), '');

    expect($disk->directories(Filepond::rootPath()))
        ->toHaveCount(3);
    Filepond::clearUploadFolder();
    expect($disk->directories(Filepond::rootPath()))
        ->toHaveCount(0);

    $id = Str::uuid();
    $disk->put(Filepond::path($id, 'text1.txt'), '');
    expect($disk->directories(Filepond::rootPath()))
        ->toHaveCount(1)
        ->and(Filepond::delete($id))
        ->toBeTrue()
        ->and($disk->directories(Filepond::rootPath()))
        ->toHaveCount(0);
});

it('can send request', function () {
    $id = Str::uuid()->toString();
    $disk = \Illuminate\Support\Facades\Storage::fake(Filepond::diskName());
    $disk->put(Filepond::path($id, 'text1.txt'), '');
    Route::post('foo-bar', function (Request $request) {
        expect($request->filepond('foo'))
            ->toBeInstanceOf(UploadedFile::class)
            ->and($request->input('foo'))
            ->toBeString()
            ->and($request->mergeFilepond('foo'))
            ->toBeNull()
            ->and($request->input('foo'))
            ->toBeInstanceOf(UploadedFile::class);
    });

    $this->post('foo-bar', ['foo' => $id])
        ->assertSuccessful();
});
