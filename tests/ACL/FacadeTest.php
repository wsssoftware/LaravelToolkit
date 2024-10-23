<?php

use LaravelToolkit\Facades\ACL;
use LaravelToolkit\Tests\Model\User;

it('can get user permission', function () {
    expect(ACL::userPermission())
        ->toBeNull();
    (new User(['id' => 1, 'name' => 'Foo', 'email' => 'foo@bar.com', 'password' => 'abc']))->saveOrFail();
    (new User(['id' => 2, 'name' => 'Foo', 'email' => 'foo2@bar.com', 'password' => 'abc']))->saveOrFail();
    $user = User::query()->where('id', 1)->firstOrFail();
    $user2 = User::query()->where('id', 2)->firstOrFail();

    Auth::setUser($user);

    expect(ACL::userPermission())
        ->toEqual($user->userPermission)
        ->and(ACL::userPermission())
        ->not
        ->toEqual($user2->userPermission)
        ->and(ACL::userPermission($user2))
        ->toEqual($user2->userPermission)
        ->and(ACL::userPermission($user2))
        ->not
        ->toEqual($user->userPermission);
});

it('can get permissions', function () {
    expect(ACL::permissions())
        ->toBeNull();
    (new User(['id' => 1, 'name' => 'Foo', 'email' => 'foo@bar.com', 'password' => 'abc']))->saveOrFail();
    (new User(['id' => 2, 'name' => 'Foo', 'email' => 'foo2@bar.com', 'password' => 'abc']))->saveOrFail();
    $user = User::query()->where('id', 1)->firstOrFail();
    $user2 = User::query()->where('id', 2)->firstOrFail();

    Auth::setUser($user);

    expect(ACL::permissions())
        ->toBeArray()
    ->and(ACL::gatePermissions())
        ->toBeArray();
});
