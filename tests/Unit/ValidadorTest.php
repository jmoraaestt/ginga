<?php

use Ginga\Support\Validador;
use Illuminate\Support\Facades\Validator;

describe('CPF', function () {
    it('aceita CPF válido com ou sem máscara', function (string $cpf) {
        expect(Validador::cpf($cpf))->toBeTrue();
    })->with(['529.982.247-25', '52998224725']);

    it('recusa CPF inválido', function (mixed $cpf) {
        expect(Validador::cpf($cpf))->toBeFalse();
    })->with([
        'dígito errado' => '529.982.247-24',
        'todos iguais' => '111.111.111-11',
        'com letra' => '5299822472a',
        'curto' => '529.982.247',
        'número em vez de texto' => 52998224725,
        'nulo' => null,
    ]);
});

describe('CNPJ', function () {
    it('aceita CNPJ numérico e alfanumérico', function (string $cnpj) {
        expect(Validador::cnpj($cnpj))->toBeTrue();
    })->with([
        'numérico' => '11.222.333/0001-81',
        'alfanumérico (exemplo da Receita)' => '12.ABC.345/01DE-35',
        'alfanumérico minúsculo e sem máscara' => '12abc34501de35',
    ]);

    it('recusa CNPJ inválido', function (string $cnpj) {
        expect(Validador::cnpj($cnpj))->toBeFalse();
    })->with([
        'dígito errado' => '11.222.333/0001-82',
        'alfanumérico com dígito errado' => '12.ABC.345/01DE-36',
        'letra no dígito verificador' => '12.ABC.345/01DE-3A',
        'todos iguais' => '00.000.000/0000-00',
    ]);

    it('aceita CPF ou CNPJ', function () {
        expect(Validador::cpfCnpj('529.982.247-25'))->toBeTrue()
            ->and(Validador::cpfCnpj('12.ABC.345/01DE-35'))->toBeTrue()
            ->and(Validador::cpfCnpj('123'))->toBeFalse();
    });
});

describe('CEP, telefone e UF', function () {
    it('valida CEP', function () {
        expect(Validador::cep('01310-100'))->toBeTrue()
            ->and(Validador::cep('01310100'))->toBeTrue()
            ->and(Validador::cep('0131010'))->toBeFalse()
            ->and(Validador::cep('00000-000'))->toBeFalse();
    });

    it('valida telefone fixo e celular', function (string $telefone, bool $valido) {
        expect(Validador::telefone($telefone))->toBe($valido);
    })->with([
        'celular' => ['(11) 98765-4321', true],
        'fixo' => ['(11) 3456-7890', true],
        'sem máscara' => ['11987654321', true],
        'celular sem o 9' => ['(11) 88765-4321', false],
        'DDD com zero' => ['(10) 98765-4321', false],
        'fixo começando com 6' => ['(11) 6456-7890', false],
    ]);

    it('valida UF', function () {
        expect(Validador::uf('SP'))->toBeTrue()
            ->and(Validador::uf('XX'))->toBeFalse()
            ->and(Validador::uf('sp'))->toBeFalse();
    });
});

describe('regras de validação do Laravel', function () {
    it('registra as regras com mensagens em português', function (string $regra, string $invalido, string $mensagem) {
        $validacao = Validator::make(['campo' => $invalido], ['campo' => $regra]);

        expect($validacao->fails())->toBeTrue()
            ->and($validacao->errors()->first('campo'))->toBe($mensagem);
    })->with([
        ['cpf', '111.111.111-11', 'O campo campo deve ser um CPF válido.'],
        ['cnpj', '11.222.333/0001-82', 'O campo campo deve ser um CNPJ válido.'],
        ['cpf_cnpj', '123', 'O campo campo deve ser um CPF ou CNPJ válido.'],
        ['cep', '123', 'O campo campo deve ser um CEP válido.'],
        ['telefone', '123', 'O campo campo deve ser um telefone válido com DDD.'],
        ['uf', 'XX', 'O campo campo deve ser uma UF válida.'],
    ]);

    it('aceita valores válidos com máscara', function () {
        $validacao = Validator::make(
            ['cpf' => '529.982.247-25', 'cnpj' => '12.ABC.345/01DE-35', 'cep' => '01310-100', 'telefone' => '(11) 98765-4321', 'uf' => 'SP'],
            ['cpf' => 'cpf', 'cnpj' => 'cnpj', 'cep' => 'cep', 'telefone' => 'telefone', 'uf' => 'uf'],
        );

        expect($validacao->passes())->toBeTrue();
    });
});
