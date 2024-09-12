<?php

namespace LaravelToolkit\Sitemap;

use Saloon\XmlWrangler\Data\Element;

interface ToXml
{
    public function toXml(): Element;
}
