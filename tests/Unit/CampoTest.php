<?php

use Ginga\Support\Campo;
use Ginga\Tests\Fixtures\Nivel;
use Illuminate\Support\Carbon;

it('converte o name na chave de erros e old()', function () {
    expect(Campo::chave('endereco[cidade]'))->toBe('endereco.cidade')
        ->and(Campo::chave('interesses[]'))->toBe('interesses')
        ->and(Campo::nomeLista('interesses'))->toBe('interesses[]')
        ->and(Campo::nomeLista('interesses[]'))->toBe('interesses[]');
});

it('aceita opções em vários formatos', function (mixed $opcoes, array $esperado) {
    expect(Campo::opcoes($opcoes))->toBe($esperado);
})->with([
    'valor => texto' => [['sp' => 'São Paulo'], ['sp' => 'São Paulo']],
    'lista simples' => [['Manhã', 'Tarde'], ['Manhã' => 'Manhã', 'Tarde' => 'Tarde']],
    'collection' => [collect([3 => 'Três', 7 => 'Sete']), [3 => 'Três', 7 => 'Sete']],
    'enum com label()' => [Nivel::class, ['iniciante' => 'Iniciante', 'avancado' => 'Avançado']],
]);

it('normaliza valores marcados', function () {
    expect(Campo::lista('SP'))->toBe(['SP'])
        ->and(Campo::lista(['SP', null, 3]))->toBe(['SP', '3'])
        ->and(Campo::lista(collect(['RJ'])))->toBe(['RJ'])
        ->and(Campo::lista(Nivel::Avancado))->toBe(['avancado'])
        ->and(Campo::lista(null))->toBe([]);
});

it('formata datas para cada tipo de input', function (string $tipo, string $esperado) {
    expect(Campo::valorInput(Carbon::create(2026, 10, 7, 15, 30), $tipo))->toBe($esperado);
})->with([
    ['date', '2026-10-07'],
    ['datetime-local', '2026-10-07T15:30'],
    ['time', '15:30'],
    ['month', '2026-10'],
]);
