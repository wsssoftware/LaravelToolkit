<?php

namespace LaravelToolkit\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use InvalidArgumentException;
use LaravelToolkit\Support\PhoneTools;
use LaravelToolkit\Support\RegexTools;

readonly class Phone implements ValidationRule
{
    use PhoneTools;
    use RegexTools;

    public const int LANDLINE = 1;

    public const int LOCAL_FARE = 2;

    public const int MOBILE = 4;

    public const int NON_REGIONAL = 8;

    public const int PUBLIC_SERVICE = 16;

    public const int GENERIC = 31;

    public function __construct(
        public int $types = self::GENERIC
    ) {
        if ($this->types < 1 || $this->types > self::GENERIC) {
            throw new InvalidArgumentException('Configuração inválida para o tipo de telefone.');
        }
    }

    public static function mobile(): self
    {
        return new self(self::MOBILE);
    }

    public static function generic(): self
    {
        return new self;
    }

    public static function localFare(): self
    {
        return new self(self::LOCAL_FARE);
    }

    public static function nonRegional(): self
    {
        return new self(self::NON_REGIONAL);
    }

    public static function landline(): self
    {
        return new self(self::LANDLINE);
    }

    public static function landlineOrMobile(): self
    {
        return new self(self::LANDLINE | self::MOBILE);
    }

    public static function publicServices(): self
    {
        return new self(self::PUBLIC_SERVICE);
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('validation.string')->translate();

            return;
        }
        $value = $this->regexOnlyNumbers($value);

        $result = match (true) {
            $this->types === (self::MOBILE | self::LANDLINE)
            && strlen($value) === 10, $this->types === self::LANDLINE => $this->validateLandline($value),
            $this->types === (self::MOBILE | self::LANDLINE)
            && strlen($value) === 11, $this->types === self::MOBILE => $this->validateMobile($value),
            $this->types === self::GENERIC
            && $this->appearsToBeMobilePhone($value) => $this->validateMobile($value),
            $this->types === self::GENERIC
            && $this->appearsToBeLocalFarePhone($value),
            $this->types === self::LOCAL_FARE => $this->validateLocalFare($value),
            $this->types === self::GENERIC
            && $this->appearsToBeNonRegionalPhone($value),
            $this->types === self::NON_REGIONAL => $this->validateNonRegional($value),
            $this->types === self::GENERIC
            && $this->appearsToBeLandlinePhone($value) => $this->validateLandline($value),
            $this->types === self::GENERIC
            && $this->appearsToPublicServicePhone($value),
            $this->types === self::PUBLIC_SERVICE => $this->validatePublicServices($value),
            default => 'validation.phone.generic',
        };
        if (is_string($result)) {
            $fail($result)->translate();
        }
    }

    protected function validateLandline(string $value): string|true
    {
        return $this->validLandlinePhone($value) ? true : 'validation.phone.landline';
    }

    protected function validateLocalFare(string $value): string|true
    {
        return $this->validLocalFarePhone($value) ? true : 'validation.phone.local_fare';
    }

    protected function validateMobile(string $value): string|true
    {
        return $this->validMobilePhone($value) ? true : 'validation.phone.mobile';
    }

    protected function validateNonRegional(string $value): string|true
    {
        return $this->validNonRegionalPhone($value) ? true : 'validation.phone.non_regional';
    }

    protected function validatePublicServices(string $value): string|true
    {
        return $this->validPublicServicesPhone($value) ? true : 'validation.phone.public_services';
    }
}
