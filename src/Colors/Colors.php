<?php

namespace LaravelToolkit\Colors;

use Exception;

/**
 * @see \LaravelToolkit\Facades\Colors
 */
class Colors
{
    /**
     * @return array{0: float, 1: float, 2: float}
     *
     * @throws \Exception
     */
    public function hexToHsl(string $hex): array
    {
        return $this->rgbToHsl(...$this->hexToRgb($hex));
    }

    /**
     * @return array{0: int, 1: int, 2: int}
     *
     * @throws \Exception
     */
    public function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 6) {
            return [
                hexdec(substr($hex, 0, 2)),
                hexdec(substr($hex, 2, 2)),
                hexdec(substr($hex, 4, 2)),
            ];
        } elseif (strlen($hex) === 3) {
            return [
                hexdec(str_repeat(substr($hex, 0, 1), 2)),
                hexdec(str_repeat(substr($hex, 1, 1), 2)),
                hexdec(str_repeat(substr($hex, 2, 1), 2)),
            ];
        } else {
            throw new Exception('Invalid hex string');
        }
    }

    public function hslToHex(int $h, int $s, int $l): string
    {
        return $this->rgbToHex(...$this->hslToRgb($h, $s, $l));
    }

    /**
     * @return array{0: int, 1: int, 2: int}
     */
    public function hslToRgb(int $h, int $s, int $l): array
    {
        $h /= 360;
        $s /= 100;
        $l /= 100;
        if ($s == 0) {
            $r = $g = $b = $l;
        } else {
            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;

            $r = $this->hueToRgb($p, $q, $h + 1 / 3);
            $g = $this->hueToRgb($p, $q, $h);
            $b = $this->hueToRgb($p, $q, $h - 1 / 3);
        }

        return [
            intval(round($r * 255)),
            intval(round($g * 255)),
            intval(round($b * 255)),
        ];
    }

    protected function hueToRgb(float $p, float $q, float $t): float
    {
        $t = match (true) {
            $t < 0 => $t + 1,
            $t > 1 => $t - 1,
            default => $t,
        };

        return match (true) {
            $t < 1 / 6 => $p + ($q - $p) * 6 * $t,
            $t < 1 / 2 => $q,
            $t < 2 / 3 => $p + ($q - $p) * (2 / 3 - $t) * 6,
            default => $p,
        };
    }

    public function rgbToHex(int $r, int $g, int $b): string
    {
        return sprintf('#%02X%02X%02X', $r, $g, $b);
    }

    /**
     * @return array{0: float, 1: float, 2: float}
     */
    public function rgbToHsl(int $r, int $g, int $b): array
    {
        $r /= 255;
        $g /= 255;
        $b /= 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);

        $h = $s = 0;
        $l = ($max + $min) / 2;

        if ($max !== $min) {
            $delta = $max - $min;
            $s = $l > 0.5 ? $delta / (2.0 - $max - $min) : $delta / ($max + $min);

            $h = match (true) {
                $max == $r => ($g - $b) / $delta + ($g < $b ? 6 : 0),
                $max == $g => ($b - $r) / $delta + 2,
                default => ($r - $g) / $delta + 4
            };

            $h /= 6;
        }

        return [
            intval(round($h * 360)),
            intval(round($s * 100)),
            intval(round($l * 100)),
        ];
    }
}
