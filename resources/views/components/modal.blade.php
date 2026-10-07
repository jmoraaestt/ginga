@props([
    'id',
    'title' => null,
    'size' => null,
    'centered' => false,
    'scrollable' => false,
    // Não fecha ao clicar fora nem com Esc: para formulários que não podem ser perdidos
    'static' => false,
])

@php
    $classesDialogo = \Illuminate\Support\Arr::toCssClasses([
        'modal-dialog',
        'modal-' . $size => $size,
        'modal-dialog-centered' => $centered,
        'modal-dialog-scrollable' => $scrollable,
    ]);

    // Atributos com valor null ou false não são renderizados
    $defaults = [
        'class' => 'modal fade',
        'id' => $id,
        'tabindex' => '-1',
        'aria-labelledby' => filled($title) ? $id . '-titulo' : null,
        'aria-hidden' => 'true',
        'data-bs-backdrop' => $static ? 'static' : null,
        'data-bs-keyboard' => $static ? 'false' : null,
    ];
@endphp

<div {{ $attributes->merge($defaults) }}>
    <div class="{{ $classesDialogo }}">
        <div class="modal-content">
        @if (filled($title))
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="{{ $id }}-titulo">{{ $title }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
        @endif
            <div class="modal-body">
                {{ $slot }}
            </div>
        @isset($footer)
            <div class="modal-footer">
                {{ $footer }}
            </div>
        @endisset
        </div>
    </div>
</div>
