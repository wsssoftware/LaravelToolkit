<?php

use LaravelToolkit\Actions\Flash\GetMessages;
use LaravelToolkit\Actions\Sitemap\RenderSitemap;

Route::middleware('web')->get('/lt/flash-get-messages', GetMessages::class)
    ->name('lt.flash.get_messages');

if (config('laraveltoolkit.sitemap.default_route')) {
    Route::middleware('web')->get('sitemap.xml', RenderSitemap::make())
        ->name('lt.sitemap.xml');
}
