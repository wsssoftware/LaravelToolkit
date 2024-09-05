<?php

use LaravelToolkit\Rules\Document;

it('can validate a cpf', function () {
    $data = [
        'cpf1' => '927.758.550-16',
        'cpf2' => '927.758.550-20',
    ];
    $rules = [
        'cpf1' => Document::cpf(),
        'cpf2' => Document::cpf(),
    ];
    $result = Validator::make($data, $rules)->errors();
    dd($result);
    ray($result);
//    expect($result->has('cpf1'))
//        ->toBeFalse()
//        ->and($result->has('cpf2'))
//        ->toBeTrue();
});
