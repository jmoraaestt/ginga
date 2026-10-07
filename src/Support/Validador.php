<?php

namespace Ginga\Support;

/**
 * Validações aceitam o valor com ou sem máscara: "123.456.789-09" e "12345678909".
 */
class Validador
{
    public static function cpf(mixed $valor): bool
    {
        if (! is_string($valor) || ! preg_match('/^\d{3}\.?\d{3}\.?\d{3}-?\d{2}$/', $valor)) {
            return false;
        }

        $cpf = preg_replace('/\D/', '', $valor);

        // 000.000.000-00, 111.111.111-11... passam no cálculo, mas não existem
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($posicao = 9; $posicao < 11; $posicao++) {
            $soma = 0;

            for ($i = 0; $i < $posicao; $i++) {
                $soma += (int) $cpf[$i] * ($posicao + 1 - $i);
            }

            $digito = ((10 * $soma) % 11) % 10;

            if ((int) $cpf[$posicao] !== $digito) {
                return false;
            }
        }

        return true;
    }

    /**
     * Aceita o CNPJ numérico e o alfanumérico (letras nas 12 primeiras posições, a partir de julho de 2026).
     * Cada caractere vale o código ASCII menos 48: os dígitos continuam valendo 0-9 e "A" vale 17.
     */
    public static function cnpj(mixed $valor): bool
    {
        if (! is_string($valor)) {
            return false;
        }

        $valor = strtoupper($valor);

        if (! preg_match('/^[A-Z0-9]{2}\.?[A-Z0-9]{3}\.?[A-Z0-9]{3}\/?[A-Z0-9]{4}-?\d{2}$/', $valor)) {
            return false;
        }

        $cnpj = preg_replace('/[^A-Z0-9]/', '', $valor);

        if (preg_match('/^(.)\1{13}$/', $cnpj)) {
            return false;
        }

        $valores = array_map(fn (string $caractere) => ord($caractere) - 48, str_split($cnpj));

        foreach ([12, 13] as $posicao) {
            $pesos = array_slice([6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2], 13 - $posicao);
            $soma = 0;

            for ($i = 0; $i < $posicao; $i++) {
                $soma += $valores[$i] * $pesos[$i];
            }

            $resto = $soma % 11;
            $digito = $resto < 2 ? 0 : 11 - $resto;

            if ($valores[$posicao] !== $digito) {
                return false;
            }
        }

        return true;
    }

    public static function cpfCnpj(mixed $valor): bool
    {
        return self::cpf($valor) || self::cnpj($valor);
    }

    public static function cep(mixed $valor): bool
    {
        return is_string($valor)
            && preg_match('/^\d{5}-?\d{3}$/', $valor)
            && preg_replace('/\D/', '', $valor) !== '00000000';
    }

    /**
     * Fixo: DDD + 8 dígitos começando em 2-5. Celular: DDD + 9 dígitos começando em 9.
     */
    public static function telefone(mixed $valor): bool
    {
        if (! is_string($valor) || ! preg_match('/^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/', $valor)) {
            return false;
        }

        $telefone = preg_replace('/\D/', '', $valor);

        // DDDs vão de 11 a 99 e não têm zero
        if (str_contains(substr($telefone, 0, 2), '0')) {
            return false;
        }

        return match (strlen($telefone)) {
            10 => in_array($telefone[2], ['2', '3', '4', '5'], true),
            11 => $telefone[2] === '9',
            default => false,
        };
    }

    public static function uf(mixed $valor): bool
    {
        return is_string($valor) && array_key_exists($valor, Brasil::ESTADOS);
    }
}
