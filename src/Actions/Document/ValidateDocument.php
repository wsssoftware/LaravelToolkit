<?php

namespace LaravelToolkit\Actions\Document;

use LaravelToolkit\Enum\Document;
use LaravelToolkit\Support\RegexTools;

class ValidateDocument
{
    use RegexTools;

    public function handle(Document $type, string $document): bool
    {
        $document = $this->regexOnlyNumbers($document);

        return match ($type) {
            Document::CNPJ => $this->cnpj($document),
            Document::CPF => $this->cpf($document),
            Document::GENERIC => $this->generic($document),
        };

    }

    public function cnpj(string $cnpj): bool
    {
        if (strlen($cnpj) != 14 || $this->regexIsSequenceOfUniqueChar($cnpj)) {
            return false;
        }

        return substr($cnpj, -2, 2) === app(GetDocumentCheckDigits::class)->handle(Document::CNPJ, $cnpj);
    }

    public function cpf(string $cpf): bool
    {
        if (strlen($cpf) != 11 || $this->regexIsSequenceOfUniqueChar($cpf)) {
            return false;
        }

        return substr($cpf, -2, 2) === app(GetDocumentCheckDigits::class)->handle(Document::CPF, $cpf);
    }

    public function generic(string $document): bool
    {
        if (strlen($document) === 14 && ! $this->regexIsSequenceOfUniqueChar($document)) {
            return $this->cnpj($document);
        }
        if (strlen($document) === 11 && ! $this->regexIsSequenceOfUniqueChar($document)) {
            return $this->cpf($document);
        }

        return false;
    }
}
