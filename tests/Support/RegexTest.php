<?php

use LaravelToolkit\Facades\Regex;
use LaravelToolkit\Support\RegexTools;

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

it('test cass', function () {
    $class = new class
    {
      use RegexTools;
    };
    expect($class->regexIsLikePhpVariableChars('foo_bar1'))
        ->toBeTrue()
        ->and($class->regexIsLikePhpVariableChars('fooBar2'))
        ->toBeTrue()
        ->and($class->regexIsLikePhpVariableChars('1fooBar3'))
        ->toBeFalse()
        ->and($class->regexIsLikePhpVariableChars('$fooBar3'))
        ->toBeFalse()
        ->and($class->regexIsLikePhpVariableChars('$@@@#$'))
        ->toBeFalse()
        ->and($class->regexIsSequenceOfUniqueChar('a'))
        ->toBeTrue()
        ->and($class->regexIsSequenceOfUniqueChar('bbbbbb'))
        ->toBeTrue()
        ->and($class->regexIsSequenceOfUniqueChar('33333'))
        ->toBeTrue()
        ->and($class->regexIsSequenceOfUniqueChar('$$$$$'))
        ->toBeTrue()
        ->and($class->regexIsSequenceOfUniqueChar('$$AA'))
        ->toBeFalse()
        ->and($class->regexIsSequenceOfUniqueChar('AAaa'))
        ->toBeFalse()
        ->and($class->regexOnlyNumbers('AA1133BB'))
        ->toEqual('1133');
});
