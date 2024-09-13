<?php

use LaravelToolkit\Sitemap\Index;
use Saloon\XmlWrangler\Data\Element;

it('can instantiate', function () {
    $this->artisan('laraveltoolkit:install_sitemap --force')
        ->expectsOutputToContain('Published Sitemap routes file.');
    $index = new Index('users');
    expect($index)
        ->toBeInstanceOf(Index::class)
        ->and($index->toXml())
        ->toBeInstanceOf(Element::class);
});
