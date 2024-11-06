<?php

use LaravelToolkit\Facades\Colors;

it('test conversion', function (string $hex, array $rgb, array $hsl, string $hexFromHsl, $rgbFromHsl) {
    expect(Colors::hexToRgb($hex))
        ->toBeArray()->toBe($rgb)->each->toBeInt()
        ->and(Colors::hexToHsl($hex))
        ->toBeArray()->toBe($hsl)->each->toBeInt()
        ->and(Colors::hslToHex(...$hsl))
        ->toBeString()->toBe($hexFromHsl)
        ->and(Colors::hslToRgb(...$hsl))
        ->toBeArray()->toBe($rgbFromHsl)->each->toBeInt()
        ->and(Colors::rgbToHex(...$rgb))
        ->toBeString()->toBe(strlen($hex) === 7 ? $hex : '#CCCCCC')
        ->and(Colors::rgbToHsl(...$rgb))
        ->toBeArray()->toBe($hsl)->each->toBeInt();
})->with([
    ['hex' => '#CCCCCC', 'rgb' => [204, 204, 204], 'hsl' => [0, 0, 80], 'hexFromHsl' => '#CCCCCC', 'rgbFromHsl' => [204, 204, 204]],
    ['hex' => '#CCC', 'rgb' => [204, 204, 204], 'hsl' => [0, 0, 80], 'hexFromHsl' => '#CCCCCC', 'rgbFromHsl' => [204, 204, 204]],
    ['hex' => '#D3F6DB', 'rgb' => [211, 246, 219], 'hsl' => [134, 66, 90], 'hexFromHsl' => '#D5F6DD', 'rgbFromHsl' => [213, 246, 221]],
    ['hex' => '#772D8B', 'rgb' => [119, 45, 139], 'hsl' => [287, 51, 36], 'hexFromHsl' => '#762D8B', 'rgbFromHsl' => [118, 45, 139]],
    ['hex' => '#8C1C13', 'rgb' => [140, 28, 19], 'hsl' => [4, 76, 31], 'hexFromHsl' => '#8B1B13', 'rgbFromHsl' => [139, 27, 19]],
    ['hex' => '#604D53', 'rgb' => [96, 77, 83], 'hsl' => [341, 11, 34], 'hexFromHsl' => '#604D53', 'rgbFromHsl' => [96, 77, 83]],
    ['hex' => '#9DA3A4', 'rgb' => [157, 163, 164], 'hsl' => [189, 4, 63], 'hexFromHsl' => '#9DA3A4', 'rgbFromHsl' => [157, 163, 164]],
    ['hex' => '#FFDBDA', 'rgb' => [255, 219, 218], 'hsl' => [2, 100, 93], 'hexFromHsl' => '#FFDCDB', 'rgbFromHsl' => [255, 220, 219]],
    ['hex' => '#17BEBB', 'rgb' => [23, 190, 187], 'hsl' => [179, 78, 42], 'hexFromHsl' => '#18BFBC', 'rgbFromHsl' => [24, 191, 188]],
    ['hex' => '#76B041', 'rgb' => [118, 176, 65], 'hsl' => [91, 46, 47], 'hexFromHsl' => '#76AF41', 'rgbFromHsl' => [118, 175, 65]],
    ['hex' => '#FFC914', 'rgb' => [255, 201, 20], 'hsl' => [46, 100, 54], 'hexFromHsl' => '#FFC814', 'rgbFromHsl' => [255, 200, 20]],
    ['hex' => '#E4572E', 'rgb' => [228, 87, 46], 'hsl' => [14, 77, 54], 'hexFromHsl' => '#E45A2F', 'rgbFromHsl' => [228, 90, 47]],
    ['hex' => '#DFF3E4', 'rgb' => [223, 243, 228], 'hsl' => [135, 45, 91], 'hexFromHsl' => '#DEF2E3', 'rgbFromHsl' => [222, 242, 227]],
    ['hex' => '#2E1760', 'rgb' => [46, 23, 96], 'hsl' => [259, 61, 23], 'hexFromHsl' => '#2E175E', 'rgbFromHsl' => [46, 23, 94]],
    ['hex' => '#66C3FF', 'rgb' => [102, 195, 255], 'hsl' => [204, 100, 70], 'hexFromHsl' => '#66C2FF', 'rgbFromHsl' => [102, 194, 255]],
    ['hex' => '#444444', 'rgb' => [68, 68, 68], 'hsl' => [0, 0, 27], 'hexFromHsl' => '#454545', 'rgbFromHsl' => [69, 69, 69]],
    ['hex' => '#000000', 'rgb' => [0, 0, 0], 'hsl' => [0, 0, 0], 'hexFromHsl' => '#000000', 'rgbFromHsl' => [0, 0, 0]],
    ['hex' => '#FFFFFF', 'rgb' => [255, 255, 255], 'hsl' => [0, 0, 100], 'hexFromHsl' => '#FFFFFF', 'rgbFromHsl' => [255, 255, 255]],
]);
