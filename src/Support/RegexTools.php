<?php

namespace LaravelToolkit\Support;

use LaravelToolkit\Facades\Regex;

trait RegexTools
{
    public function regexIsLikePhpVariableChars(string $payload): bool
    {
        return Regex::isLikePhpVariableChars($payload);
    }

    public function regexIsSequenceOfUniqueChar(string $payload): bool
    {
        return Regex::isSequenceOfUniqueChar($payload);
    }

    public function regexOnlyNumbers(?string $payload): string
    {
        return Regex::onlyNumbers($payload);
    }
}
