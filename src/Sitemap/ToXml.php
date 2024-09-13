<?php

namespace LaravelToolkit\Sitemap;

use DOMDocument;
use DOMElement;

interface ToXml
{
    public function toXml(DOMDocument $xml, DOMElement $root): void;
}
