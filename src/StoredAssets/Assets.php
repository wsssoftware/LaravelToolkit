<?php

namespace LaravelToolkit\StoredAssets;

use ArrayAccess;
use Countable;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Support\Arrayable;
use LaravelToolkit\StoredAssets\Casts\AssetsCast;


class Assets implements ArrayAccess, Countable, Arrayable, Castable
{

    public function __construct(
        readonly public string $uuid,
        protected array $assets = []
    ) {
        //
    }

    public static function castUsing(array $arguments): string
    {
        return AssetsCast::class;
    }

    public function count(): int
    {
        return count($this->assets);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->assets[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->assets[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->assets[] = $value;
        } else {
            $this->assets[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->assets[$offset]);
    }

    public function put(string $key, Asset $asset): self
    {
        $this->assets[$key] = $asset;
        return $this;
    }

    public function get(string $key): ?Asset
    {
        return $this->offsetExists($key) ? $this->assets[$key] : null;
    }

    public function toArray(): array
    {
        return collect($this->assets)->toArray();
    }

    public function toDatabase(): array
    {
        return collect($this->assets)->map(fn(Asset $asset) => $asset->toDatabase())->values()->toArray();
    }

    /**
     * @param  string  $name
     * @return \LaravelToolkit\StoredAssets\Asset|null
     */
    public function __get(string $name)
    {
        return $this->get($name);
    }
}
