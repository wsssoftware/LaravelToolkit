<?php

namespace LaravelToolkit\Facades;

use Illuminate\Support\Facades\Facade;
use LaravelToolkit\Colors\ColorFormat;
use LaravelToolkit\Colors\ColorStep;

/**
 * @method static int[] hexToHsl(string $hex)
 * @method static int[] hexToRgb(string $hex)
 * @method static string hslToHex(int $h, int $s, int $l)
 * @method static array hslToRgb(int $h, int $s, int $l)
 * @method static array palette(null|string $hex = null, null|array $rgb = null, null|array $hsl = null, ColorStep $baseStep = ColorStep::STEP_500, ColorFormat $outputFormat = ColorFormat::HEX, float $thresholdLightest = 5, float $thresholdDarkest = 6)
 * @method static string randHex()
 * @method static array randHsl()
 * @method static array randRgb()
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
