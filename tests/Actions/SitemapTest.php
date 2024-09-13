<?php

use LaravelToolkit\Actions\Sitemap\RenderSitemap;
use LaravelToolkit\Facades\Sitemap;

it('can render', function () {
    config()->set('laraveltoolkit.sitemap.timeout', 120);
    $this->get(route('lt.sitemap'))
        ->assertSuccessful();
    $lastModified = filemtime( base_path('routes/sitemap.php'));
    $cacheKey = 'lt.sitemap.'.sha1('localhost'.'::::'.$lastModified);
    expect(Cache::has($cacheKey))->toBeTrue();
});

it('can render without cache', function () {
    config()->set('laraveltoolkit.sitemap.cache', false);
    $this->get(route('lt.sitemap'))
        ->assertSuccessful();
    $lastModified = filemtime( base_path('routes/sitemap.php'));
    $cacheKey = 'lt.sitemap.'.sha1('localhost'.'::::'.$lastModified);
    expect(Cache::has($cacheKey))->toBeFalse();
});

it('log on large files', function () {
    ini_set('memory_limit', '-1');
    $rs = new RenderSitemap();
    for ($i = 1; $i <= 55; $i++) {
        Sitemap::addUrl(str_repeat('1', 55_000_000));
    }
    Log::shouldReceive('warning')
        ->with('The sitemap file size limit of 50.00 MB was exceeded in 2.45 MB. This may cause search engines to reject it.')
        ->andThrow(Exception::class, 'large files');

    expect(fn() => $rs(request()))
        ->toThrow('large files');
});

it('log on large items count', function () {
    ini_set('memory_limit', '-1');
    /** @var \Illuminate\Support\Collection $items */
    $items = (new ReflectionClass(Sitemap::getFacadeRoot()::class))
        ->getProperty('items')
        ->getValue(Sitemap::getFacadeRoot());
    $rs = new RenderSitemap();
    for ($i = 1; $i <= 5_001; $i++) {
        $items->push(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
    }
    Log::shouldReceive('warning')
        ->with('The sitemap items count limit of 50,000 was exceeded in 10. This may cause search engines to reject it.')
        ->andThrow(Exception::class, 'large count');

    expect(fn() => $rs(request()))
        ->toThrow('large count');
});
