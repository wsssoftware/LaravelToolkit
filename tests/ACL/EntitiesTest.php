<?php

use LaravelToolkit\ACL\Policy;
use LaravelToolkit\ACL\PolicyMaker;
use LaravelToolkit\ACL\Rule;

it('test policy and rule', function () {
    $policyMaker = new PolicyMaker(
        collect(),
        'users',
        'Users',
        'Make users'
    );

    expect(fn() => $policyMaker->rule('column', 'test', 'test'))
        ->toThrow('column is a reserved name and cannot used on key.');

    $policyMaker->crud()->rule('foo', 'Foo', 'FooBar');


    expect($policyMaker)
        ->toBeInstanceOf(PolicyMaker::class)
        ->and($policyMaker->rules)
        ->toHaveCount(5)
        ->and($policy = $policyMaker->toPolicy())
        ->toBeInstanceOf(Policy::class)
        ->and(fn() => $policy->abc)
        ->toThrow('Property abc does not exist.')
        ->and($rule = $policy->create)
        ->toBeInstanceOf(Rule::class)
        ->and($rule->value)
        ->toBeNull()
        ->and($rule->setValue(true))
        ->toBeNull()
        ->and($rule->value)
        ->toBeTrue();


});
