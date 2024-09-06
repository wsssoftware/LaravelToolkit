<?php

namespace LaravelToolkit\Enum;

use Exception;
use LaravelToolkit\Actions\Mask\MaskNumber;
use LaravelToolkit\Support\RegexTools;

enum Phone: string implements ArrayableEnum
{
    use HasArrayableEnum, RegexTools;

    case LANDLINE = 'landline';
    case LOCAL_FARE = 'local_fare';
    case MOBILE = 'mobile';
    case NON_REGIONAL = 'non_regional';
    case PUBLIC_SERVICES = 'public_services';
    case GENERIC = 'generic';

    public static function guessType(string $number): ?Phone
    {
        return match (true) {
            self::MOBILE->appearsToBe($number) => self::MOBILE,
            self::LOCAL_FARE->appearsToBe($number) => self::LOCAL_FARE,
            self::NON_REGIONAL->appearsToBe($number) => self::NON_REGIONAL,
            self::LANDLINE->appearsToBe($number) => self::LANDLINE,
            self::PUBLIC_SERVICES->appearsToBe($number) => self::PUBLIC_SERVICES,
            default => null,
        };
    }

    public function appearsToBe(string $number): bool
    {
        $this->regexOnlyNumbers($number);
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
        $this->regexOnlyNumbers($number);
        $type = $this === self::GENERIC ? self::guessType($number) : $this;
        if ($type === null) {
            return false;
        }
        $pattern = match ($type) {
            self::LANDLINE => '/^[1-9][0-9][1-5][0-9]{7}$/',
            self::LOCAL_FARE => '/^400[0-9]{5}$/',
            self::MOBILE => '/^[1-9][0-9]9[5-9][0-9]{7}$/',
            self::NON_REGIONAL => '/^0[3589]00[0-9]{7}$/',
            self::PUBLIC_SERVICES => '/^1[0-9]{2}$/',
        };

        return preg_match($pattern, $number) === 1;
    }

    public function label(): string
    {
        return match ($this) {
            self::LANDLINE => 'Fixo',
            self::LOCAL_FARE => 'Tarifa local',
            self::MOBILE => 'Móvel',
            self::NON_REGIONAL => 'Não regional',
            self::PUBLIC_SERVICES => 'Serviço publico',
            self::GENERIC => 'Genérico',
        };
    }

    public function mask(string $number): string
    {
        $this->regexOnlyNumbers($number);
        $type = $this === self::GENERIC ? self::guessType($number) : $this;
        if ($type === null) {
            return $number;
        }
        $handler = app(MaskNumber::class);

        return match ($type) {
            self::LANDLINE => $handler->handle('(##) ####-####', $number),
            self::LOCAL_FARE => $handler->handle('####-####', $number),
            self::MOBILE => $handler->handle('(##) # ####-####', $number),
            self::NON_REGIONAL => $handler->handle('####-###-####', $number),
            self::PUBLIC_SERVICES => $handler->handle('###', $number),
        };
    }
}
