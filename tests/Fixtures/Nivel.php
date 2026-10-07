<?php

namespace Ginga\Tests\Fixtures;

enum Nivel: string
{
    case Iniciante = 'iniciante';
    case Avancado = 'avancado';

    public function label(): string
    {
        return match ($this) {
            self::Iniciante => 'Iniciante',
            self::Avancado => 'Avançado',
        };
    }
}
