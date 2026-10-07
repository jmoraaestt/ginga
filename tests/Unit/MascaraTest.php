<?php

use Ginga\Support\Mascara;

it('formata valores vindos do banco', function (string $tipo, mixed $valor, string $esperado) {
    expect(Mascara::aplicar($tipo, $valor))->toBe($esperado);
})->with([
    'cpf' => ['cpf', '52998224725', '529.982.247-25'],
    'cnpj alfanumérico' => ['cnpj', '12abc34501de35', '12.ABC.345/01DE-35'],
    'cpf-cnpj com CPF' => ['cpf-cnpj', '52998224725', '529.982.247-25'],
    'cpf-cnpj com CNPJ' => ['cpf-cnpj', '11222333000181', '11.222.333/0001-81'],
    'cep' => ['cep', '01310100', '01310-100'],
    'telefone fixo' => ['telefone', '1134567890', '(11) 3456-7890'],
    'celular' => ['telefone', '11987654321', '(11) 98765-4321'],
    'dinheiro float' => ['dinheiro', 1234.5, '1.234,50'],
    'dinheiro decimal do banco' => ['dinheiro', '1234567.89', '1.234.567,89'],
    'dinheiro já formatado (old)' => ['dinheiro', '1.234,56', '1.234,56'],
]);

it('não mexe em valores incompletos ou vazios', function () {
    expect(Mascara::aplicar('cpf', '5299'))->toBe('5299')
        ->and(Mascara::aplicar('cpf', null))->toBeNull()
        ->and(Mascara::aplicar(null, '52998224725'))->toBe('52998224725');
});

it('remove a máscara para salvar', function (string $tipo, string $valor, ?string $esperado) {
    expect(Mascara::limpar($tipo, $valor))->toBe($esperado);
})->with([
    'cpf' => ['cpf', '529.982.247-25', '52998224725'],
    'cnpj mantém as letras em maiúsculas' => ['cnpj', '12.abc.345/01de-35', '12ABC34501DE35'],
    'telefone' => ['telefone', '(11) 98765-4321', '11987654321'],
    'dinheiro' => ['dinheiro', '1.234,56', '1234.56'],
    'dinheiro só centavos' => ['dinheiro', '0,05', '0.05'],
    'dinheiro no formato do banco' => ['dinheiro', '1234.5', '1234.50'],
    'vazio' => ['cpf', '', null],
]);

it('exibe valores para tabelas e páginas', function () {
    expect(Mascara::exibir('dinheiro', 1234.5))->toBe("R$\u{00A0}1.234,50")
        ->and(Mascara::exibir('cpf', null))->toBe('')
        ->and(Mascara::exibir('cep', '01310100'))->toBe('01310-100');
});

it('recusa máscara desconhecida', function () {
    Mascara::atributos('rg');
})->throws(InvalidArgumentException::class);
