<?php

namespace LaravelToolkit;

use LaravelToolkit\Macros\CollectionMacro;
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
            ->hasTranslations();
        //            ->hasViews()
        //            ->hasMigration('create_laraveltoolkit_table')
        //            ->hasCommand(LaravelToolkitCommand::class);
    }

    public function boot(): self
    {
        (new CollectionMacro)();

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

        return parent::boot();

    }
}
