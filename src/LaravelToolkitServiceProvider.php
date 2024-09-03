<?php

namespace LaravelToolkit;

use LaravelToolkit\Commands\LaravelToolkitCommand;
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
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laraveltoolkit_table')
            ->hasCommand(LaravelToolkitCommand::class);
    }
}
