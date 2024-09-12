<?php

namespace LaravelToolkit\Sitemap;

use Illuminate\Support\Carbon;
use Saloon\XmlWrangler\Data\Element;

readonly class Url implements ToXml
{
    public function __construct(
        public string $loc,
        public ?Carbon $lastModified = null,
        public ?ChangeFrequency $changeFrequency = null,
        public ?float $priority = null,
    ) {
    }

    public function toXml(): Element
    {
        $data = [
            'loc' => $this->loc,
            'lastmod' => $this->lastModified?->format('Y-m-d'),
            'changefreq' => $this->changeFrequency?->value,
            'priority' => $this->priority,
        ];
        foreach ($data as $key => $value) {
            if ($value === null) {
                unset($data[$key]);
            }
        }
        return Element::make($data);
    }
}
