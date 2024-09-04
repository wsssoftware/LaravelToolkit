<?php

namespace LaravelToolkit\Actions\Document;

use LaravelToolkit\Support\RegexTools;

class ValidateCnpj
{
    use RegexTools;

    public function handle(string $cnpj): bool
    {
        $cnpj = $this->regexOnlyNumbers($cnpj);
        if (strlen($cnpj) != 14 || $this->regexIsSequenceOfUniqueChar($cnpj)) {
            return false;
        }

        return substr($cnpj, -2, 2) === app(GetCnpjCheckDigits::class)->handle($cnpj);
    }
}
