<?php

namespace LaravelToolkit\Macros;

use Illuminate\Support\Collection;

class CollectionMacro
{
    public function __invoke(): void
    {
        $this->toPriveVueSelectFromArray();
        $this->toPriveVueSelectFromEnum();
        $this->toPriveVueSelectFromObject();
    }

    public function toPriveVueSelectFromArray(): void
    {
        Collection::macro(
            'toPriveVueSelectFromArray',
            function (string $optionLabel = 'label', string $optionValue = 'value'): Collection {
                return $this->map(fn(mixed $item, mixed $key) => [
                    $optionValue => $key,
                    $optionLabel => $item,
                ])->values();
            }
        );
    }

    public function toPriveVueSelectFromEnum(): void
    {
        Collection::macro(
            'toPriveVueSelectFromEnum',
            function (string $value, string $key, string $optionLabel = 'label', string $optionValue = 'value'): Collection {
                return $this->map(fn(mixed $item, mixed $key) => [
                    $optionValue => $key,
                    $optionLabel => $item,
                ]);
            }
        );
    }

    public function toPriveVueSelectFromObject(): void
    {
        Collection::macro(
            'toPriveVueSelectFromObject',
            function (string $value, string $key, string $optionLabel = 'label', string $optionValue = 'value'): Collection {
                return $this->map(fn(object $item) => [
                    $optionValue => $item->{$key},
                    $optionLabel => $item->{$value},
                ]);
            }
        );
    }
}
