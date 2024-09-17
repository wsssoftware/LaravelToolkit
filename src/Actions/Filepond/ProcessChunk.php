<?php

namespace LaravelToolkit\Actions\Filepond;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProcessChunk
{
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json();
    }
}
