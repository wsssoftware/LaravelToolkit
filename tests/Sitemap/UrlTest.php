<?php

use LaravelToolkit\Sitemap\ChangeFrequency;
use LaravelToolkit\Sitemap\Url;
use Saloon\XmlWrangler\Data\Element;

it('can instantiate', function () {
    $url = new Url(
        'http://example.com/',
        now()->subWeek(),
        ChangeFrequency::ALWAYS,
        0.5
    );
    $url2 = new Url(
        'http://example.com/',
    );
    expect($url)
        ->toBeInstanceOf(Url::class)
        ->and($url->toXml())
        ->toBeInstanceOf(Element::class)
        ->and($url2)
        ->toBeInstanceOf(Url::class)
        ->and($url2->toXml())
        ->toBeInstanceOf(Element::class);
});
