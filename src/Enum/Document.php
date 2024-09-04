<?php

namespace LaravelToolkit\Enum;

use Illuminate\Support\Lottery;
use LaravelToolkit\Actions\Document\FakeCnpj;
use LaravelToolkit\Actions\Document\FakeCpf;
use LaravelToolkit\Actions\Document\GetCnpjCheckDigits;
use LaravelToolkit\Actions\Document\GetCpfCheckDigits;
use LaravelToolkit\Actions\Document\GetDocumentCheckDigits;
use LaravelToolkit\Actions\Document\MaskCnpj;
use LaravelToolkit\Actions\Document\MaskCpf;
use LaravelToolkit\Actions\Document\MaskDocument;
use LaravelToolkit\Actions\Document\ValidateCnpj;
use LaravelToolkit\Actions\Document\ValidateCpf;
use LaravelToolkit\Actions\Document\ValidateDocument;
use LaravelToolkit\Actions\Mask\UnmaskNumber;
use LaravelToolkit\Support\RegexTools;

enum Document: string
{
    use RegexTools;

    case CNPJ = 'cnpj';
    case CPF = 'cpf';
    case GENERIC = 'generic';

    public function calculateCheckDigits(string $document): string
    {
        $document = $this->regexOnlyNumbers($document);
        return match ($this) {
            self::CNPJ => app(GetCnpjCheckDigits::class)->handle($document),
            self::CPF => app(GetCpfCheckDigits::class)->handle($document),
            self::GENERIC => app(GetDocumentCheckDigits::class)->handle($document),
        };
    }

    public function fake(): string
    {
        return match ($this) {
            self::CNPJ => app(FakeCnpj::class)->handle(),
            self::CPF => app(FakeCpf::class)->handle(),
            self::GENERIC => Lottery::odds(0.5)
                ->winner(fn() => app(FakeCnpj::class)->handle())
                ->loser(fn() => app(FakeCpf::class)->handle())
                ->choose(),
        };
    }

    public function isValid(string $document): bool
    {
        return match ($this) {
            self::CNPJ => app(ValidateCnpj::class)->handle($document),
            self::CPF => app(ValidateCpf::class)->handle($document),
            self::GENERIC => app(ValidateDocument::class)->handle($document),
        };
    }

    public function mask(string $document): string
    {
        return match ($this) {
            self::CNPJ => app(MaskCnpj::class)->handle($document),
            self::CPF => app(MaskCpf::class)->handle($document),
            self::GENERIC => app(MaskDocument::class)->handle($document),
        };
    }

    public function unmask(string $document): string
    {
        return app(UnmaskNumber::class)->handle($document);
    }
}
