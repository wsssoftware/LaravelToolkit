<?php

use Illuminate\Contracts\Support\Responsable;
use LaravelToolkit\Filepond\Abortable;
use Illuminate\Http\Response;

it('can create', function () {
    $a = Abortable::make('foo', 400);

    expect($a)
        ->toBeInstanceOf(Abortable::class)
        ->toBeInstanceOf(Responsable::class)
        ->and($a->reason)
        ->toEqual('foo')
        ->and($a->status)
        ->toEqual(400)
        ->and($a->toResponse(request()))
        ->toBeInstanceOf(Response::class);
});
