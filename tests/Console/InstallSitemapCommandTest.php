<?php

it('can install', function () {
    if (file_exists(base_path('routes/sitemap.php'))) {
        unlink(base_path('routes/sitemap.php'));
    }
    $this->artisan('laraveltoolkit:install_sitemap')
        ->expectsOutputToContain('Published Sitemap routes file.');
    expect(file_exists(base_path('routes/sitemap.php')))
        ->toBeTrue();
    $this->artisan('laraveltoolkit:install_sitemap')
        ->expectsOutputToContain('Sitemap routes file already exists.');
    $this->artisan('laraveltoolkit:install_sitemap --force')
        ->expectsOutputToContain('Published Sitemap routes file.');
});
