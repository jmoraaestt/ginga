<?php

namespace App\Providers;

use App\Docs\Exemplos;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Banco dos exemplos: SQLite em memória, sem arquivo nem migração
        config(['database.connections.' . Exemplos::CONEXAO => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
