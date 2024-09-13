<?php

use LaravelToolkit\Actions\Flash\GetMessages;
use LaravelToolkit\Actions\Sitemap\RenderSitemap;

Route::middleware('web')->get('/lt/flash-get-messages', GetMessages::class)
    ->name('lt.flash.get_messages');

if (config('laraveltoolkit.sitemap.default_routes')) {
    Route::middleware('web')->group(function () {
        Route::any('sitemap.xml', RenderSitemap::class)
            ->name('lt.sitemap');
        Route::any('sitemap-{index}.xml', RenderSitemap::class)
            ->name('lt.sitemap_group');

        Route::any('robots.txt', function () {
            $path = base_path('public/robots.stub');
            $content = file_exists($path) ? file_get_contents($path) : "User-agent:\nDisallow: *";

            $content .= "\n\nSitemap: ".route('lt.sitemap')."\n\n";

            return response($content)
                ->header('Content-Type', 'text/plain');
        });
    });

}
