<?php

namespace LaravelToolkit\StoredAssets;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

readonly class Asset implements Arrayable
{
    public function __construct(
        public string $uuid,
        public string $disk,
        public string $key,
        public string $pathname,
        public string $extension,
        public string $mimeType,
        public int $size,
    ) {
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'disk' => $this->disk,
            'key' => $this->key,
            'pathname' => $this->pathname,
            'extension' => $this->extension,
            'mimeType' => $this->mimeType,
            'size' => $this->size,
        ];
    }

    public static function fromDatabase(string $uuid, array $data): self
    {
        return new self(
            $uuid,
            Arr::get($data, 'd'),
            Arr::get($data, 'k'),
            Arr::get($data, 'p'),
            Arr::get($data, 'e'),
            Arr::get($data, 'm'),
            Arr::get($data, 's'),
        );
    }

    public function toDatabase(): array
    {
        return [
            'd' => $this->disk,
            'k' => $this->key,
            'p' => $this->pathname,
            'e' => $this->extension,
            'm' => $this->mimeType,
            's' => $this->size,
        ];
    }
}