<?php

namespace LaravelToolkit\Support;

trait PhoneTools
{
    use RegexTools;

    protected const string APPEARS_LANDLINE = '/^[1-9][0-9][1-5][0-9]$/';

    protected const string APPEARS_LOCAL_FARE = '/^400$/';

    protected const string APPEARS_MOBILE = '/^[1-9][0-9]9$/';

    protected const string APPEARS_NON_REGIONAL = '/^0[3589]00$/';

    protected const string APPEARS_PUBLIC_SERVICES = '/^1[0-9]{2}$/';

    protected const string VALID_LANDLINE = '/^[1-9][0-9][1-5][0-9]{7}$/';

    protected const string VALID_LOCAL_FARE = '/^400[0-9]{5}$/';

    protected const string VALID_MOBILE = '/^[1-9][0-9]9[5-9][0-9]{7}$/';

    protected const string VALID_NON_REGIONAL = '/^0[3589]00[0-9]{7}$/';

    protected const string VALID_PUBLIC_SERVICES = '/^1[0-9]{2}$/';

    public function appearsToBeLandlinePhone(string $value): bool
    {
        return preg_match(self::APPEARS_LANDLINE, substr($value, 0, 4)) === 1;
    }

    public function appearsToBeLocalFarePhone(string $value): bool
    {
        return preg_match(self::APPEARS_LOCAL_FARE, substr($value, 0, 3)) === 1;
    }

    public function appearsToBeMobilePhone(string $value): bool
    {
        return preg_match(self::APPEARS_MOBILE, substr($value, 0, 3)) === 1;
    }

    public function appearsToBeNonRegionalPhone(string $value): bool
    {
        return preg_match(self::APPEARS_NON_REGIONAL, substr($value, 0, 4)) === 1;
    }

    public function appearsToPublicServicePhone(string $value): bool
    {
        return preg_match(self::APPEARS_PUBLIC_SERVICES, substr($value, 0, 3)) === 1;
    }

    public function validLandlinePhone(string $value): bool
    {
        return preg_match(self::VALID_LANDLINE, $this->regexOnlyNumbers($value)) === 1;
    }

    public function validLocalFarePhone(string $value): bool
    {
        return preg_match(self::VALID_LOCAL_FARE, $this->regexOnlyNumbers($value)) === 1;
    }

    public function validMobilePhone(string $value): bool
    {
        return preg_match(self::VALID_MOBILE, $this->regexOnlyNumbers($value)) === 1;
    }

    public function validNonRegionalPhone(string $value): bool
    {
        return preg_match(self::VALID_NON_REGIONAL, $this->regexOnlyNumbers($value)) === 1;
    }

    public function validPublicServicesPhone(string $value): bool
    {
        return preg_match(self::VALID_PUBLIC_SERVICES, $this->regexOnlyNumbers($value)) === 1;
    }

    public function validGenericPhone(string $value): bool
    {
        $value = $this->regexOnlyNumbers($value);

        return match (true) {
            preg_match(self::APPEARS_MOBILE, substr($value, 0, 3)) === 1 => $this->validMobilePhone($value),
            preg_match(self::APPEARS_LOCAL_FARE, substr($value, 0, 3)) === 1 => $this->validLocalFarePhone($value),
            preg_match(self::APPEARS_NON_REGIONAL, substr($value, 0, 4)) === 1 => $this->validNonRegionalPhone($value),
            preg_match(self::APPEARS_LANDLINE, substr($value, 0, 4)) === 1 => $this->validLandlinePhone($value),
            preg_match(self::APPEARS_PUBLIC_SERVICES, substr($value, 0, 3)) === 1 => $this->validPublicServicesPhone($value),
            default => false
        };
    }
}
