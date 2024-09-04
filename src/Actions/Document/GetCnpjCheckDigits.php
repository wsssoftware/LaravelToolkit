<?php

namespace LaravelToolkit\Actions\Document;

use LaravelToolkit\Support\RegexTools;

class GetCnpjCheckDigits
{
    use RegexTools;

    public function handle(string $cnpj): string
    {
        $cnpj = $this->regexOnlyNumbers($cnpj);
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
}
