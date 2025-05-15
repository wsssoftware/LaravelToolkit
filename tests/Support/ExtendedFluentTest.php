<?php

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use LaravelToolkit\Support\AsExtendedFluent;
use LaravelToolkit\Support\ExtendedFluent;
use LaravelToolkit\Tests\Model\User;
use LaravelToolkit\Tests\Support\FooBarExtendedFluent;

it('test extended fluent', function () {
    $fluent = new class extends ExtendedFluent
    {
        protected function fullName(): \Illuminate\Database\Eloquent\Casts\Attribute
        {
            return \Illuminate\Database\Eloquent\Casts\Attribute::make(
                get: fn ($value) => $this->name.' '.$this->last_name,
                set: function ($value) {
                    $this->name = explode(' ', $value)[0];
                    $this->last_name = explode(' ', $value)[1];
                },
            );
        }
    };

    $fluent->name = 'Paul';
    $fluent->last_name = 'Job';

    expect($fluent->full_name)
        ->toEqual('Paul Job');

    $fluent->full_name = 'Foo Bar';
    expect($fluent->name)
        ->toEqual('Foo')
        ->and($fluent->last_name)
        ->toEqual('Bar');
});

it('test extended cast', function () {

    $cast = AsExtendedFluent::castUsing([FooBarExtendedFluent::class]);
    $model = new User;

    expect(AsExtendedFluent::of(FooBarExtendedFluent::class))
        ->toEqual(AsExtendedFluent::class.':'.FooBarExtendedFluent::class)
        ->and(fn () => AsExtendedFluent::castUsing(['invalid_class']))
        ->toThrow('Class invalid_class does not exist.')
        ->and(fn () => AsExtendedFluent::castUsing([AsExtendedFluent::class]))
        ->toThrow('Class LaravelToolkit\Support\AsExtendedFluent does not extend ExtendedFluent.')
        ->and($cast)
        ->toBeInstanceOf(CastsAttributes::class)
        ->and($cast->get($model, 'foo', '----', []))
        ->toBe('----')
        ->and($cast->get($model, 'foo', '{"id": 2}', []))
        ->toBeInstanceOf(FooBarExtendedFluent::class)
        ->and($cast->get($model, 'foo', '{"id": 2}', []))
        ->toBeInstanceOf(FooBarExtendedFluent::class)
        ->and($cast->set($model, 'foo', new FooBarExtendedFluent(['id' => 2]), []))
        ->toBeJson()
        ->and($cast->set($model, 'foo', 'invalid', []))
        ->toBe('invalid');
});
