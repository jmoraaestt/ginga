<?php

namespace Ginga;

use Ginga\Support\Validador;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class GingaServiceProvider extends ServiceProvider
{
    private const REGRAS = [
        'cpf' => [[Validador::class, 'cpf'], 'O campo :attribute deve ser um CPF válido.'],
        'cnpj' => [[Validador::class, 'cnpj'], 'O campo :attribute deve ser um CNPJ válido.'],
        'cpf_cnpj' => [[Validador::class, 'cpfCnpj'], 'O campo :attribute deve ser um CPF ou CNPJ válido.'],
        'cep' => [[Validador::class, 'cep'], 'O campo :attribute deve ser um CEP válido.'],
        'telefone' => [[Validador::class, 'telefone'], 'O campo :attribute deve ser um telefone válido com DDD.'],
        'uf' => [[Validador::class, 'uf'], 'O campo :attribute deve ser uma UF válida.'],
    ];

    public function boot(): void{
        Blade::anonymousComponentPath(__DIR__ . '/../resources/views/components', 'ginga');

        // Views internas, como o script das máscaras: ginga::partials.mascara
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'ginga');

        $this->publishes([
    __DIR__ . '/../resources/css/ginga-theme.css' => public_path('vendor/ginga/ginga-theme.css'),
    ], 'ginga-assets');

        // A mensagem é usada quando a aplicação não define validation.{regra} nos arquivos de tradução
        foreach (self::REGRAS as $regra => [$validar, $mensagem]) {
            Validator::extend($regra, fn ($atributo, $valor) => $validar($valor), $mensagem);
        }
    }
}
