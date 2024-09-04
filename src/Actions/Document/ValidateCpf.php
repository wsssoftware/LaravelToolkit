<?php

namespace LaravelToolkit\Actions\Document;

use LaravelToolkit\Support\RegexTools;

class ValidateCpf
{
    use RegexTools;

    public function handle(string $cpf): bool
    {
        $cpf = $this->regexOnlyNumbers($cpf);
        if (strlen($cpf) != 11 || $this->regexIsSequenceOfUniqueChar($cpf)) {
            return false;
        }

        return substr($cpf, -2, 2) === app(GetCpfCheckDigits::class)->handle($cpf);
    }
}
