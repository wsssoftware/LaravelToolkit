<?php

namespace LaravelToolkit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static float[] hexToHsl(string $hex)
 * @method static int[] hexToRgb(string $hex)
 * @method static string hlsToHex(float $h, float $s, float $l)
 * @method static array hlsToRgb(float $h, float $s, float $l)
 * @method static string rgbToHex(int $r, int $g, int $b)
 * @method static float[] rgbToHsl(int $r, int $g, int $b)
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
