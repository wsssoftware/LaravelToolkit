<?php

use LaravelToolkit\Facades\SEO;
use LaravelToolkit\SEO\SEOComponent;

it('', function () {
    $result = SEOComponent::resolve(['payload' => SEO::payload()]);
    expect($result->render()->render())
        ->toBeString()
        ->toStartWith('<title');
});
