<?php

namespace LaravelToolkit\ACL;

 class Rule
{

    public ?bool $value = null;

    public function __construct(
        public readonly string $key,
        public readonly string $name,
        public readonly string $description,
        public readonly ?int $denyStatus,
    ){
    }

    public function setValue(bool $value): void
    {
        $this->value = $value;
    }
}
