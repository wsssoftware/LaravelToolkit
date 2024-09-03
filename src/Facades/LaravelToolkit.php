<?php

namespace LaravelToolkit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LaravelToolkit\LaravelToolkit
 */
class LaravelToolkit extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \LaravelToolkit\LaravelToolkit::class;
    }
}
