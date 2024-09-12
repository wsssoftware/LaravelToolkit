<?php

namespace LaravelToolkit\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use LaravelToolkit\LaravelToolkitServiceProvider;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\TestCase as Orchestra;

#[WithMigration]
class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'LaravelToolkit\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelToolkitServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(dirname(__DIR__).'/workbench/database/migrations');
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('app.locale', 'pt_BR');
        config()->set('app.key', 'base64:Z1sxfk3d54CWnssAxvEFshoZVGmAO7KrbZGMzU5xko4=');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laraveltoolkit_table.php.stub';
        $migration->up();
        */
    }
}
