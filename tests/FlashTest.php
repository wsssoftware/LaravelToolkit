<?php

use Illuminate\Support\Collection;
use LaravelToolkit\Facades\Flash;
use LaravelToolkit\Flash\Message;

it('can send flash', function () {
    Flash::success('ok');
    Flash::info('ok');
    Flash::warn('ok');
    Flash::error('ok');
    Flash::secondary('ok');
    Flash::contrast('ok');

    expect(Flash::getMessages())
        ->toHaveCount(6)
        ->toBeInstanceOf(Collection::class)
        ->each
        ->toBeInstanceOf(Message::class);
});

it('can ge', function () {
    $this->get(route('lt.flash.get_messages'))
        ->assertSuccessful();
});
