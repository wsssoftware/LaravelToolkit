<?php

namespace LaravelToolkit\Support\Phone;

use Illuminate\Support\Str;
use LaravelToolkit\Facades\Regex;

class LocalFare implements Phone
{

    public function appearsToBe(string $phone): string
    {
        return preg_match('/^400$/', substr(Regex::onlyNumbers($phone), 0, 3)) === 1;
    }

    public function fake(): string
    {
        return sprintf(
            '400%s',
            str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT),
        );
    }

    public function label(): string
    {
        return 'Telefone de tarifa local';
    }

    public function mask(string $phone): string
    {
        $phone = Regex::onlyNumbers($phone);
        return Str::applyMask($phone, '0000-0000');
    }

    public function unmask(string $phone): string
    {
        return Regex::onlyNumbers($phone);
    }

    public function validate(string $phone): bool
    {
        $phone = Regex::onlyNumbers($phone);
        return preg_match('/^400[0-9]{5}$/', $phone) === 1;
    }
}
