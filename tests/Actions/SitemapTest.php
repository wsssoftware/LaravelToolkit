<?php

use Illuminate\Support\Number;
use LaravelToolkit\Actions\Sitemap\RenderSitemap;
use LaravelToolkit\Facades\Sitemap;

it('can render', function () {
    config()->set('laraveltoolkit.sitemap.timeout', 120);
    $this->get(route('lt.sitemap'))
        ->assertSuccessful();
    $lastModified = filemtime(base_path('routes/sitemap.php'));
    $cacheKey = 'lt.sitemap.'.sha1('localhost'.'::::'.$lastModified);
    expect(Cache::has($cacheKey))->toBeTrue();
});

it('can render without cache', function () {
    config()->set('laraveltoolkit.sitemap.cache', false);
    $this->get(route('lt.sitemap'))
        ->assertSuccessful();
    $lastModified = filemtime(base_path('routes/sitemap.php'));
    $cacheKey = 'lt.sitemap.'.sha1('localhost'.'::::'.$lastModified);
    expect(Cache::has($cacheKey))->toBeFalse();
});

it('log on large files', function () {
    config()->set('laraveltoolkit.sitemap.max_file_size', 1024);
    $rs = new RenderSitemap;
    $content = str_repeat('1', 2_000);
    $url = new \LaravelToolkit\Sitemap\Url($content);
    Sitemap::addUrl($url);

    $maxAllowedSize = intval(config('laraveltoolkit.sitemap.max_file_size'));
    Log::shouldReceive('warning')
        ->with(sprintf(
            'The sitemap file size limit of %s was exceeded. This may cause search engines to reject it.',
            Number::fileSize($maxAllowedSize, 2),
        ), Mockery::andAnyOtherArgs())
        ->andThrow(Exception::class, 'large files');

    expect(fn () => $rs(request()))
        ->toThrow('large files');
});

it('log on large items count', function () {
    config()->set('laraveltoolkit.sitemap.max_file_items', 100);
    /** @var \Illuminate\Support\Collection $items */
    $items = (new ReflectionClass(Sitemap::getFacadeRoot()::class))
        ->getProperty('items')
        ->getValue(Sitemap::getFacadeRoot());
    $rs = new RenderSitemap;
    for ($i = 1; $i <= 101; $i++) {
        $items->push(new \LaravelToolkit\Sitemap\Url("loc $i"));
    }
    Log::shouldReceive('warning')
        ->with('The sitemap items count limit of 100 was exceeded in 1. This may cause search engines to reject it.')
        ->andThrow(Exception::class, 'large count');

    expect(fn () => $rs(request()))
        ->toThrow('large count');
});
