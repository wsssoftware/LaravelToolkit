<?php

namespace LaravelToolkit\Support;

use LaravelToolkit\Enum\IPVersion;

/**
 * @see \LaravelToolkit\Facades\Regex
 */
class Regex
{

    public function getHashtags(string $payload): array
    {
        $matches = [];
        preg_match_all('/#\w+/', $payload, $matches);
        return $matches;
    }

    public function isEmail(string $payload, bool $uncommon = false): bool
    {
        return match (true) {
                $uncommon => preg_match('/^([a-z0-9_\.\+-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/', $payload),
                default => preg_match('/^([a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6})*$/', $payload),
            } === 1;
    }

    public function isHexValue(string $payload): bool
    {
        return preg_match('/^#?([a-f0-9]{6}|[a-f0-9]{3})$/', $payload) === 1;
    }

    public function isIpAddress(string $payload, IPVersion $version = IPVersion::ALL): bool
    {
        return match ($version) {
                IPVersion::IPV4 => preg_match(
                    '/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/',
                    $payload
                ),
                IPVersion::IPV6 => preg_match(
                    '/(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))/',
                    $payload
                ),
                IPVersion::ALL => preg_match(
                    '/((^\s*((([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))\s*$)|(^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$))/',
                    $payload
                ),
            } === 1;
    }

    public function isLikePhpVariableChars(string $payload): bool
    {
        return preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $payload) === 1;
    }

    public function isSequenceOfUniqueChar(string $payload): bool
    {
        return preg_match('/^(.)\1*$/', $payload) === 1;
    }

    public function isURL(string $payload, bool $protocolOptional = false): bool
    {
        return match (true) {
                $protocolOptional => preg_match(
                    '/(https?:\/\/)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/',
                    $payload
                ),
                default => preg_match(
                    '/https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#()?&//=]*)/',
                    $payload
                ),
            } === 1;
    }

    public function onlyAlpha(?string $payload, bool $allowSpace = false): string
    {
        return match (true) {
            $allowSpace => preg_replace('/^[a-zA-Z ]*$/', '', $payload ?? ''),
            default => preg_replace('/^[a-zA-Z]*$/', '', $payload ?? ''),
        };
    }

    public function onlyAlphaNumeric(?string $payload, bool $allowSpace = false): string
    {
        return match (true) {
            $allowSpace => preg_replace('/^[a-zA-Z0-9 ]*$/', '', $payload ?? ''),
            default => preg_replace('/^[a-zA-Z0-9]*$/', '', $payload ?? ''),
        };
    }

    public function onlyNumeric(?string $payload, bool $allowSpace = false): string
    {
        return match (true) {
            $allowSpace => preg_replace('/[^0-9 ]/', '', $payload ?? ''),
            default => preg_replace('/[^0-9]/', '', $payload ?? ''),
        };
    }
}
