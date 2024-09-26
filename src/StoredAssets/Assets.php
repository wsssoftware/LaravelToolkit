<?php

namespace LaravelToolkit\StoredAssets;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Support\Collection;
use LaravelToolkit\StoredAssets\Casts\AssetsCast;


class Assets extends Collection implements Castable
{

    public function __construct(
        readonly public string $uuid,
        array $assets = []
    ) {
        parent::__construct($assets);
    }

    public static function castUsing(array $arguments): string
    {
        return AssetsCast::class;
    }

    public function toDatabase(): array
    {
        return collect($this->assets)->map(fn(Asset $asset) => $asset->toDatabase())->values()->toArray();
    }

    public function __get($key): ?Asset
    {
        return $this->offsetExists($key) ? $this->offsetGet($key) : null;
    }
}
