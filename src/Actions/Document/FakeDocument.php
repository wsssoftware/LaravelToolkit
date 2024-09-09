<?php

namespace LaravelToolkit\Actions\Document;

use Illuminate\Support\Lottery;
use LaravelToolkit\Enum\Document;
use LaravelToolkit\Support\RegexTools;

class FakeDocument
{
    use RegexTools;

    public function handle(Document $type): string
    {
        return match ($type) {
            Document::CNPJ => $this->cnpj(),
            Document::CPF => $this->cpf(),
            Document::GENERIC => Lottery::odds(0.5)
                ->winner(fn () => $this->cnpj())
                ->loser(fn () => $this->cpf())
                ->choose()
        };

    }

    protected function cnpj(): string
    {
        $cnpj = rand(10000000, 99999999).'000'.rand(1, 9);

        return $cnpj.app(GetDocumentCheckDigits::class)->handle(Document::CNPJ, $cnpj);
    }

    protected function cpf(): string
    {
        $cpf = str_pad(rand(10000000, 99999999), 9, '0', STR_PAD_LEFT);

        return $cpf.app(GetDocumentCheckDigits::class)->handle(Document::CPF, $cpf);
    }
}
