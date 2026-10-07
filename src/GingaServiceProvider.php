<?php

namespace Ginga;

use Ginga\Support\Validador;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory as Validacao;

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

    // Diretiva => máscara. Ex.: @cpf($cliente->cpf), @dinheiro($pedido->total)
    private const DIRETIVAS = [
        'cpf' => 'cpf',
        'cnpj' => 'cnpj',
        'cpfCnpj' => 'cpf-cnpj',
        'cep' => 'cep',
        'telefone' => 'telefone',
        'dinheiro' => 'dinheiro',
    ];

    public function boot(): void
    {
        Blade::anonymousComponentPath(__DIR__ . '/../resources/views/components', 'ginga');

        // Views internas, como os scripts das máscaras e da exclusão: ginga::partials.mascara
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'ginga');

        // Rota do tema usada pelo <x-ginga::styles>
        $this->loadRoutesFrom(__DIR__ . '/../routes/ginga.php');

        // Opcional: copiar o tema para public/ e servir como arquivo estático
        $this->publishes([
            Ginga::TEMA => public_path('vendor/ginga/ginga-theme.css'),
        ], 'ginga-assets');

        foreach (self::DIRETIVAS as $diretiva => $mascara) {
            Blade::directive($diretiva, fn ($valor) => "<?php echo e(\\Ginga\\Support\\Mascara::exibir('{$mascara}', {$valor})); ?>");
        }

        // Só registra as regras quando a validação é usada de fato.
        // A mensagem vale quando a aplicação não define validation.{regra} nos arquivos de tradução
        $this->callAfterResolving('validator', function (Validacao $validacao) {
            foreach (self::REGRAS as $regra => [$validar, $mensagem]) {
                $validacao->extend($regra, fn ($atributo, $valor) => $validar($valor), $mensagem);
            }
        });
    }
}
