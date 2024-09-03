<?php

namespace LaravelToolkit\Actions\Flash;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use LaravelToolkit\Facades\Flash;
use LaravelToolkit\Flash\FlashResource;

class GetMessages
{
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json(FlashResource::collection(Flash::getMessages()));
    }
}
