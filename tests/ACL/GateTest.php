<?php

use LaravelToolkit\Tests\Model\User;

it('', function () {
    $user = User::query()->firstOrFail();

    $user->userPermission->users->create->value = true;
    $user->userPermission->saveOrFail();
    $user->userPermission->refresh();
    $this->actingAs($user);

    ray(Gate::abilities());

    expect(Gate::allows('users::create'))->toBeTrue();
});
