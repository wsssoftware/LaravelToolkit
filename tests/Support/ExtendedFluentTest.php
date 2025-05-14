<?php

use LaravelToolkit\Support\ExtendedFluent;

it('test extended fluent', function () {
    $fluent = new class extends ExtendedFluent
    {
        protected function fullName(): \Illuminate\Database\Eloquent\Casts\Attribute
        {
            return \Illuminate\Database\Eloquent\Casts\Attribute::make(
                get: fn ($value) => $this->attributes['name'].' '.$this->attributes['last_name'],
                set: function ($value) {
                    $this->attributes['name'] = explode(' ', $value)[0];
                    $this->attributes['last_name'] = explode(' ', $value)[1];
                },
            );
        }
    };
    $fluent->fill([
        'id' => 12,
        'name' => 'Paul',
        'last_name' => 'Job',
    ]);

    expect($fluent->full_name)
        ->toEqual('Paul Job');

    $fluent->full_name = 'Foo Bar';
    expect($fluent->name)
        ->toEqual('Foo')
        ->and($fluent->last_name)
        ->toEqual('Bar');
});
