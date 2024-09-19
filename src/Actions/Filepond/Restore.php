<?php

namespace LaravelToolkit\Actions\Filepond;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use LaravelToolkit\Facades\Filepond;
use LaravelToolkit\Filepond\Abortable;

class Restore
{
    public function __invoke(Request $request): Response
    {
        $id = $request->input('id');
        $disk = Filepond::disk();

        abort_if(empty($id), Abortable::make('No upload id provided', 404));
        $files = $disk->files(Filepond::path($id));
        abort_if(count($files) !== 1, Abortable::make('Invalid upload', 404));

        return response($disk->get($files[0]), 200, [
            'Content-Disposition' => 'inline',
            'Filename' => basename($files[0]),
            'Content-Type' => $disk->mimeType($files[0]),
        ]);
    }
}
