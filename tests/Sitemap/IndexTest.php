<?php

use LaravelToolkit\Sitemap\Index;

it('can instantiate', function () {
    $index = new Index('users');
    expect($index)
        ->toBeInstanceOf(Index::class);
});
