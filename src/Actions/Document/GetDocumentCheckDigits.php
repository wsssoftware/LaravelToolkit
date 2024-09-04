<?php

namespace LaravelToolkit\Actions\Document;

use Exception;
use LaravelToolkit\Support\RegexTools;

class GetDocumentCheckDigits
{
    use RegexTools;

    public function handle(string $document): string
    {
        $document = $this->regexOnlyNumbers($document);
        throw_if(!in_array(strlen($document), [9, 11, 12, 14]), Exception::class, 'Invalid document');
        $handler = in_array(strlen($document), [9, 11])
            ? app(GetCpfCheckDigits::class)
            : app(GetCnpjCheckDigits::class);
        return $handler->handle($document);
    }
}
