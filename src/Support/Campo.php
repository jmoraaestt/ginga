<?php

namespace Ginga\Support;

use Illuminate\Support\Str;

/**
 * Lógica comum dos campos de formulário: chave de erro, id, old() e mensagens.
 */
class Campo
{
    /**
     * "endereco[cidade]" -> "endereco.cidade" e "interesses[]" -> "interesses", a chave usada por $errors e old().
     */
    public static function chave(?string $name): ?string
    {
        return $name ? str_replace(['[]', '[', ']'], ['', '.', ''], $name) : null;
    }

    public static function id(?string $id, ?string $chave): string
    {
        return $id ?? 'campo-' . ($chave ? str_replace('.', '-', $chave) : Str::random(8));
    }

    /**
     * Primeira mensagem de erro do campo. Em grupos, também procura erros dos itens ("interesses.0").
     */
    public static function erro(mixed $errors, ?string $chave, bool $itens = false): ?string
    {
        if (! $chave || ! $errors) {
            return null;
        }

        return $errors->first($chave) ?: ($itens ? $errors->first($chave . '.*') : null) ?: null;
    }

    /**
     * Houve um envio com erro de validação: os valores devem vir do old(), mesmo os ausentes.
     * Checkbox desmarcado não é enviado, então "não está no old()" significa "desmarcado".
     */
    public static function enviado(): bool
    {
        return request()->hasSession() && request()->session()->hasOldInput();
    }

    public static function antigo(?string $chave, mixed $padrao): mixed
    {
        return $chave && request()->hasSession() ? old($chave, $padrao) : $padrao;
    }

    /**
     * Valores marcados de checkboxes e radios, como strings para comparar com as opções.
     */
    public static function marcados(?string $chave, mixed $padrao): array
    {
        // Sem chave, old() devolveria todos os campos enviados
        $valores = $chave && self::enviado() ? old($chave) : $padrao;

        return array_map('strval', array_filter((array) $valores, fn ($valor) => $valor !== null));
    }

    public static function obrigatorio($attributes): bool
    {
        return $attributes->has('required') && $attributes->get('required') !== false;
    }

    /**
     * Ids da ajuda e do erro para o aria-describedby.
     */
    public static function descricoes(string $id, mixed $ajuda, mixed $erro): ?string
    {
        $ids = array_filter([$ajuda ? $id . '-ajuda' : null, $erro ? $id . '-erro' : null]);

        return $ids ? implode(' ', $ids) : null;
    }
}
