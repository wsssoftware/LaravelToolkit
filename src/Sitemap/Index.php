<?php

namespace LaravelToolkit\Sitemap;

use Saloon\XmlWrangler\Data\Element;

readonly class Index implements ToXml
{
    public function __construct(public string $group)
    {
        //
    }

    public function toXml(): Element
    {
        return Element::make([
            'loc' => route('lt.sitemap_group', ['group' => $this->group]),
        ]);
    }
}
