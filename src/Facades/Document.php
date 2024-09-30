<?php

namespace LaravelToolkit\Facades;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 *
 * @method static string checkDigitsFromCnpj(string $cnpj)
 * @method static string checkDigitsFromCpf(string $cpf)
 * @method static string checkDigitsFromGeneric(string $document)
 * @method static string fakeCnpj()
 * @method static string fakeCpf()
 * @method static string fakeGeneric()
 * @method static bool isValidCnpj(string $cnpj)
 * @method static bool isValidCpf(string $cnpj)
 * @method static bool isValidGeneric(string $document)
 * @method static string mask(string $document)
 * @method static string unmask(string $document)
 *
 * @see \LaravelToolkit\Support\Document
 */
class Document extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \LaravelToolkit\Support\Document::class;
    }
}
