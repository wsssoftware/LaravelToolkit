<?php

use Illuminate\Http\UploadedFile;
use LaravelToolkit\Facades\Filepond;

it('can process a normal file', function () {
    $response = $this->post(route('lt.filepond.process'), ['filepond' => UploadedFile::fake()->image('image.jpg')]);


    Filepond::disk()->assertExists(Filepond::path($response->content(), 'image.jpg'));
});
