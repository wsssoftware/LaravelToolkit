<?php

namespace LaravelToolkit\Actions\Document;

use LaravelToolkit\Support\RegexTools;

class GetCpfCheckDigits
{
    use RegexTools;

    public function handle(string $cpf): string
    {
        $cpf = $this->regexOnlyNumbers($cpf);
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
}
