<?php

namespace LaravelToolkit\Colors;

readonly class Color
{
    private function __construct(
        protected float $hue,
        protected float $saturation,
        protected float $lightness,
    ) {
        //
    }
}
