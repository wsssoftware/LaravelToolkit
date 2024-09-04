<?php

namespace LaravelToolkit\Actions\Document;

use LaravelToolkit\Support\RegexTools;

class FakeCnpj
{
    use RegexTools;

    public function handle(): string
    {
        $cnpj = rand(10000000, 99999999).'000'.rand(1, 9);

        return $cnpj.app(GetCnpjCheckDigits::class)->handle($cnpj);
    }
}
