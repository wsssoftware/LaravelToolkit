<?php

namespace LaravelToolkit\Enum;

use LaravelToolkit\Actions\Document\FakeDocument;
use LaravelToolkit\Actions\Document\GetDocumentCheckDigits;
use LaravelToolkit\Actions\Document\MaskDocument;
use LaravelToolkit\Actions\Document\ValidateDocument;
use LaravelToolkit\Actions\Mask\UnmaskNumber;
use LaravelToolkit\Support\RegexTools;

enum Document: string implements ArrayableEnum
{
    use HasArrayableEnum, RegexTools;

    case CNPJ = 'cnpj';
    case CPF = 'cpf';
    case GENERIC = 'generic';

    public function calculateCheckDigits(string $document): string
    {
        return app(GetDocumentCheckDigits::class)->handle($this, $document);
    }

    public function fake(): string
    {
        return app(FakeDocument::class)->handle($this);
    }

    public function isValid(string $document): bool
    {
        return app(ValidateDocument::class)->handle($this, $document);
    }

    public function mask(string $document): string
    {
        return app(MaskDocument::class)->handle($document);
    }

    public function unmask(string $document): string
    {
        return app(UnmaskNumber::class)->handle($document);
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
