@props([
    'name' => null,
    'label' => null,
    'value' => null,
    'help' => null,
    'rows' => 3,
])

@use('Ginga\Support\Campo')

@php
    $chave = Campo::chave($name);
    $id = Campo::id($attributes->get('id'), $chave);
    $erro = Campo::erro($errors ?? null, $chave);
    $valor = Campo::antigo($chave, $value);
    $obrigatorio = Campo::obrigatorio($attributes);

    // Atributos com valor null ou false não são renderizados
    $defaults = [
        'class' => \Illuminate\Support\Arr::toCssClasses([
            'form-control',
            'is-invalid' => $erro,
        ]),
        'id' => $id,
        'name' => $name,
        'rows' => $rows,
        'aria-invalid' => $erro ? 'true' : null,
        'aria-describedby' => Campo::descricoes($id, $help, $erro),
    ];
@endphp


<div class="mb-3">
@if (filled($label))
    <label class="form-label" for="{{ $id }}">
        {{ $label }}
        @if ($obrigatorio)<span class="text-danger" aria-hidden="true">*</span>@endif
    </label>
@endif
    {{-- Sem espaço entre as tags: tudo dentro do textarea vira conteúdo --}}
    <textarea {{ $attributes->merge($defaults) }}>{{ $valor }}</textarea>
    <div class="invalid-feedback" id="{{ $id }}-erro">{{ $erro }}</div>
@if (filled($help))
    <div class="form-text" id="{{ $id }}-ajuda">{{ $help }}</div>
@endif
</div>
