<?php

namespace LaravelToolkit\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use InvalidArgumentException;
use LaravelToolkit\Enum\Document as DocumentEnum;

readonly class Document implements ValidationRule
{

    public function __construct(
        public DocumentEnum $types = DocumentEnum::GENERIC
    ) {
        if ($this->types < 1 || $this->types > self::GENERIC) {
            throw new InvalidArgumentException('Configuração inválida para o tipo de documento.');
        }
    }

    public static function both(): self
    {
        return new self;
    }

    public static function cnpj(): self
    {
        return new self(self::CNPJ);
    }

    public static function cpf(): self
    {
        return new self(self::CPF);
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('validation.string')->translate();

            return;
        }
        $value = preg_replace('/[^0-9]/i', '', $value);
        $length = strlen($value);

        match (true) {
            $this->types === self::GENERIC
            && ! in_array($length, [11, 14]) => $fail('validation.document.generic.size')->translate(),
            $this->types === self::CNPJ
            && $length !== 14 => $fail('validation.document.cnpj.size')->translate(),
            $this->types === self::CPF
            && $length !== 11 => $fail('validation.document.cpf.size')->translate(),
            $this->types === self::GENERIC
            && $length === 14
            && ! $this->validCnpj($value) => $fail('validation.document.generic.invalid')->translate(),
            $this->types === self::GENERIC
            && $length === 11
            && ! $this->validCpf($value) => $fail('validation.document.generic.invalid')->translate(),
            $this->types === self::CNPJ && $length === 14
            && ! $this->validCnpj($value) => $fail('validation.document.cnpj.invalid')->translate(),
            $this->types === self::CPF && $length === 11
            && ! $this->validCpf($value) => $fail('validation.document.cpf.invalid')->translate(),
            default => true,
        };
    }
}
