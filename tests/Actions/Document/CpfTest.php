<?php

use LaravelToolkit\Actions\Document\FakeDocument;
use LaravelToolkit\Actions\Document\GetDocumentCheckDigits;
use LaravelToolkit\Actions\Document\MaskDocument;
use LaravelToolkit\Actions\Document\ValidateDocument;
use LaravelToolkit\Enum\Document;

it('can create a valid CPF', function () {
    expect(app(GetDocumentCheckDigits::class)->handle(Document::CPF, '042.849.040'))
        ->toBe('94')
        ->and(app(GetDocumentCheckDigits::class)->handle(Document::CPF, '060.313.750'))
        ->toBe('44')
        ->and(app(GetDocumentCheckDigits::class)->handle(Document::CPF, '213.879.670'))
        ->toBe('10')
        ->and(app(GetDocumentCheckDigits::class)->handle(Document::CPF, '598.504.710'))
        ->toBe('50')
        ->and(app(GetDocumentCheckDigits::class)->handle(Document::CPF, '598.504'))
        ->toBe('30');
});

it('can create a fake CPF', function () {
    expect(app(FakeDocument::class)->handle(Document::CPF))
        ->toBeString()
        ->toHaveLength(11);
});

it('can validate a CPF', function () {
    expect(app(ValidateDocument::class)->handle(Document::CPF, '032.121.050-62'))
        ->toBeTrue()
        ->and(app(ValidateDocument::class)->handle(Document::CPF, '032.121.050-61'))
        ->toBeFalse()
        ->and(app(ValidateDocument::class)->handle(Document::CPF, '297.616.150-06'))
        ->toBeTrue()
        ->and(app(ValidateDocument::class)->handle(Document::CPF, '297.616.150-16'))
        ->toBeFalse()
        ->and(app(ValidateDocument::class)->handle(Document::CPF, '297.616.-16'))
        ->toBeFalse();
});

it('can mask a CPF', function () {
    expect(app(MaskDocument::class)->handle('03212105062'))
        ->toEqual('032.121.050-62')
        ->and(app(MaskDocument::class)->handle('29761615006'))
        ->toEqual('297.616.150-06');
});
