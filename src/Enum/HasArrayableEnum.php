<?php

namespace LaravelToolkit\Enum;

use Exception;

trait HasArrayableEnum
{
    public static function toEnumArray(): array {
        throw_if(!enum_exists(self::class), Exception::class, self::class . ' is not a valid enum');
        return collect(self::cases())
            ->mapWithKeys(fn ($value, $key) => [$value->value => $value->label()])
            ->toArray();
    }

    public function label(): string {
        return $this->value;
    }
}
