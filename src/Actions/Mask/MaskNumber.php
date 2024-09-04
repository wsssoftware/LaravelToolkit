<?php

namespace LaravelToolkit\Actions\Mask;

use LaravelToolkit\Support\RegexTools;

class MaskNumber
{
    use RegexTools;

    public function handle(string $mask, string $string): string
    {
        return vsprintf(
            str_replace('#', '%s', $mask),
            mb_str_split($this->regexOnlyNumbers($string))
        );
    }
}
