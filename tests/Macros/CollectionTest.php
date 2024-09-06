<?php

use Illuminate\Support\Collection;
use LaravelToolkit\Tests\Macros\FakeModel;

it('test locale sort', function () {
    $collection = collect(['João', 'Allan', 'Álan', 'Uai', 'Úrsula']);
    expect($collection->localeSort()->first())
        ->toBe('Álan')
        ->and($collection->localeSortDesc()->first())
        ->toBe('Úrsula');
});

it('test locale sort by', function () {
    $collection = collect([
        ['name' => 'João'], ['name' => 'Allan'], ['name' => 'Álan'], ['name' => 'Uai'], ['name' => 'Úrsula'],
    ]);
    expect($collection->localeSortBy('name')->first()['name'])
        ->toBe('Álan')
        ->and($collection->localeSortByDesc('name')->first()['name'])
        ->toBe('Úrsula');
});

it('test value label', function () {
    $collection = collect([
        new FakeModel(['id' => 1, 'name' => 'João']),
        new FakeModel(['id' => 2, 'name' => 'Paulo']),
        new FakeModel(['id' => 3, 'name' => 'Lucas']),
        new FakeModel(['id' => 4, 'name' => 'Fabio']),
        new FakeModel(['id' => 5, 'name' => 'Carlos']),
    ]);
    $collection2 = collect([
        'user_1' => 'João',
        'user_2' => 'Paulo',
        'user_3' => 'Lucas',
        'user_4' => 'Fabio',
        'user_5' => 'Carlos',
    ]);
    expect($collection->toValueLabelFromObject('name', 'id'))
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(5)
        ->each
        ->toBeArray()
        ->toHaveKeys(['label', 'value'])
        ->and($collection->toValueLabelFromObject('name', 'id', 'foo', 'bar'))
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(5)
        ->each
        ->toBeArray()
        ->toHaveKeys(['foo', 'bar'])
        ->and($collection2->toValueLabelFromArray())
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(5)
        ->each
        ->toBeArray()
        ->toHaveKeys(['label', 'value'])
        ->and($collection2->toValueLabelFromArray('foo', 'bar'))
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(5)
        ->each
        ->toBeArray()
        ->toHaveKeys(['foo', 'bar']);
});
