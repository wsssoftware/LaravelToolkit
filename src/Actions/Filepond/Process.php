<?php

namespace LaravelToolkit\Actions\Filepond;

use Illuminate\Http\Request;
use LaravelToolkit\Facades\Filepond;

class Process
{
    public function __invoke(Request $request)
    {
        $id = Filepond::generateId();
        $input = $request->file('filepond');

        if (empty($input)) {
            $dontHasLength = ! is_numeric($request->header('upload_length'));
            abort_if($dontHasLength, 500, 'Invalid upload', ['Content-Type' => 'text/plain']);
            Filepond::disk()->createDirectory(Filepond::path($id));

            return response($id, 200, ['Content-Type' => 'text/plain']);
        }

        $file = is_array($input) ? $input[0] : $input;
        $savedFile = $file->storeAs(
            Filepond::path($id),
            $file->getClientOriginalName(),
            Filepond::diskName(),
        );

        abort_if(! $savedFile, 500, 'Could not save file', ['Content-Type' => 'text/plain']);

        defer(fn () => Filepond::garbageCollector());

        return response($id, 200, ['Content-Type' => 'text/plain']);
    }
}
