<?php

use LaravelToolkit\Sitemap\Index;
use Saloon\XmlWrangler\Data\Element;

it('can instantiate', function () {
    $url = new Index('users');
    expect($url)
        ->toBeInstanceOf(Index::class)
        ->and($url->toXml())
        ->toBeInstanceOf(Element::class);
});
