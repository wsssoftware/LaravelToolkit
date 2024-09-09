<?php

namespace LaravelToolkit\Actions\Phone;

use LaravelToolkit\Actions\Mask\MaskNumber;
use LaravelToolkit\Enum\Phone;
use LaravelToolkit\Support\RegexTools;

class MaskPhone
{
    use RegexTools;

    public function handle(?Phone $type, string $number): string
    {
        $number = $this->regexOnlyNumbers($number);
        if ($type === null) {
            return $number;
        }
        $handler = app(MaskNumber::class);

        return match ($type) {
            Phone::LANDLINE => $handler->handle('(##) ####-####', $number),
            Phone::LOCAL_FARE => $handler->handle('####-####', $number),
            Phone::MOBILE => $handler->handle('(##) # ####-####', $number),
            Phone::NON_REGIONAL => $handler->handle('####-###-####', $number),
            Phone::PUBLIC_SERVICES => $handler->handle('###', $number),
        };
    }
}
