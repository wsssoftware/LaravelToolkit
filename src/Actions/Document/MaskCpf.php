<?php

namespace LaravelToolkit\Actions\Document;

use LaravelToolkit\Actions\Mask\MaskNumber;
use LaravelToolkit\Support\RegexTools;

class MaskCpf
{
    use RegexTools;

    public function handle(string $cpf): string
    {
        return app(MaskNumber::class)->handle('###.###.###-##', $cpf);
    }
}
