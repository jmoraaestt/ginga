<?php

namespace Ginga\Support;

use BackedEnum;
use DateTimeInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use UnitEnum;

/**
 * Lógica comum dos campos de formulário: chave de erro, id, old(), opções e mensagens.
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

    /**
     * Listas enviam um array: "interesses" vira "interesses[]".
     */
    public static function nomeLista(?string $name): ?string
    {
        return $name && ! str_ends_with($name, '[]') ? $name . '[]' : $name;
    }

    public static function id(?string $id, ?string $chave): string
    {
        return $id ?? 'campo-' . ($chave ? str_replace('.', '-', $chave) : Str::random(8));
    }

    /**
     * Primeira mensagem de erro do campo. Em listas, também procura erros dos itens ("interesses.0").
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
     * Valores marcados de checkboxes, radios e selects múltiplos.
     */
    public static function marcados(?string $chave, mixed $padrao): array
    {
        // Sem chave, old() devolveria todos os campos enviados
        return self::lista($chave && self::enviado() ? old($chave) : $padrao);
    }

    /**
     * Qualquer valor vira uma lista de strings para comparar com as opções: "SP", ["SP"], Collection, enum.
     */
    public static function lista(mixed $valores): array
    {
        $valores = match (true) {
            $valores instanceof Arrayable => $valores->toArray(),
            is_array($valores) => $valores,
            default => [$valores],
        };

        $valores = array_filter($valores, fn ($valor) => $valor !== null);

        return array_values(array_map(fn ($valor) => (string) self::valor($valor), $valores));
    }

    /**
     * Opções de select, radio-group e checkbox-group. Aceita:
     * - ['sp' => 'São Paulo']: valor => texto
     * - ['Manhã', 'Tarde']: lista simples, o texto também é o valor
     * - Empresa::pluck('nome', 'id'): Collection
     * - Plano::class: enum (usa o método label() do enum, se existir)
     */
    public static function opcoes(mixed $opcoes): array
    {
        if (is_string($opcoes) && enum_exists($opcoes)) {
            $resultado = [];

            foreach ($opcoes::cases() as $caso) {
                $resultado[self::valor($caso)] = method_exists($caso, 'label') ? $caso->label() : $caso->name;
            }

            return $resultado;
        }

        $opcoes = $opcoes instanceof Arrayable ? $opcoes->toArray() : (array) $opcoes;

        return array_is_list($opcoes) ? array_combine($opcoes, $opcoes) : $opcoes;
    }

    /**
     * Enums viram o valor salvo no banco.
     */
    public static function valor(mixed $valor): mixed
    {
        return match (true) {
            $valor instanceof BackedEnum => $valor->value,
            $valor instanceof UnitEnum => $valor->name,
            default => $valor,
        };
    }

    /**
     * Valor no formato que cada tipo de input espera. Datas do Eloquent (Carbon) funcionam direto.
     */
    public static function valorInput(mixed $valor, string $tipo): mixed
    {
        $valor = self::valor($valor);

        if (! $valor instanceof DateTimeInterface) {
            return $valor;
        }

        return $valor->format(match ($tipo) {
            'date' => 'Y-m-d',
            'datetime-local' => 'Y-m-d\TH:i',
            'time' => 'H:i',
            'month' => 'Y-m',
            'week' => 'o-\WW',
            default => 'd/m/Y',
        });
    }

    /**
     * Atributo booleano presente e não desligado: required, disabled, multiple...
     */
    public static function ativo($attributes, string $nome): bool
    {
        return $attributes->has($nome) && $attributes->get($nome) !== false;
    }

    public static function obrigatorio($attributes): bool
    {
        return self::ativo($attributes, 'required');
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
