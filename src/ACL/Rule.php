<?php

namespace LaravelToolkit\ACL;

use Exception;

class Rule
{

    public ?bool $value = null;

    public function __construct(
        public readonly string $key,
        public readonly string $name,
        public readonly string $description,
        public readonly ?int $denyStatus,
    ) {
        throw_if(
            in_array($this->key, ['items', 'column', 'name', 'description']),
            Exception::class,
            "$this->key is a reserved name and cannot used on key."
        );
    }

    public function setValue(bool $value): void
    {
        $this->value = $value;
    }
}
