<?php

namespace LaravelToolkit\Sitemap;

use Illuminate\Support\Carbon;

readonly class Url
{
    public function __construct(
        public string $loc,
        public ?Carbon $lastModified = null,
        public ?ChangeFrequency $changeFrequency = null,
        public ?float $priority = null,
    ) {
    }
}
