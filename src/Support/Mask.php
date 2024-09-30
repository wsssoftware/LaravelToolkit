<?php

namespace LaravelToolkit\Support;

class Mask
{

    public function apply(string $input, string $mask): string
    {
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

            $found = false;
            while ($found === false || $inputIndex + 1 >= strlen($input)) {
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
    }
}
