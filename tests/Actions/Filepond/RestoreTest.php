<?php

use LaravelToolkit\Facades\Filepond;

it('can restore a file', function () {
    Storage::fake(Filepond::diskName());
    $id = Str::uuid()->toString();
    Filepond::disk()->put(Filepond::path($id, 'foo.jpg'), 'content example');

    $this->get(route('lt.filepond.restore', ['id' => $id]))
        ->assertSuccessful()
        ->assertContent('content example')
        ->assertHeader('Content-Disposition', 'inline')
        ->assertHeader('Content-Type', 'application/jpeg')
        ->assertHeader('Filename', 'foo.zip');
});
