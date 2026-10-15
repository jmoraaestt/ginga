<?php

namespace App\Docs;

use Illuminate\Support\HtmlString;
use Tempest\Highlight\Highlighter;

/**
 * Prepara trechos de código para exibição: tira a indentação comum, esconde as partes
 * marcadas como ocultas e colore a sintaxe no servidor (funciona no site estático sem JavaScript).
 */
class Codigo
{
    // Linguagens que o tempest/highlight não conhece saem sem cor
    private const SEM_COR = ['bash', 'text'];

    /**
     * Código de um exemplo. Trechos entre {{-- oculto --}} e {{-- /oculto --}} são executados,
     * mas não aparecem no código mostrado (dados de exemplo, por exemplo).
     */
    public static function deArquivo(string $caminho): string
    {
        $codigo = file_get_contents($caminho);
        $codigo = preg_replace('/\{\{-- oculto --\}\}.*?\{\{-- \/oculto --\}\}\R?/s', '', $codigo);

        return self::normalizar($codigo);
    }

    /**
     * O Blade apara o começo do slot: a primeira linha chega sem recuo e as outras com.
     * Com $deSlot, o recuo comum é medido sem a primeira linha.
     */
    public static function normalizar(string $codigo, bool $deSlot = false): string
    {
        $linhas = explode("\n", str_replace("\r\n", "\n", $codigo));

        // Remove linhas vazias do começo e do fim
        while ($linhas && trim(reset($linhas)) === '') {
            array_shift($linhas);
        }
        while ($linhas && trim(end($linhas)) === '') {
            array_pop($linhas);
        }

        $recuo = collect($deSlot ? array_slice($linhas, 1) : $linhas)
            ->filter(fn ($linha) => trim($linha) !== '')
            ->map(fn ($linha) => strlen($linha) - strlen(ltrim($linha, ' ')))
            ->min() ?? 0;

        return implode("\n", array_map(
            fn ($linha, $i) => $deSlot && $i === 0 ? ltrim($linha) : substr($linha, min($recuo, strlen($linha) - strlen(ltrim($linha, ' ')))),
            $linhas,
            array_keys($linhas),
        ));
    }

    public static function colorir(string $codigo, string $linguagem): HtmlString
    {
        if (in_array($linguagem, self::SEM_COR, true)) {
            return new HtmlString(e($codigo));
        }

        return new HtmlString((new Highlighter())->parse($codigo, $linguagem));
    }
}
