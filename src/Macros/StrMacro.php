<?php

namespace LaravelToolkit\Macros;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class StrMacro
{
    public function __invoke(): void
    {
        $this->applyMask();
    }

    public function applyMask(): void
    {
        Str::macro('applyMask', function (string $input, string $mask): string {
            $result = '';
            $inputIndex = 0;
            $escape = false;

            foreach (str_split($mask) as $currentMaskChar) {
                if ($currentMaskChar === '\\' && !$escape) {
                    $escape = true;
                    continue;
                }
                if ($escape) {
                    $result .= $currentMaskChar;
                    $escape = false;
                    continue;
                }
                if ($inputIndex >= strlen($input)) {
                    break;
                }

                while ($inputIndex + 1 <= strlen($input)) {
                    $charInput = $input[$inputIndex];
                    if ($currentMaskChar === '0' && ctype_digit($charInput)) {
                        $result .= $charInput;
                        $inputIndex++;
                        break;
                    } elseif ($currentMaskChar === 'A' && ctype_alnum($charInput)) {
                        $result .= $charInput;
                        $inputIndex++;
                        break;
                    } elseif ($currentMaskChar === 'S' && ctype_alpha($charInput)) {
                        $result .= $charInput;
                        $inputIndex++;
                        break;
                    } elseif (!in_array($currentMaskChar, ['0', 'A', 'S'])) {
                        $result .= $currentMaskChar;
                        break;
                    }
                    $inputIndex++;
                }

            }

            return $result;
        });

        Stringable::macro('applyMask', function (string $mask): Stringable {
            return new Stringable(Str::applyMask($this->value, $mask));
        });
    }
}
