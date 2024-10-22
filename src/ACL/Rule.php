<?php

namespace LaravelToolkit\ACL;

use Illuminate\Support\Arr;

 class Rule
{

    public ?bool $value = null;

    public function __construct(
        public readonly string $key,
        public readonly string $name,
        public readonly ?int $denyStatus,
    ){
    }

    public function setValue(bool $value): void
    {
        $this->value = $value;
    }

    public function serialize(): string
    {
        return bin2hex(json_encode([
            'key' => $this->key,
            'name' => $this->name,
            'denyStatus' => $this->denyStatus,
        ]));
    }

    public static function unserialize(string $serialized): self
    {
        $payload = json_decode(hex2bin($serialized), true);
        return new self(
            Arr::get($payload, 'key'),
            Arr::get($payload, 'name'),
            Arr::get($payload, 'denyStatus'),
        );
    }

}
