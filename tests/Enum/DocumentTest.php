<?php

use LaravelToolkit\Enum\Document;

it('can get check digits', function () {
    expect(Document::CPF->calculateCheckDigits('808.643.330'))
        ->toBe('72')
        ->and(Document::CPF->calculateCheckDigits('751.678.140-14'))
        ->toBe('14')
        ->and(Document::CNPJ->calculateCheckDigits('54.133.627/0001'))
        ->toBe('40')
        ->and(Document::CNPJ->calculateCheckDigits('06.064.076/0001-67'))
        ->toBe('67')
        ->and(Document::GENERIC->calculateCheckDigits('309.231.240'))
        ->toBe('63')
        ->and(Document::GENERIC->calculateCheckDigits('998.508.220-66'))
        ->toBe('66')
        ->and(Document::GENERIC->calculateCheckDigits('26.244.177/0001'))
        ->toBe('27')
        ->and(Document::GENERIC->calculateCheckDigits('70.413.981/0001-84'))
        ->toBe('84');
});

it('can generate fakes', function () {
    $generic = Document::GENERIC->fake();
    expect(Document::CPF->fake())
        ->toBeString()
        ->toHaveLength(11)
        ->and(Document::CNPJ->fake())
        ->toBeString()
        ->toHaveLength(14)
        ->and($generic)
        ->toBeString()
        ->and(in_array(strlen($generic), [11, 14]))
        ->toBeTrue();
})->repeat(20);

it('can validate', function () {
    expect(Document::CPF->validate('050.504.760-89'))
        ->toBeTrue()
        ->and(Document::CPF->validate('050.504.760-29'))
        ->toBeFalse()
        ->and(Document::CPF->validate('444.760.640-27'))
        ->toBeTrue()
        ->and(Document::CPF->validate('444.760.640-21'))
        ->toBeFalse()
        ->and(Document::CNPJ->validate('58.110.408/0001-04'))
        ->toBeTrue()
        ->and(Document::CNPJ->validate('58.110.408/0001-12'))
        ->toBeFalse()
        ->and(Document::CNPJ->validate('80.096.933/0001-63'))
        ->toBeTrue()
        ->and(Document::CNPJ->validate('80.096.933/0001-43'))
        ->toBeFalse()
        ->and(Document::GENERIC->validate('510.403.630-83'))
        ->toBeTrue()
        ->and(Document::GENERIC->validate('510.403.630-81'))
        ->toBeFalse()
        ->and(Document::GENERIC->validate('44.600.135/0001-63'))
        ->toBeTrue()
        ->and(Document::GENERIC->validate('44.600.135/0001-62'))
        ->toBeFalse();
});

it('can unmask', function () {
    expect(Document::CPF->unmask('050.504.760-89'))
        ->toEqual('05050476089')
        ->and(Document::CNPJ->unmask('58.110.408/0001-04'))
        ->toEqual('58110408000104');
});

it('can mask', function () {
    expect(Document::CPF->mask('05050476089'))
        ->toEqual('050.504.760-89')
        ->and(Document::CNPJ->mask('58110408000104'))
        ->toEqual('58.110.408/0001-04')
        ->and(Document::GENERIC->mask('05050476089'))
        ->toEqual('050.504.760-89')
        ->and(Document::GENERIC->mask('58110408000104'))
        ->toEqual('58.110.408/0001-04');
});

it('can get label', function (Document $document) {
    expect($document->label())
        ->toBeString();
})->with(Document::cases());
