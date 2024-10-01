<?php

it('can apply mask', function () {
    expect(Str::applyMask('12ABCa2c123B4', 'SSS AAA 0000'))
        ->toEqual('ABC a2c 1234')
        ->and($stringable = str('12ABCa2c123B4')->applyMask('SSS AAA 0000'))
        ->toBeInstanceOf(\Illuminate\Support\Stringable::class)
        ->and($stringable->toString())
        ->toEqual('ABC a2c 1234')
        ->and(Str::applyMask(' 12ABCa2c123B4', '\0\A\S SSS AAA 0000'))
        ->toEqual('0AS ABC a2c 1234')
        ->and(Str::applyMask(' 12', '0000'))
        ->toEqual('12');

});
