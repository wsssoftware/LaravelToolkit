<?php

namespace LaravelToolkit\Support;

use Exception;
use Illuminate\Support\Lottery;
use LaravelToolkit\Facades\Regex;

/**
 * @see \LaravelToolkit\Facades\Document
 */
class Document
{
    public function checkDigitsFromCnpj(string $cnpj): string
    {
        if (strlen($cnpj) > 12) {
            $cnpj = substr($cnpj, 0, 12);
        } elseif (strlen($cnpj) < 12) {
            $cnpj = str_pad($cnpj, 12, '0');
        }
        $c = str($cnpj)->substr(0, 12)->reverse()->toString();

        $closure = function (string $x) {
            return $x[0] * 2 + $x[1] * 3 + $x[2] * 4 + $x[3] * 5 + $x[4] * 6 + $x[5] * 7 + $x[6] * 8 + $x[7] * 9 + $x[8] * 2 + $x[9] * 3 + $x[10] * 4 + $x[11] * 5;
        };

        $sum = $closure($c);
        $d1 = 11 - ($sum % 11);
        $d1 = $d1 >= 10 ? 0 : $d1;
        $c = $d1.$c;

        $sum = $closure($c) + $c[12] * 6;

        $d2 = 11 - ($sum % 11);
        $d2 = $d2 >= 10 ? 0 : $d2;

        return $d1.$d2;
    }


    public function checkDigitsFromCpf(string $cpf): string
    {
        if (strlen($cpf) > 9) {
            $cpf = substr($cpf, 0, 9);
        } elseif (strlen($cpf) < 9) {
            $cpf = str_pad($cpf, 9, '0');
        }
        $sum = $cpf[0] * 10 + $cpf[1] * 9 + $cpf[2] * 8 + $cpf[3] * 7 + $cpf[4] * 6 + $cpf[5] * 5 + $cpf[6] * 4 + $cpf[7] * 3 + $cpf[8] * 2;
        $d1 = 11 - ($sum % 11);
        $d1 = $d1 >= 10 ? 0 : $d1;
        $cpf .= $d1;

        $sum = $cpf[0] * 11 + $cpf[1] * 10 + $cpf[2] * 9 + $cpf[3] * 8 + $cpf[4] * 7 + $cpf[5] * 6 + $cpf[6] * 5 + $cpf[7] * 4 + $cpf[8] * 3 + $cpf[9] * 2;
        $d2 = 11 - ($sum % 11);
        $d2 = $d2 >= 10 ? 0 : $d2;

        return $d1.$d2;
    }

    public function checkDigitsFromGeneric(string $document): string
    {
        throw_if(! in_array(strlen($document), [9, 11, 12, 14]), Exception::class, 'Invalid document');

        return in_array(strlen($document), [9, 11])
            ? $this->checkDigitsFromCpf($document)
            : $this->checkDigitsFromCnpj($document);
    }

    public function fakeCnpj(): string
    {
        $cnpj = rand(10000000, 99999999).'000'.rand(1, 9);

        return $cnpj.$this->checkDigitsFromCnpj($cnpj);
    }

    protected function fakeCpf(): string
    {
        $cpf = str_pad(rand(10000000, 99999999), 9, '0', STR_PAD_LEFT);

        return $cpf.$this->checkDigitsFromCpf($cpf);
    }

    protected function fakeGeneric(): string
    {
       return Lottery::odds(0.5)
           ->winner(fn () => $this->fakeCnpj())
           ->loser(fn () => $this->fakeCpf())
           ->choose();
    }

    public function isValidCnpj(string $cnpj): bool
    {
        if (strlen($cnpj) != 14 || Regex::isSequenceOfUniqueChar($cnpj)) {
            return false;
        }

        return substr($cnpj, -2, 2) === $this->checkDigitsFromCnpj($cnpj);
    }

    public function isValidCpf(string $cpf): bool
    {
        if (strlen($cpf) != 11 || Regex::isSequenceOfUniqueChar($cpf)) {
            return false;
        }

        return substr($cpf, -2, 2) === $this->checkDigitsFromCpf($cpf);
    }

    public function isValidGeneric(string $document): bool
    {
        if (strlen($document) === 14 && ! Regex::isSequenceOfUniqueChar($document)) {
            return $this->isValidCnpj($document);
        }
        if (strlen($document) === 11 && ! Regex::isSequenceOfUniqueChar($document)) {
            return $this->isValidCpf($document);
        }

        return false;
    }

    public function mask(string $document): string
    {
        $document = Regex::onlyNumbers($document);
        throw_if(! in_array(strlen($document), [11, 14]), Exception::class, 'Invalid document');

        return \LaravelToolkit\Facades\Mask::apply(
            $document,
            strlen($document) === 11 ? '###.###.###-##' : '##.###.###/####-##'
        );
    }

    public function unmask(string $document): string
    {
        return Regex::onlyNumbers($document);
    }
}
