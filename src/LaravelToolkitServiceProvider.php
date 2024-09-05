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
        (new CollectionMacro())();
        return parent::boot();

    }
}
