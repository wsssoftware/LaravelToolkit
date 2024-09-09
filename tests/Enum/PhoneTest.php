<?php

use LaravelToolkit\Enum\Phone;

it('can guess from number', function () {
    expect(Phone::guessType('4132324545'))
        ->toBe(Phone::LANDLINE)
        ->and(Phone::guessType('40031234'))
        ->toBe(Phone::LOCAL_FARE)
        ->and(Phone::guessType('32988232323'))
        ->toBe(Phone::MOBILE)
        ->and(Phone::guessType('08001235432'))
        ->toBe(Phone::NON_REGIONAL)
        ->and(Phone::guessType('123'))
        ->toBe(Phone::PUBLIC_SERVICES);

});

it('can validate', function () {
    expect(Phone::LANDLINE->isValid('4132324545'))
        ->toBeTrue()
        ->and(Phone::LANDLINE->isValid('4192324545'))
        ->toBeFalse()
        ->and(Phone::LOCAL_FARE->isValid('40031234'))
        ->toBeTrue()
        ->and(Phone::LOCAL_FARE->isValid('39991234'))
        ->toBeFalse()
        ->and(Phone::MOBILE->isValid('32988232323'))
        ->toBeTrue()
        ->and(Phone::MOBILE->isValid('32948232323'))
        ->toBeFalse()
        ->and(Phone::NON_REGIONAL->isValid('08001235432'))
        ->toBeTrue()
        ->and(Phone::NON_REGIONAL->isValid('00001235432'))
        ->toBeFalse()
        ->and(Phone::PUBLIC_SERVICES->isValid('123'))
        ->toBeTrue()
        ->and(Phone::PUBLIC_SERVICES->isValid('1223'))
        ->toBeFalse()
        ->and(Phone::GENERIC->isValid('4132324545'))
        ->toBeTrue()
        ->and(Phone::GENERIC->isValid('4192324545'))
        ->toBeFalse()
        ->and(Phone::GENERIC->isValid('40031234'))
        ->toBeTrue()
        ->and(Phone::GENERIC->isValid('39991234'))
        ->toBeFalse()
        ->and(Phone::GENERIC->isValid('32988232323'))
        ->toBeTrue()
        ->and(Phone::GENERIC->isValid('32948232323'))
        ->toBeFalse()
        ->and(Phone::GENERIC->isValid('08001235432'))
        ->toBeTrue()
        ->and(Phone::GENERIC->isValid('00001235432'))
        ->toBeFalse()
        ->and(Phone::GENERIC->isValid('123'))
        ->toBeTrue()
        ->and(Phone::GENERIC->isValid('1223'))
        ->toBeFalse()
        ->and(Phone::GENERIC->isValid('1'))
        ->toBeFalse();

});

it('can mask', function () {
    expect(Phone::LANDLINE->mask('4132324545'))
        ->toEqual('(41) 3232-4545')
        ->and(Phone::LOCAL_FARE->mask('40012132'))
        ->toEqual('4001-2132')
        ->and(Phone::MOBILE->mask('43988762432'))
        ->toEqual('(43) 9 8876-2432')
        ->and(Phone::NON_REGIONAL->mask('08003221234'))
        ->toEqual('0800-322-1234')
        ->and(Phone::PUBLIC_SERVICES->mask('180'))
        ->toEqual('180')
        ->and(Phone::GENERIC->mask('4132324545'))
        ->toEqual('(41) 3232-4545')
        ->and(Phone::GENERIC->mask('40012132'))
        ->toEqual('4001-2132')
        ->and(Phone::GENERIC->mask('43988762432'))
        ->toEqual('(43) 9 8876-2432')
        ->and(Phone::GENERIC->mask('180'))
        ->toEqual('180')
        ->and(Phone::GENERIC->mask('1'))
        ->toEqual('1');
});

it('can get label', function (Phone $phone) {
    expect($phone->label())
        ->toBeString();
})->with(Phone::cases());

it('can create a fake', function (Phone $phone) {
    expect($phone->isValid($phone->fake()))
        ->toBeTrue();
})->with(Phone::cases())
    ->repeat(4);
