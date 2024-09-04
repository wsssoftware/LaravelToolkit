<?php

namespace LaravelToolkit\Actions\Document;

use Exception;
use LaravelToolkit\Support\RegexTools;

class MaskDocument
{
    use RegexTools;

    public function handle(string $document): string
    {
        $document = $this->regexOnlyNumbers($document);
        throw_if(!in_array(strlen($document), [11, 14]), Exception::class, 'Invalid document');
        $handler = strlen($document) === 11 ? app(MaskCpf::class) : app(MaskCnpj::class);
        return $handler->handle($document);
    }
}
