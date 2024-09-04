<?php

use LaravelToolkit\Actions\Mask\MaskNumber;
use LaravelToolkit\Actions\Mask\UnmaskNumber;

it('can mask a number', function () {
    expect(app(MaskNumber::class)->handle('##.###-###', '86870000'))
        ->toEqual('86.870-000')
        ->and(app(MaskNumber::class)->handle('#.###,##', '812332'))
        ->toEqual('8.123,32');
});

it('can unmask a number', function () {
    expect(app(UnmaskNumber::class)->handle('86.870-000'))
        ->toEqual('86870000')
        ->and(app(UnmaskNumber::class)->handle('8.123,32'))
        ->toEqual('812332');
});
