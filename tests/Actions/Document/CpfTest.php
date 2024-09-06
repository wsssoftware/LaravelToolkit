<?php

use LaravelToolkit\Actions\Document\FakeCpf;
use LaravelToolkit\Actions\Document\GetCpfCheckDigits;
use LaravelToolkit\Actions\Document\MaskCpf;
use LaravelToolkit\Actions\Document\ValidateCpf;

it('can create a valid CPF', function () {
    expect(app(GetCpfCheckDigits::class)->handle('042.849.040'))
        ->toBe('94')
        ->and(app(GetCpfCheckDigits::class)->handle('060.313.750'))
        ->toBe('44')
        ->and(app(GetCpfCheckDigits::class)->handle('213.879.670'))
        ->toBe('10')
        ->and(app(GetCpfCheckDigits::class)->handle('598.504.710'))
        ->toBe('50')
        ->and(app(GetCpfCheckDigits::class)->handle('598.504'))
        ->toBe('30');
});

it('can create a fake CPF', function () {
    expect(app(FakeCpf::class)->handle())
        ->toBeString()
        ->toHaveLength(11);
});

it('can validate a CPF', function () {
    expect(app(ValidateCpf::class)->handle('032.121.050-62'))
        ->toBeTrue()
        ->and(app(ValidateCpf::class)->handle('032.121.050-61'))
        ->toBeFalse()
        ->and(app(ValidateCpf::class)->handle('297.616.150-06'))
        ->toBeTrue()
        ->and(app(ValidateCpf::class)->handle('297.616.150-16'))
        ->toBeFalse()
        ->and(app(ValidateCpf::class)->handle('297.616.-16'))
        ->toBeFalse();
});

it('can mask a CPF', function () {
    expect(app(MaskCpf::class)->handle('03212105062'))
        ->toEqual('032.121.050-62')
        ->and(app(MaskCpf::class)->handle('29761615006'))
        ->toEqual('297.616.150-06');
});
