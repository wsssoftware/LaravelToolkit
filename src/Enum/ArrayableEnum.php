<?php

namespace LaravelToolkit\Enum;

interface ArrayableEnum
{
    public static function toEnumArray(): array;

    public function label(): string;
}
