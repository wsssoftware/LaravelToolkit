<?php

namespace LaravelToolkit\Facades;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 *
 * @method static string apply(string $input, string $mask)
 *
 * @see \LaravelToolkit\Support\Mask
 */
class Mask extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \LaravelToolkit\Support\Mask::class;
    }
}
