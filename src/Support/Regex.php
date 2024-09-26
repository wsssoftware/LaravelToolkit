<?php

namespace LaravelToolkit\Support;

class Regex
{

    public function isLikePhpVariableChars(string $payload): bool
    {
        return preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $payload) === 1;
    }

    public function isSequenceOfUniqueChar(string $payload): bool
    {
        return preg_match('/^(.)\1*$/', $payload) === 1;
    }

    public function onlyNumbers(?string $payload): string
    {
        return preg_replace('/[^0-9]/', '', $payload ?? '');
    }
}
