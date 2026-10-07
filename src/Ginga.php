<?php

namespace Ginga;

class Ginga
{
    public const BOOTSTRAP = '5.3.3';
    public const BOOTSTRAP_ICONS = '1.11.3';

    public const TEMA = __DIR__ . '/../resources/css/ginga-theme.css';

    public static function bootstrapCss(): string
    {
        return 'https://cdn.jsdelivr.net/npm/bootstrap@' . self::BOOTSTRAP . '/dist/css/bootstrap.min.css';
    }

    public static function bootstrapJs(): string
    {
        return 'https://cdn.jsdelivr.net/npm/bootstrap@' . self::BOOTSTRAP . '/dist/js/bootstrap.bundle.min.js';
    }

    public static function iconesCss(): string
    {
        return 'https://cdn.jsdelivr.net/npm/bootstrap-icons@' . self::BOOTSTRAP_ICONS . '/font/bootstrap-icons.min.css';
    }

    /**
     * URL do tema com a data do arquivo: muda a cada atualização do pacote e o navegador baixa de novo.
     */
    public static function urlTema(): string
    {
        return route('ginga.tema', ['v' => filemtime(self::TEMA)]);
    }
}
