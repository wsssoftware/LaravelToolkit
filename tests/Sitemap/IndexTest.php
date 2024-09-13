<?php

use LaravelToolkit\Sitemap\Index;
use Saloon\XmlWrangler\Data\Element;

it('can instantiate', function () {
    $this->artisan('laraveltoolkit:install_sitemap --force')
        ->expectsOutputToContain('Published Sitemap routes file.');
    $url = new Index('users');
    expect($url)
        ->toBeInstanceOf(Index::class)
        ->and($url->toXml())
        ->toBeInstanceOf(Element::class);
});
