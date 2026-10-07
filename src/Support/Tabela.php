<?php

namespace Ginga\Support;

use ArrayIterator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use IteratorAggregate;
use Traversable;

/**
 * Busca, ordenação e paginação no servidor, lidas da query string.
 *
 *     $clientes = Tabela::de(Cliente::query())
 *         ->buscarEm(['nome', 'email', 'cpf'])
 *         ->ordenarPor(['nome', 'created_at'], padrao: 'nome')
 *         ->paginar();
 */
class Tabela implements IteratorAggregate
{
    public const BUSCA = 'busca';
    public const ORDENAR = 'ordenar';
    public const DIRECAO = 'direcao';
    public const POR_PAGINA = 'por_pagina';
    public const PAGINA = 'pagina';

    private array $colunasBusca = [];
    private array $colunasOrdenacao = [];
    private ?string $ordemPadrao = null;
    private string $direcaoPadrao = 'asc';
    private int $porPaginaPadrao = 10;

    /** Resultado de paginar() */
    public ?LengthAwarePaginator $linhas = null;

    /** Estado lido da query string (já validado) */
    public string $busca = '';
    public ?string $ordem = null;
    public string $direcao = 'asc';
    public int $porPagina = 10;
    public array $opcoesPorPagina = [10, 25, 50, 100];

    public function __construct(
        private EloquentBuilder|QueryBuilder|Relation $query,
        private Request $request,
    ) {
    }

    public static function de(EloquentBuilder|QueryBuilder|Relation $query, ?Request $request = null): static
    {
        return new static($query, $request ?? request());
    }

    /**
     * Colunas pesquisadas pela busca. Use ponto para relacionamentos: "empresa.nome".
     */
    public function buscarEm(array $colunas): static
    {
        $this->colunasBusca = $colunas;

        return $this;
    }

    /**
     * Colunas que podem ser ordenadas. Qualquer outra coluna na URL é ignorada.
     */
    public function ordenarPor(array $colunas, ?string $padrao = null, string $direcao = 'asc'): static
    {
        $this->colunasOrdenacao = $colunas;
        $this->ordemPadrao = $padrao;
        $this->direcaoPadrao = $direcao === 'desc' ? 'desc' : 'asc';

        return $this;
    }

    public function itensPorPagina(int $padrao, ?array $opcoes = null): static
    {
        $this->opcoesPorPagina = $opcoes ?? $this->opcoesPorPagina;
        $this->porPaginaPadrao = $padrao;

        return $this;
    }

    public function paginar(): static
    {
        $this->lerQueryString();

        $query = clone $this->query;

        if ($this->busca !== '' && $this->colunasBusca) {
            $this->aplicarBusca($query);
        }

        if ($this->ordem) {
            $query->orderBy($this->ordem, $this->direcao);
        }

        // Desempate pela chave primária: sem isso, registros com o mesmo valor mudam de página entre consultas
        if ($query instanceof EloquentBuilder || $query instanceof Relation) {
            $query->orderBy($query->getModel()->getQualifiedKeyName(), $this->direcao);
        }

        $this->linhas = $query
            ->paginate($this->porPagina, ['*'], self::PAGINA)
            ->withQueryString();

        return $this;
    }

    public function ordenavel(string $coluna): bool
    {
        return in_array($coluna, $this->colunasOrdenacao, true);
    }

    /**
     * Link do cabeçalho: ordena pela coluna, invertendo a direção se ela já for a atual. Volta para a página 1.
     */
    public function urlOrdenar(string $coluna): string
    {
        $direcao = $this->ordem === $coluna && $this->direcao === 'asc' ? 'desc' : 'asc';

        return $this->url([self::ORDENAR => $coluna, self::DIRECAO => $direcao, self::PAGINA => null]);
    }

    public function urlSemBusca(): string
    {
        return $this->url([self::BUSCA => null, self::PAGINA => null]);
    }

    public function getIterator(): Traversable
    {
        return $this->linhas?->getIterator() ?? new ArrayIterator();
    }

    private function lerQueryString(): void
    {
        $texto = fn (string $nome) => is_string($valor = $this->request->query($nome)) ? $valor : '';

        $this->busca = trim($texto(self::BUSCA));

        $ordem = $texto(self::ORDENAR);
        $this->ordem = $this->ordenavel($ordem) ? $ordem : $this->ordemPadrao;

        $direcao = strtolower($texto(self::DIRECAO));
        $this->direcao = in_array($direcao, ['asc', 'desc'], true) ? $direcao : $this->direcaoPadrao;

        $porPagina = (int) $texto(self::POR_PAGINA);
        $this->porPagina = in_array($porPagina, $this->opcoesPorPagina, true) ? $porPagina : $this->porPaginaPadrao;
    }

    private function aplicarBusca(EloquentBuilder|QueryBuilder|Relation $query): void
    {
        $termos = [$this->busca];

        // "529.982" também procura "529982": CPF, CNPJ, CEP e telefone costumam ser salvos sem máscara
        if (preg_match('/\d/', $this->busca)) {
            $termos[] = preg_replace('/[.\-\/() ]/', '', $this->busca);
        }

        $termos = array_unique(array_filter($termos, fn ($termo) => $termo !== ''));

        // No PostgreSQL o LIKE diferencia maiúsculas de minúsculas
        $operador = $query->getConnection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';

        $query->where(function ($busca) use ($termos, $operador) {
            foreach ($this->colunasBusca as $coluna) {
                foreach ($termos as $termo) {
                    $padrao = '%' . $termo . '%';

                    if (str_contains($coluna, '.')) {
                        $relacao = substr($coluna, 0, strrpos($coluna, '.'));
                        $campo = substr($coluna, strrpos($coluna, '.') + 1);
                        $busca->orWhereRelation($relacao, $campo, $operador, $padrao);
                    } else {
                        $busca->orWhere($coluna, $operador, $padrao);
                    }
                }
            }
        });
    }

    private function url(array $mudancas): string
    {
        $parametros = array_filter(
            array_merge($this->request->query(), $mudancas),
            fn ($valor) => $valor !== null && $valor !== '',
        );

        return $this->request->url() . ($parametros ? '?' . Arr::query($parametros) : '');
    }
}
