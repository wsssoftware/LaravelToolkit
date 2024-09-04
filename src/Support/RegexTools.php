<?php

namespace LaravelToolkit\Support;

trait RegexTools
{
    public function regexOnlyNumbers(string $payload): string
    {
        return preg_replace('/[^0-9]/', '', $payload);
    }

    public function regexIsSequenceOfUniqueChar(string $payload): bool
    {
        return preg_match('/^(.)\1*$/', $payload) === 1;
    }
}
