<?php

namespace LaravelToolkit\Actions\Document;

use LaravelToolkit\Actions\Mask\MaskNumber;
use LaravelToolkit\Support\RegexTools;

class MaskCnpj
{
    use RegexTools;

    public function handle(string $cnpj): string
    {
        return app(MaskNumber::class)->handle('##.###.###/####-##', $cnpj);
    }
}
