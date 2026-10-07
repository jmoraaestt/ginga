<?php

namespace Ginga\Support;

use InvalidArgumentException;

class Mascara
{
    private const MOLDES = [
        'cpf' => '000.000.000-00',
        'cnpj' => '00.000.000/0000-00',
        'cep' => '00000-000',
        'fixo' => '(00) 0000-0000',
        'celular' => '(00) 00000-0000',
    ];

    /**
     * Atributos HTML de cada máscara (teclado no celular, tamanho máximo, exemplo).
     */
    public static function atributos(?string $tipo): array
    {
        return match ($tipo) {
            null => [],
            'cpf' => ['inputmode' => 'numeric', 'maxlength' => 14, 'placeholder' => '000.000.000-00'],
            // Desde julho de 2026 o CNPJ pode ter letras, então o teclado não pode ser só numérico
            'cnpj' => ['maxlength' => 18, 'placeholder' => '00.000.000/0000-00', 'autocapitalize' => 'characters'],
            'cpf-cnpj' => ['maxlength' => 18, 'autocapitalize' => 'characters'],
            'cep' => ['inputmode' => 'numeric', 'maxlength' => 9, 'placeholder' => '00000-000', 'autocomplete' => 'postal-code'],
            'telefone' => ['type' => 'tel', 'maxlength' => 15, 'placeholder' => '(00) 00000-0000', 'autocomplete' => 'tel-national'],
            'dinheiro' => ['inputmode' => 'numeric', 'placeholder' => '0,00'],
            default => throw new InvalidArgumentException("Máscara desconhecida: {$tipo}"),
        };
    }

    /**
     * Formata um valor vindo do banco ou do old(). Valores incompletos voltam como estão.
     */
    public static function aplicar(?string $tipo, mixed $valor): mixed
    {
        if ($tipo === null || $valor === null || $valor === '') {
            return $valor;
        }

        $limpo = self::limpar($tipo, $valor);

        return match ($tipo) {
            'cpf' => strlen($limpo) === 11 ? self::moldar($limpo, self::MOLDES['cpf']) : $valor,
            'cnpj' => strlen($limpo) === 14 ? self::moldar($limpo, self::MOLDES['cnpj']) : $valor,
            'cpf-cnpj' => match (strlen($limpo)) {
                11 => ctype_digit($limpo) ? self::moldar($limpo, self::MOLDES['cpf']) : $valor,
                14 => self::moldar($limpo, self::MOLDES['cnpj']),
                default => $valor,
            },
            'cep' => strlen($limpo) === 8 ? self::moldar($limpo, self::MOLDES['cep']) : $valor,
            'telefone' => match (strlen($limpo)) {
                10 => self::moldar($limpo, self::MOLDES['fixo']),
                11 => self::moldar($limpo, self::MOLDES['celular']),
                default => $valor,
            },
            'dinheiro' => $limpo === null ? $valor : number_format((float) $limpo, 2, ',', '.'),
            default => throw new InvalidArgumentException("Máscara desconhecida: {$tipo}"),
        };
    }

    /**
     * Remove a máscara para salvar no banco.
     * Documentos e telefone viram só dígitos (o CNPJ mantém as letras, em maiúsculas).
     * Dinheiro vira decimal com ponto: "1.234,56" -> "1234.56".
     */
    public static function limpar(string $tipo, mixed $valor): ?string
    {
        if ($valor === null || $valor === '') {
            return null;
        }

        $valor = (string) $valor;

        return match ($tipo) {
            'cpf', 'cep', 'telefone' => preg_replace('/\D/', '', $valor),
            'cnpj', 'cpf-cnpj' => preg_replace('/[^A-Z0-9]/', '', strtoupper($valor)),
            'dinheiro' => self::limparDinheiro($valor),
            default => throw new InvalidArgumentException("Máscara desconhecida: {$tipo}"),
        };
    }

    private static function limparDinheiro(string $valor): ?string
    {
        // Já está no formato do banco: "1234.5", "1234.56", "1234"
        if (preg_match('/^\d+(\.\d+)?$/', $valor)) {
            return number_format((float) $valor, 2, '.', '');
        }

        // Formato do campo: os dois últimos dígitos são os centavos
        $digitos = preg_replace('/\D/', '', $valor);

        if ($digitos === '') {
            return null;
        }

        $digitos = str_pad($digitos, 3, '0', STR_PAD_LEFT);
        $inteiro = ltrim(substr($digitos, 0, -2), '0') ?: '0';

        return $inteiro . '.' . substr($digitos, -2);
    }

    /**
     * Encaixa os caracteres no molde: cada "0" recebe um caractere, o resto é separador.
     */
    private static function moldar(string $valor, string $molde): string
    {
        $resultado = '';
        $i = 0;

        foreach (str_split($molde) as $caractere) {
            if ($i >= strlen($valor)) {
                break;
            }

            $resultado .= $caractere === '0' ? $valor[$i++] : $caractere;
        }

        return $resultado;
    }
}
