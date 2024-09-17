<?php

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use LaravelToolkit\Routing\Redirector;

it('get right instance', function () {
    $redirector = app(\Illuminate\Routing\Redirector::class);

    expect($redirector)
        ->toBeInstanceOf(Redirector::class);
});

it('get a redirect response', function () {
    $redirector = app(\Illuminate\Routing\Redirector::class);

    expect($redirector->to('/'))
        ->toBeInstanceOf(RedirectResponse::class);
});

it('get a redirect response2', function () {
    Request::macro('inertia', fn () => true);
    $redirector = app(\Illuminate\Routing\Redirector::class);

    $response = $redirector->to('https://google.com');
    expect($response)
        ->toBeInstanceOf(Response::class)
        ->and($response->status())
        ->toBe(409)
        ->and($response->headers->get('X-Inertia-Location'))
        ->toBe('https://google.com');
});
