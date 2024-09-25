<?php

namespace LaravelToolkit\StoredAssets;

readonly class Asset
{
    public function __construct(
        public string $label,
        public string $path,
        public string $filename,
        public string $extension,
        public string $mimeType,
        public string $size,
    ) {
    }
}
