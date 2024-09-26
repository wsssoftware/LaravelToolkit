<?php

namespace LaravelToolkit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool isLikePhpVariableChars(string $payload)
 * @method static bool isSequenceOfUniqueChar(string $payload)
 * @method static string onlyNumbers(?string $payload)
 *
 * @see \LaravelToolkit\Support\Regex
 */
class Regex extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \LaravelToolkit\Support\Regex::class;
    }
}
