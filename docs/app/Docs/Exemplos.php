<?php

namespace App\Docs;

use Ginga\Support\Tabela;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

/**
 * Dados fictícios usados pelos exemplos. Ficam num SQLite em memória, recriado a cada requisição,
 * para a datatable rodar busca, ordenação e paginação de verdade sem banco configurado.
 */
class Exemplos
{
    public const CONEXAO = 'exemplos';

    private const NOMES = ['Ana', 'Bruno', 'Carla', 'Diego', 'Eduarda', 'Felipe', 'Gabriela', 'Heitor', 'Isabela', 'João', 'Larissa', 'Marcos', 'Natália', 'Otávio', 'Paula', 'Rafael', 'Sabrina', 'Thiago', 'Vitória'];
    private const SOBRENOMES = ['Silva', 'Souza', 'Oliveira', 'Santos', 'Lima', 'Pereira', 'Costa', 'Almeida'];
    private const CIDADES = [['São Paulo', 'SP'], ['Rio de Janeiro', 'RJ'], ['Belo Horizonte', 'MG'], ['Salvador', 'BA'], ['Recife', 'PE'], ['Curitiba', 'PR'], ['Porto Alegre', 'RS'], ['Fortaleza', 'CE']];

    private static bool $criado = false;

    public static function clientes(): Builder
    {
        self::criar();

        return DB::connection(self::CONEXAO)->table('clientes');
    }

    public static function tabelaClientes(): Tabela
    {
        return Tabela::de(self::clientes())
            ->buscarEm(['nome', 'cpf', 'cidade'])
            ->ordenarPor(['nome', 'cidade', 'mensalidade', 'desde'], padrao: 'nome')
            ->itensPorPagina(5, [5, 10, 25])
            ->paginar();
    }

    /**
     * Paginator avulso para o exemplo do componente pagination.
     */
    public static function paginator(int $total = 57, int $pagina = 2, int $porPagina = 10): LengthAwarePaginator
    {
        return new LengthAwarePaginator(range(1, $porPagina), $total, $porPagina, $pagina, ['path' => '#', 'pageName' => 'pagina']);
    }

    /**
     * Simula um envio com erro: soma as mensagens ao $errors compartilhado com as views.
     * Os exemplos usam nomes de campo próprios, então o erro não vaza para outros exemplos.
     */
    public static function erros(array $mensagens, array $antigos = []): void
    {
        $atual = View::shared('errors') ?? new ViewErrorBag();
        $bag = $atual->getBag('default');
        $bag->merge(new MessageBag(array_map(fn ($mensagem) => (array) $mensagem, $mensagens)));
        View::share('errors', $atual->put('default', $bag));

        // now(): vale só para esta requisição, para o valor não aparecer na próxima página aberta
        if ($antigos && request()->hasSession()) {
            request()->session()->now('_old_input', array_merge(request()->session()->getOldInput(), $antigos));
        }
    }

    private static function criar(): void
    {
        if (self::$criado) {
            return;
        }

        $schema = DB::connection(self::CONEXAO)->getSchemaBuilder();
        $schema->create('clientes', function ($tabela) {
            $tabela->id();
            $tabela->string('nome');
            $tabela->string('cpf', 11);
            $tabela->string('cidade');
            $tabela->string('uf', 2);
            $tabela->decimal('mensalidade', 10, 2);
            $tabela->boolean('ativo');
            $tabela->date('desde');
        });

        // Combinações fixas: o resultado é o mesmo em todo carregamento e em todo build
        $linhas = [];
        foreach (self::NOMES as $i => $nome) {
            foreach ([0, 3, 5] as $j => $deslocamento) {
                $sobrenome = self::SOBRENOMES[($i + $deslocamento) % count(self::SOBRENOMES)];
                [$cidade, $uf] = self::CIDADES[($i * 3 + $j) % count(self::CIDADES)];
                $n = count($linhas) + 1;

                $linhas[] = [
                    'nome' => "{$nome} {$sobrenome}",
                    'cpf' => self::cpf(100000000 + $n * 7654321 % 899999999),
                    'cidade' => $cidade,
                    'uf' => $uf,
                    'mensalidade' => 89.9 + ($n * 37 % 12) * 15,
                    'ativo' => $n % 5 !== 0,
                    'desde' => date('Y-m-d', mktime(0, 0, 0, 1 + $n % 12, 1 + $n % 28, 2019 + $n % 7)),
                ];
            }
        }

        DB::connection(self::CONEXAO)->table('clientes')->insert($linhas);
        self::$criado = true;
    }

    /**
     * CPF válido (com os dígitos verificadores certos) a partir de 9 dígitos.
     */
    private static function cpf(int $base): string
    {
        $digitos = str_pad((string) $base, 9, '0', STR_PAD_LEFT);

        foreach ([10, 11] as $peso) {
            $soma = 0;
            for ($i = 0; $i < $peso - 1; $i++) {
                $soma += (int) $digitos[$i] * ($peso - $i);
            }
            $resto = $soma % 11;
            $digitos .= $resto < 2 ? '0' : (string) (11 - $resto);
        }

        return $digitos;
    }
}
