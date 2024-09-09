<?php

namespace LaravelToolkit\Actions\Phone;

use LaravelToolkit\Enum\Phone;

class FakePhone
{
    public function handle(?Phone $type): string
    {
        return match ($type) {
            Phone::LANDLINE => $this->landline(),
            Phone::LOCAL_FARE => $this->localFare(),
            Phone::MOBILE => $this->mobile(),
            Phone::NON_REGIONAL => $this->nonRegional(),
            Phone::PUBLIC_SERVICES => $this->publicService(),
            Phone::GENERIC => $this->generic(),
        };
    }

    protected function landline(): string
    {
        return sprintf(
            '%s%s%s',
            rand(10, 99),
            rand(1000, 5999),
            str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT),
        );
    }

    protected function localFare(): string
    {
        return sprintf(
            '400%s',
            str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT),
        );
    }

    protected function mobile(): string
    {
        return sprintf(
            '%s9%s%s',
            rand(10, 99),
            rand(5000, 9999),
            str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT),
        );
    }

    protected function nonRegional(): string
    {
        return sprintf(
            '0%s00%s',
            collect([3, 5, 8, 9])->shuffle()->first(),
            str_pad(rand(0, 9999999), 7, '0', STR_PAD_LEFT),
        );
    }

    protected function publicService(): string
    {
        return sprintf(
            '1%s',
            str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT),
        );
    }

    protected function generic(): string
    {
        return match (rand(1, 5)) {
            1 => $this->landline(),
            2 => $this->localFare(),
            3 => $this->mobile(),
            4 => $this->nonRegional(),
            5 => $this->publicService(),
        };
    }
}
