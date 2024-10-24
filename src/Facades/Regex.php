<?php

namespace LaravelToolkit\Facades;

use Illuminate\Support\Facades\Facade;
use LaravelToolkit\Enum\IPVersion;

/**
 * @method static array getHashtags(string $payload)
 * @method static bool isEmail(string $payload, bool $uncommon = false)
 * @method static bool isHexValue(string $payload)
 * @method static bool isIpAddress(string $payload, IPVersion $version = IPVersion::ALL)
 * @method static bool isLikePhpVariableChars(string $payload)
 * @method static bool isSequenceOfUniqueChar(string $payload)
 * @method static bool isURL(string $payload, bool $protocolOptional = true)
 * @method static string onlyAlpha(?string $payload, bool $allowSpace = false)
 * @method static string onlyAlphaNumeric(?string $payload, bool $allowSpace = false)
 * @method static string onlyNumeric(?string $payload, bool $allowSpace = false)
 *
 * @see \LaravelToolkit\Support\Regex
 */
class Regex extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \LaravelToolkit\Support\Regex::class;
    }
}
