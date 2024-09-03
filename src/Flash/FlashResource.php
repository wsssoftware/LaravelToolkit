<?php

namespace LaravelToolkit\Flash;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \LaravelToolkit\Flash\Message $resource
 */
class FlashResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'severity' => $this->resource->severity->value,
            'summary' => $this->when($this->resource->summary !== null, $this->resource->summary),
            'detail' => $this->resource->detail,
            'closable' => $this->when($this->resource->closable !== null, $this->resource->closable),
            'life' => $this->when($this->resource->life !== null, $this->resource->life),
            'group' => $this->when($this->resource->group !== null, $this->resource->group),
        ];
    }
}