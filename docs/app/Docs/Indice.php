<?php

namespace App\Docs;

/**
 * Seções da página atual, registradas pelo <x-docs.secao> enquanto o conteúdo é renderizado.
 * O <x-docs.pagina> lê a lista para montar o "Nesta página" sem JavaScript.
 */
class Indice
{
    /** @var array<int, array{id: string, titulo: string, nivel: int}> */
    private static array $secoes = [];

    public static function registrar(string $id, string $titulo, int $nivel): void
    {
        self::$secoes[] = compact('id', 'titulo', 'nivel');
    }

    /**
     * Devolve as seções e limpa a lista: o build renderiza várias páginas no mesmo processo.
     */
    public static function consumir(): array
    {
        [$secoes, self::$secoes] = [self::$secoes, []];

        return $secoes;
    }
}
