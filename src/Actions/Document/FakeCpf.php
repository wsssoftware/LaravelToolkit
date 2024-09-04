<?php

namespace LaravelToolkit\Actions\Document;

use LaravelToolkit\Support\RegexTools;

class FakeCpf
{
    use RegexTools;

    public function handle(): string
    {
        $cpf = str_pad(rand(10000000, 99999999), 9, '0', STR_PAD_LEFT);

        return $cpf.app(GetCpfCheckDigits::class)->handle($cpf);
    }
}
