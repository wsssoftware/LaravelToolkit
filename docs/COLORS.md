## Colors

A toolset of helpers for colors.

### Converting
You can use the following methods of `Facade` to convert between colors format:
```php
use LaravelToolkit\Facades\Colors;

[$r, $g, $b] = Colors::hexToRgb('#e4572e');
[$h, $s, $l] = Colors::hexToHsl('#e4572e');

$hex = Colors::hslToHex(14, 77, 54);
[$r, $g, $b] = Colors::hslToRgb(14, 77, 54);

$hex = Colors::rgbToHex(228, 87, 46);
[$h, $s, $l] = Colors::rgbToHsl(228, 87, 46);

```

### Random colors
If you want to generate a random color use:
```php
use LaravelToolkit\Facades\Colors;

$hex = Colors::randHex();
[$r, $g, $b] = Colors::randRgb();
[$h, $s, $l] = Colors::randHsl();

```

### Tailwind palette
If you have a base color and want to generate a palette:
```php
use LaravelToolkit\Facades\Colors;
use LaravelToolkit\Colors\ColorStep;
use LaravelToolkit\Colors\ColorFormat;

// from hex
$palette = Colors::palette('#e4572e');
// from rgb
$palette = Colors::palette(rgb: [228, 87, 46]);
// from hsl
$palette = Colors::palette(hsl: [14, 77, 54]);

// changing base color
$palette = Colors::palette('#e4572e', baseStep: ColorStep::STEP_400);
// choosing output format
$palette = Colors::palette('#e4572e', outputFormat: ColorFormat::RGB);
// with custom threshold
$palette = Colors::palette('#e4572e', thresholdLightest: 10, thresholdDarkest: 10);

```