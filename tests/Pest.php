<?php

use Ginga\Tests\TestCase;

uses(TestCase::class)->in('Unit', 'Feature');

/**
 * HTML sem espaços repetidos, para comparar marcação sem depender da indentação dos componentes.
 */
function compactar(string $html): string
{
    return trim(preg_replace('/\s+/', ' ', $html));
}
