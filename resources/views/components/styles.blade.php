{{--
    No <head>: Bootstrap, tema do Ginga e, opcionalmente, Bootstrap Icons.
    bootstrap: false quando a aplicação já carrega o Bootstrap (Vite, por exemplo)
--}}
@props([
    'bootstrap' => true,
    'icons' => false,
])

@use('Ginga\Ginga')

@if ($bootstrap)
<link rel="stylesheet" href="{{ Ginga::bootstrapCss() }}">
@endif
@if ($icons)
<link rel="stylesheet" href="{{ Ginga::iconesCss() }}">
@endif
{{-- Sempre depois do Bootstrap, para as regras do tema terem prioridade --}}
<link rel="stylesheet" href="{{ Ginga::urlTema() }}">
