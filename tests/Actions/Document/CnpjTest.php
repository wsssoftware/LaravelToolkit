<?php

use LaravelToolkit\Actions\Document\FakeDocument;
use LaravelToolkit\Actions\Document\GetDocumentCheckDigits;
use LaravelToolkit\Actions\Document\MaskDocument;
use LaravelToolkit\Actions\Document\ValidateDocument;
use LaravelToolkit\Enum\Document;

it('can create a valid CNPJ', function () {
    expect(app(GetDocumentCheckDigits::class)->handle(Document::CNPJ, '45.857.098/0001'))
        ->toBe('36')
        ->and(app(GetDocumentCheckDigits::class)->handle(Document::CNPJ, '76.044.083/0001'))
        ->toBe('82')
        ->and(app(GetDocumentCheckDigits::class)->handle(Document::CNPJ, '42.044.452/0001'))
        ->toBe('15')
        ->and(app(GetDocumentCheckDigits::class)->handle(Document::CNPJ, '62.731.562/0001'))
        ->toBe('62')
        ->and(app(GetDocumentCheckDigits::class)->handle(Document::CNPJ, '62.731/0001'))
        ->toBe('91');
});

it('can create a fake CNPJ', function () {
    expect(app(FakeDocument::class)->handle(Document::CNPJ))
        ->toBeString()
        ->toHaveLength(14);
});

it('can validate a CNPJ', function () {
    expect(app(ValidateDocument::class)->handle(Document::CNPJ, '24.776.086/0001-07'))
        ->toBeTrue()
        ->and(app(ValidateDocument::class)->handle(Document::CNPJ, '24.776.086/0001-17'))
        ->toBeFalse()
        ->and(app(ValidateDocument::class)->handle(Document::CNPJ, '70.521.738/0001-80'))
        ->toBeTrue()
        ->and(app(ValidateDocument::class)->handle(Document::CNPJ, '70.521.738/0001-88'))
        ->toBeFalse()
        ->and(app(ValidateDocument::class)->handle(Document::CNPJ, '70.521.738/01-88'))
        ->toBeFalse();
});

it('can mask a CNPJ', function () {
    expect(app(MaskDocument::class)->handle('24776086000107'))
        ->toEqual('24.776.086/0001-07')
        ->and(app(MaskDocument::class)->handle('70521738000180'))
        ->toEqual('70.521.738/0001-80');
});
