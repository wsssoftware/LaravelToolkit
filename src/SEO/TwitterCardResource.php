<?php

namespace LaravelToolkit\SEO;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \LaravelToolkit\SEO\TwitterCard $resource
 */
class TwitterCardResource extends JsonResource
{
    public function __construct($resource)
    {
        self::withoutWrapping();
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->resource->title
        ];
    }
}
