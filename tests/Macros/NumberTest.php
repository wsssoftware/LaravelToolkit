<?php

it('can spell', function () {
    expect(Number::spellCurrency(1))
        ->toEqual('um dólar')
        ->and(Number::spellCurrency(2))
        ->toEqual('dois dólares')
        ->and(Number::spellCurrency(2))
        ->toEqual('dois dólares')
        ->and(Number::spellCurrency(0.43))
        ->toEqual('zero dólares e quarenta e três centavos')
        ->and(Number::spellCurrency(1_432_321.52))
        ->toEqual('um milhão quatrocentos e trinta e dois mil trezentos e vinte e um dólares e cinquenta e dois centavos');
});
