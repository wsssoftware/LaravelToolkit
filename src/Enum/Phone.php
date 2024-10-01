<?php

namespace LaravelToolkit\Enum;

use LaravelToolkit\Support\Phone\Generic;
use LaravelToolkit\Support\Phone\Landline;
use LaravelToolkit\Support\Phone\LocalFare;
use LaravelToolkit\Support\Phone\Mobile;
use LaravelToolkit\Support\Phone\NonRegional;
use LaravelToolkit\Support\Phone\Phone as Utils;
use LaravelToolkit\Support\Phone\PublicServices;

enum Phone: string implements ArrayableEnum
{
    use HasArrayableEnum;

    case GENERIC = 'generic';
    case LANDLINE = 'landline';
    case LOCAL_FARE = 'local_fare';
    case MOBILE = 'mobile';
    case NON_REGIONAL = 'non_regional';
    case PUBLIC_SERVICES = 'public_services';

    public static function guessType(?string $number): ?Phone
    {
        return match (true) {
            strlen($number) === 3 && self::PUBLIC_SERVICES->appearsToBe($number) => self::PUBLIC_SERVICES,
            self::MOBILE->appearsToBe($number) => self::MOBILE,
            self::LOCAL_FARE->appearsToBe($number) => self::LOCAL_FARE,
            self::NON_REGIONAL->appearsToBe($number) => self::NON_REGIONAL,
            self::LANDLINE->appearsToBe($number) => self::LANDLINE,
            default => null,
        };
    }

    public function appearsToBe(?string $number): bool
    {
        return $this->utils()->appearsToBe($number);
    }

    public function fake(): string
    {
        return $this->utils()->fake();
    }

    public function label(): string
    {
        return $this->utils()->label();
    }

    public function mask(string $number): string
    {
        return $this->utils()->mask($number);
    }

    public function unmask(string $number): string
    {
        return $this->utils()->unmask($number);
    }

    public function utils(): Utils
    {
        return match ($this) {
            self::LANDLINE => app(Landline::class),
            self::LOCAL_FARE => app(LocalFare::class),
            self::MOBILE => app(Mobile::class),
            self::NON_REGIONAL => app(NonRegional::class),
            self::PUBLIC_SERVICES => app(PublicServices::class),
            self::GENERIC => app(Generic::class),
        };
    }

    public function validate(?string $number): bool
    {
        return $this->utils()->validate($number);
    }
}
