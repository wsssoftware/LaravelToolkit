<?php


use LaravelToolkit\Actions\Document\GetDocumentCheckDigits;
use LaravelToolkit\Actions\Document\MaskDocument;
use LaravelToolkit\Actions\Document\ValidateDocument;

it('can create a valid CNPJ', function () {
    $handler = app(GetDocumentCheckDigits::class);
    expect($handler->handle('839.760.980'))
        ->toBe('60')
        ->and($handler->handle('738.554.510'))
        ->toBe('60')
        ->and($handler->handle('811.940.790-32'))
        ->toBe('32')
        ->and($handler->handle('232.049.290-94'))
        ->toBe('94')
        ->and($handler->handle('93.998.102/0001'))
        ->toBe('29')
        ->and($handler->handle('95.701.649/0001'))
        ->toBe('46')
        ->and($handler->handle('43.814.425/0001-47'))
        ->toBe('47')
        ->and($handler->handle('79.883.544/0001-07'))
        ->toBe('07')
        ->and(fn() => $handler->handle('3212325421'))
        ->toThrow('Invalid document');
});

it('can validate a CPF', function () {
    expect(app(ValidateDocument::class)->handle('024.894.190-99'))
        ->toBeTrue()
        ->and(app(ValidateDocument::class)->handle('024.894.190-00'))
        ->toBeFalse()
        ->and(app(ValidateDocument::class)->handle('605.014.840-60'))
        ->toBeTrue()
        ->and(app(ValidateDocument::class)->handle('605.014.840-63'))
        ->toBeFalse()
        ->and(app(ValidateDocument::class)->handle('01.852.228/0001-72'))
        ->toBeTrue()
        ->and(app(ValidateDocument::class)->handle('01.852.228/0001-22'))
        ->toBeFalse()
        ->and(app(ValidateDocument::class)->handle('16.598.847/0001-50'))
        ->toBeTrue()
        ->and(app(ValidateDocument::class)->handle('16.598.847/0001-42'))
        ->toBeFalse();
});


it('can mask a CNPJ', function () {
    expect(app(MaskDocument::class)->handle('03212105062'))
        ->toEqual('032.121.050-62')
        ->and(app(MaskDocument::class)->handle('29761615006'))
        ->toEqual('297.616.150-06')
        ->and(app(MaskDocument::class)->handle('24776086000107'))
        ->toEqual('24.776.086/0001-07')
        ->and(app(MaskDocument::class)->handle('70521738000180'))
        ->toEqual('70.521.738/0001-80')
        ->and(fn() => app(MaskDocument::class)->handle('705217'))
        ->toThrow('Invalid document');
});
