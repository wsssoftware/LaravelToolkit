<?php

namespace LaravelToolkit\Actions\Document;

use LaravelToolkit\Support\RegexTools;

class ValidateDocument
{
    use RegexTools;

    public function handle(string $document): bool
    {
        $document = $this->regexOnlyNumbers($document);
        if (strlen($document) === 14 && ! $this->regexIsSequenceOfUniqueChar($document)) {
            return app(ValidateCnpj::class)->handle($document);
        }
        if (strlen($document) === 11 && ! $this->regexIsSequenceOfUniqueChar($document)) {
            return app(ValidateCpf::class)->handle($document);
        }

        return false;
    }
}
