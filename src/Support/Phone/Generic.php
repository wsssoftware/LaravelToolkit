<?php

namespace LaravelToolkit\Support\Phone;

use Exception;
use LaravelToolkit\Enum\Phone as PhoneEnum;
use LaravelToolkit\Facades\Regex;

class Generic implements Phone
{

    public function appearsToBe(string $phone): string
    {
        throw new Exception('Appears to be method is not allowed to generic type');
    }

    public function fake(): string
    {
        return match (rand(1, 5)) {
            1 => app(Landline::class)->fake(),
            2 => app(LocalFare::class)->fake(),
            3 => app(Mobile::class)->fake(),
            4 => app(NonRegional::class)->fake(),
            5 => app(PublicServices::class)->fake(),
        };
    }

    public function label(): string
    {
        return 'Telefone genérico';
    }

    public function mask(string $phone): string
    {
        $phone = Regex::onlyNumbers($phone);
        $type = PhoneEnum::guessType($phone);
        if ($type === null) {
            return $phone;
        }
        return $type->mask($phone);
    }

    public function unmask(string $phone): string
    {
       return Regex::onlyNumbers($phone);
    }

    public function validate(string $phone): bool
    {
        $phone = Regex::onlyNumbers($phone);
        $type = PhoneEnum::guessType($phone);
        if ($type === null) {
            return false;
        }
        return $type->validate($phone);
    }
}
