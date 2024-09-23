<?php

namespace LaravelToolkit;

use Illuminate\Support\Facades\Blade;
use LaravelToolkit\Macros\CollectionMacro;
use LaravelToolkit\Macros\RequestMacro;
use LaravelToolkit\Routing\Redirector as PackageRedirector;
use LaravelToolkit\SEO\SEOComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelToolkitServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laraveltoolkit')
            ->hasConfigFile('laraveltoolkit')
            ->hasRoute('web')
            ->hasTranslations()
            ->hasViews();

        $this->addPublishGroup('laraveltoolkit-sitemap', [
            dirname(__DIR__).'/routes/sitemap.php' => base_path('routes/sitemap.php'),
        ]);
    }

    public function boot(): self
    {
        (new CollectionMacro)();
        (new RequestMacro)();

        setlocale(
            LC_ALL,
            config('app.locale').'.UTF-8',
            config('app.locale'),
            config('app.fallback_locale').'.UTF-8',
            config('app.fallback_locale'),
            'en.UTF-8',
            'en',
            'en_US.UTF-8',
            'en_US',
        );

        Blade::component('seo', SEOComponent::class);

        if (config('laraveltoolkit.extended_redirector')) {
            $this->app->extend('redirect', fn () => app(PackageRedirector::class));
        }

        return parent::boot();

    }
}
