<?php

namespace LaravelToolkit\Enum;

use LaravelToolkit\Support\Document\CNPJ;
use LaravelToolkit\Support\Document\CPF;
use LaravelToolkit\Support\Document\Document as Utils;
use LaravelToolkit\Support\Document\Generic;

enum Document: string implements ArrayableEnum
{
    use HasArrayableEnum;

    case CNPJ = 'cnpj';
    case CPF = 'cpf';
    case GENERIC = 'generic';

    public function calculateCheckDigits(string $document): string
    {
        return $this->utils()->checkDigits($document);
    }

    public function fake(): string
    {
        return $this->utils()->fake();
    }

    public function label(): string
    {
        return match ($this) {
            self::CNPJ => 'CNPJ',
            self::CPF => 'CPF',
            self::GENERIC => 'Genérico'
        };
    }

    public function mask(string $document): string
    {
        return $this->utils()->mask($document);
    }

    public function unmask(string $document): string
    {
        return $this->utils()->unmask($document);
    }

    public function utils(): Utils
    {
        return match ($this) {
            self::CNPJ => app(CNPJ::class),
            self::CPF => app(CPF::class),
            self::GENERIC => app(Generic::class),
        };
    }

    public function validate(string $document): bool
    {
        return $this->utils()->validate($document);
    }
}
