<?php

namespace LaravelToolkit\SEO;

readonly class Image
{
    public function __construct(
        public string $disk,
        public string $path,
        public ?string $alt,
    ) {
        //
    }
}
