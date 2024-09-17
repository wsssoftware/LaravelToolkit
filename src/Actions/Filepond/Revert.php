<?php

namespace LaravelToolkit\Actions\Filepond;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use LaravelToolkit\Facades\Filepond;

class Revert
{
    public function __invoke(Request $request): JsonResponse
    {
        $path = Filepond::path($request->getContent());
        $disk = Filepond::disk();
        if ($disk->directoryExists($path)) {
            $disk->deleteDirectory($path);
        }
        return response()->json();
    }
}
