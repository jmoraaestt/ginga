{{-- Destaque no texto, feito com o próprio <x-ginga::alert> --}}
@props([
    'tipo' => 'info',
    'titulo' => null,
])

@php
    [$variante, $icone] = match ($tipo) {
        'dica' => ['success', 'bi-lightbulb'],
        'aviso' => ['warning', 'bi-exclamation-triangle'],
        'perigo' => ['danger', 'bi-x-octagon'],
        'a11y' => ['primary', 'bi-universal-access'],
        'js' => ['secondary', 'bi-filetype-js'],
        default => ['primary', 'bi-info-circle'],
    };
@endphp

{{-- role="note": é conteúdo da página, não um aviso que deva interromper o leitor de tela --}}
<x-ginga::alert :variant="$variante" :title="$titulo" role="note" {{ $attributes->merge(['class' => 'docs-nota']) }}>
    <x-slot:icon><i class="bi {{ $icone }}" aria-hidden="true"></i></x-slot:icon>
    {{ $slot }}
</x-ginga::alert>
