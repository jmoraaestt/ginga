<?php

use App\Docs\Navegacao;
use Illuminate\Support\Facades\Route;

Route::view('/', 'docs.inicio')->name('docs.inicio');

// Uma rota para todas as páginas: o slug precisa existir na Navegacao
Route::get('/{slug}', function (string $slug) {
    $pagina = Navegacao::pagina($slug) ?? abort(404);

    return view($pagina->view(), ['pagina' => $pagina]);
})->where('slug', Navegacao::paginas()->pluck('slug')->implode('|'))->name('docs.pagina');
