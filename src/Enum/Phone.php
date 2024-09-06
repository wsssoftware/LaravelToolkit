<?php

namespace LaravelToolkit\Enum;

use Exception;

enum Phone: string implements ArrayableEnum
{
    use HasArrayableEnum;

    case LANDLINE = 'landline';
    case LOCAL_FARE = 'local_fare';
    case MOBILE = 'mobile';
    case NON_REGIONAL = 'non_regional';
    case PUBLIC_SERVICES = 'public_services';
    case GENERIC = 'generic';

    public function appearsToBe(string $number): bool
    {
        throw_if($this === self::GENERIC, Exception::class, 'Appears to be method is not allowed to generic type');
        $pattern = match ($this) {
            self::LANDLINE => '/^[1-9][0-9][1-5][0-9]$/',
            self::LOCAL_FARE => '/^400$/',
            self::MOBILE => '/^[1-9][0-9]9$/',
            self::NON_REGIONAL => '/^0[3589]00$/',
            self::PUBLIC_SERVICES => '/^1[0-9]{2}$/',
        };
        $string = match ($this) {
            self::LANDLINE, self::NON_REGIONAL => substr($number, 0, 4),
            self::LOCAL_FARE, self::PUBLIC_SERVICES, self::MOBILE => substr($number, 0, 3),
        };

        return preg_match($pattern, $string) === 1;
    }

    public function isValid(string $number): bool
    {
        if ($this === self::GENERIC) {
            return match (true) {
                self::MOBILE->appearsToBe($number) => self::MOBILE->isValid($number),
                self::LOCAL_FARE->appearsToBe($number) => self::LOCAL_FARE->isValid($number),
                self::NON_REGIONAL->appearsToBe($number) => self::NON_REGIONAL->isValid($number),
                self::LANDLINE->appearsToBe($number) => self::LANDLINE->isValid($number),
                self::PUBLIC_SERVICES->appearsToBe($number) => self::PUBLIC_SERVICES->isValid($number),
            };
        }
        $pattern = match ($this) {
            self::LANDLINE => '/^[1-9][0-9][1-5][0-9]{7}$/',
            self::LOCAL_FARE => '/^400[0-9]{5}$/',
            self::MOBILE => '/^[1-9][0-9]9[5-9][0-9]{7}$/',
            self::NON_REGIONAL => '/^0[3589]00[0-9]{7}$/',
            self::PUBLIC_SERVICES => '/^1[0-9]{2}$/',
        };

        return preg_match($pattern, $number) === 1;
    }
}
