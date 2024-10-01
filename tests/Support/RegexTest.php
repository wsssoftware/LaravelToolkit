<?php

use LaravelToolkit\Facades\Regex;

it('test facade', function () {
    expect(Regex::isLikePhpVariableChars('foo_bar1'))
        ->toBeTrue()
        ->and(Regex::isLikePhpVariableChars('fooBar2'))
        ->toBeTrue()
        ->and(Regex::isLikePhpVariableChars('1fooBar3'))
        ->toBeFalse()
        ->and(Regex::isLikePhpVariableChars('$fooBar3'))
        ->toBeFalse()
        ->and(Regex::isLikePhpVariableChars('$@@@#$'))
        ->toBeFalse()
        ->and(Regex::isSequenceOfUniqueChar('a'))
        ->toBeTrue()
        ->and(Regex::isSequenceOfUniqueChar('bbbbbb'))
        ->toBeTrue()
        ->and(Regex::isSequenceOfUniqueChar('33333'))
        ->toBeTrue()
        ->and(Regex::isSequenceOfUniqueChar('$$$$$'))
        ->toBeTrue()
        ->and(Regex::isSequenceOfUniqueChar('$$AA'))
        ->toBeFalse()
        ->and(Regex::isSequenceOfUniqueChar('AAaa'))
        ->toBeFalse()
        ->and(Regex::onlyNumbers('AA1133BB'))
        ->toEqual('1133');
});
