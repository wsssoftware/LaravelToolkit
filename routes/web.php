<?php

use LaravelToolkit\Actions\Filepond\Fetch;
use LaravelToolkit\Actions\Filepond\Load;
use LaravelToolkit\Actions\Filepond\Process;
use LaravelToolkit\Actions\Filepond\ProcessChunk;
use LaravelToolkit\Actions\Filepond\Restore;
use LaravelToolkit\Actions\Filepond\Revert;
use LaravelToolkit\Actions\Flash\GetMessages;
use LaravelToolkit\Actions\Sitemap\RenderSitemap;
use LaravelToolkit\Deploy\MaintenanceController;
use LaravelToolkit\Facades\SEO;

Route::middleware('web')
    ->domain(config('laraveltoolkit.deploy.domain'))
    ->get(config('laraveltoolkit.deploy.path'), MaintenanceController::class)
    ->name('maintenance');

Route::middleware('web')->get('/lt/flash-get-messages', GetMessages::class)
    ->name('lt.flash.get_messages');

Route::middleware('web')->prefix('lt/filepond')->group(function () {
    Route::post('process', Process::class)->name('lt.filepond.process');
    Route::patch('process-chunk', ProcessChunk::class)->name('lt.filepond.process_chunk');
    Route::delete('revert', Revert::class)->name('lt.filepond.revert');
    Route::get('load', Load::class)->name('lt.filepond.load');
    Route::get('restore', Restore::class)->name('lt.filepond.restore');
    Route::get('fetch', Fetch::class)->name('lt.filepond.fetch');
});

if (config('laraveltoolkit.sitemap.default_routes')) {
    Route::middleware('web')->group(function () {
        Route::any('sitemap.xml', RenderSitemap::class)
            ->name('lt.sitemap');
        Route::any('sitemap-{index}.xml', RenderSitemap::class)
            ->name('lt.sitemap_group');

        Route::any('robots.txt', function () {
            $path = base_path('public/robots.stub');
            if (file_exists($path)) {
                $content = file_exists($path) ? file_get_contents($path) : "User-agent:\nDisallow: *";

                $sitemap = SEO::getRobotsTxtSitemap();
                $content .= ! empty($sitemap) ? "\n\nSitemap: ".$sitemap."\n\n" : '';
            } else {
                $content = SEO::robotsTxt();
            }

            return response($content)
                ->header('Content-Type', 'text/plain');
        });
    });

}
