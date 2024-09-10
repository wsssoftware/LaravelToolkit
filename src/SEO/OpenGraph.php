<?php

namespace LaravelToolkit\SEO;

class OpenGraph
{
    public ?string $title;
    public ?string $description;
    public ?Image $image;

    public function __construct(
        public bool $format,
        public bool $propagation,
    ) {
    }
}
