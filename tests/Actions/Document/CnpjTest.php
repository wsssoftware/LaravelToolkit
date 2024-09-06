<?php

use LaravelToolkit\Actions\Document\FakeCnpj;
use LaravelToolkit\Actions\Document\GetCnpjCheckDigits;
use LaravelToolkit\Actions\Document\MaskCnpj;
use LaravelToolkit\Actions\Document\ValidateCnpj;

it('can create a valid CNPJ', function () {
    expect(app(GetCnpjCheckDigits::class)->handle('45.857.098/0001'))
        ->toBe('36')
        ->and(app(GetCnpjCheckDigits::class)->handle('76.044.083/0001'))
        ->toBe('82')
        ->and(app(GetCnpjCheckDigits::class)->handle('42.044.452/0001'))
        ->toBe('15')
        ->and(app(GetCnpjCheckDigits::class)->handle('62.731.562/0001'))
        ->toBe('62');
});

it('can create a fake CNPJ', function () {
    expect(app(FakeCnpj::class)->handle())
        ->toBeString()
        ->toHaveLength(14);
});

it('can validate a CNPJ', function () {
    expect(app(ValidateCnpj::class)->handle('24.776.086/0001-07'))
        ->toBeTrue()
        ->and(app(ValidateCnpj::class)->handle('24.776.086/0001-17'))
        ->toBeFalse()
        ->and(app(ValidateCnpj::class)->handle('70.521.738/0001-80'))
        ->toBeTrue()
        ->and(app(ValidateCnpj::class)->handle('70.521.738/0001-88'))
        ->toBeFalse();
});

it('can mask a CNPJ', function () {
    expect(app(MaskCnpj::class)->handle('24776086000107'))
        ->toEqual('24.776.086/0001-07')
        ->and(app(MaskCnpj::class)->handle('70521738000180'))
        ->toEqual('70.521.738/0001-80');
});
