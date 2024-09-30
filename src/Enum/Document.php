<?php

namespace LaravelToolkit\Enum;

use LaravelToolkit\Facades\Document as Facade;

enum Document: string implements ArrayableEnum
{
    use HasArrayableEnum;

    case CNPJ = 'cnpj';
    case CPF = 'cpf';
    case GENERIC = 'generic';

    public function calculateCheckDigits(string $document): string
    {
        return match ($this) {
            self::CNPJ => Facade::checkDigitsFromCnpj($document),
            self::CPF => Facade::checkDigitsFromCpf($document),
            self::GENERIC => Facade::checkDigitsFromGeneric($document),
        };
    }

    public function fake(): string
    {
        return match ($this) {
            self::CNPJ => Facade::fakeCnpj(),
            self::CPF => Facade::fakeCpf(),
            self::GENERIC => Facade::fakeGeneric(),
        };
    }

    public function isValid(string $document): bool
    {
        return match ($this) {
            self::CNPJ => Facade::isValidCnpj($document),
            self::CPF => Facade::isValidCpf($document),
            self::GENERIC => Facade::isValidGeneric($document),
        };
    }

    public function mask(string $document): string
    {
        return Facade::mask($document);
    }

    public function unmask(string $document): string
    {
        return Facade::unmask($document);
    }

    public function label(): string
    {
        return match ($this) {
            self::CNPJ => 'CNPJ',
            self::CPF => 'CPF',
            self::GENERIC => 'Genérico'
        };
    }
}
