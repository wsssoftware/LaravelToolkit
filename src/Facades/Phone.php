<?php

namespace LaravelToolkit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 *
 * @method static string checkDigitsFromCnpj(string $cnpj)
 *
 * @see \LaravelToolkit\Support\Phone
 */
class Phone extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \LaravelToolkit\Support\Phone::class;
    }
}
