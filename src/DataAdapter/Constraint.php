<?php

namespace LaravelToolkit\DataAdapter;

readonly class Constraint
{
    public function __construct(
        public MatchMode $matchMode,
        public string $value,
    ) {
        //
    }
}