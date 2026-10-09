<?php

use Illuminate\Support\Facades\Route;

// Tema servido direto do pacote: sempre atualizado, sem vendor:publish.
// O <x-ginga::styles> adiciona ?v=data-do-arquivo, então o cache longo é seguro
Route::get('_ginga/tema.css', fn () => response()->file(dirname(__DIR__) . '/resources/css/ginga-theme.css', [
    'Content-Type' => 'text/css; charset=UTF-8',
    'Cache-Control' => 'public, max-age=31536000, immutable',
]))->name('ginga.tema');
