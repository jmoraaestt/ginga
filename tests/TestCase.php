<?php

namespace Ginga\Tests;

use Ginga\GingaServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [GingaServiceProvider::class];
    }

    /**
     * Simula a volta de um formulário com erro: valores antigos no old() e mensagens em $errors.
     */
    protected function comEnvio(array $antigos = [], array $erros = []): static
    {
        $sessao = $this->app['session.store'];
        $this->app['request']->setLaravelSession($sessao);
        $sessao->flashInput($antigos);

        return $this->withViewErrors($erros);
    }
}
