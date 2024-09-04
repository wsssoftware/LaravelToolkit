<?php

namespace LaravelToolkit\Actions\Mask;

use LaravelToolkit\Support\RegexTools;

class UnmaskNumber
{
    use RegexTools;

    public function handle(string $value): string
    {
        return $this->regexOnlyNumbers($value);
    }
}
