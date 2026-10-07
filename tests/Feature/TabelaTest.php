<?php

use Ginga\Support\Tabela;
use Ginga\Tests\Fixtures\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Schema::create('pessoas', function ($tabela) {
        $tabela->id();
        $tabela->string('nome');
        $tabela->string('cpf', 11);
        $tabela->integer('idade');
    });

    Pessoa::insert([
        ['nome' => 'Maria da Ginga', 'cpf' => '52998224725', 'idade' => 30],
        ['nome' => 'João Capoeira', 'cpf' => '11144477735', 'idade' => 25],
        ['nome' => 'Ana Samba', 'cpf' => '22233344405', 'idade' => 40],
    ]);
});

function tabela(array $query = []): Tabela
{
    return Tabela::de(Pessoa::query(), Request::create('/pessoas', 'GET', $query))
        ->buscarEm(['nome', 'cpf'])
        ->ordenarPor(['nome', 'idade'], padrao: 'nome')
        ->itensPorPagina(2, [2, 10])
        ->paginar();
}

function nomes(Tabela $tabela): array
{
    return collect($tabela)->pluck('nome')->all();
}

it('ordena pelo padrão e pagina', function () {
    $tabela = tabela();

    expect(nomes($tabela))->toBe(['Ana Samba', 'João Capoeira'])
        ->and($tabela->linhas->total())->toBe(3)
        ->and($tabela->porPagina)->toBe(2);
});

it('ordena pela coluna e direção da URL', function () {
    expect(nomes(tabela(['ordenar' => 'idade', 'direcao' => 'desc'])))->toBe(['Ana Samba', 'Maria da Ginga']);
});

it('busca por nome', function () {
    expect(nomes(tabela(['busca' => 'ginga'])))->toBe(['Maria da Ginga']);
});

it('busca CPF digitado com máscara', function () {
    expect(nomes(tabela(['busca' => '529.982.247'])))->toBe(['Maria da Ginga']);
});

it('ignora parâmetros inválidos na URL', function () {
    $tabela = tabela(['ordenar' => 'senha', 'direcao' => 'xx', 'por_pagina' => '9999', 'busca' => ['a']]);

    expect($tabela->ordem)->toBe('nome')
        ->and($tabela->direcao)->toBe('asc')
        ->and($tabela->porPagina)->toBe(2)
        ->and($tabela->busca)->toBe('');
});

it('monta os links de ordenação e de limpar busca', function () {
    $tabela = tabela(['busca' => 'ana', 'pagina' => '2']);

    expect($tabela->urlOrdenar('nome'))->toBe('http://localhost/pessoas?busca=ana&ordenar=nome&direcao=desc')
        ->and($tabela->urlOrdenar('idade'))->toBe('http://localhost/pessoas?busca=ana&ordenar=idade&direcao=asc')
        ->and($tabela->urlSemBusca())->toBe('http://localhost/pessoas');
});

it('mantém a busca nos links da paginação', function () {
    expect(tabela(['busca' => 'a'])->linhas->url(2))->toContain('busca=a')->toContain('pagina=2');
});
