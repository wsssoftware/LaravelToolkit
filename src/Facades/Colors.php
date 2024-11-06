<?php

namespace LaravelToolkit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static int[] hexToHsl(string $hex)
 * @method static int[] hexToRgb(string $hex)
 * @method static string hslToHex(int $h, int $s, int $l)
 * @method static array hslToRgb(int $h, int $s, int $l)
 * @method static string rgbToHex(int $r, int $g, int $b)
 * @method static int[] rgbToHsl(int $r, int $g, int $b)
 *
 * @see \LaravelToolkit\Colors\Colors
 */
class Colors extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \LaravelToolkit\Colors\Colors::class;
    }
}
