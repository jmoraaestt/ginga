@props([
    'name' => null,
    'label' => null,
    'type' => null,
    'value' => null,
    'help' => null,
    'mask' => null,
    'prefix' => null,
    'suffix' => null,
])

@use('Ginga\Support\Campo')
@use('Ginga\Support\Mascara')

@php
    $chave = Campo::chave($name);
    $id = Campo::id($attributes->get('id'), $chave);
    $erro = Campo::erro($errors ?? null, $chave);

    $mascara = Mascara::atributos($mask);
    $tipo = $type ?? $mascara['type'] ?? 'text';

    // Senha nunca volta preenchida
    $valor = $tipo === 'password' ? $value : Campo::antigo($chave, $value);
    $valor = Mascara::aplicar($mask, $valor);

    $obrigatorio = Campo::obrigatorio($attributes);
    $temGrupo = filled($prefix) || filled($suffix);

    // Atributos com valor null ou false não são renderizados
    $defaults = array_merge([
        'class' => \Illuminate\Support\Arr::toCssClasses([
            'form-control',
            'is-invalid' => $erro,
            'text-end' => $mask === 'dinheiro',
        ]),
        'type' => $tipo,
        'id' => $id,
        'name' => $name,
        'value' => $valor,
    ], \Illuminate\Support\Arr::except($mascara, 'type'), [
        'aria-invalid' => $erro ? 'true' : null,
        'aria-describedby' => Campo::descricoes($id, $help, $erro),
        'data-ginga-mascara' => $mask,
    ]);
@endphp


<div class="mb-3">
@if (filled($label))
    <label class="form-label" for="{{ $id }}">
        {{ $label }}
        @if ($obrigatorio)<span class="text-danger" aria-hidden="true">*</span>@endif
    </label>
@endif
@if ($temGrupo)
    {{-- has-validation mantém os cantos arredondados com a mensagem de erro dentro do grupo --}}
    <div class="input-group has-validation">
    @if (filled($prefix))
        <span class="input-group-text">{{ $prefix }}</span>
    @endif
        <input {{ $attributes->merge($defaults) }}>
    @if (filled($suffix))
        <span class="input-group-text">{{ $suffix }}</span>
    @endif
        <div class="invalid-feedback" id="{{ $id }}-erro">{{ $erro }}</div>
    </div>
@else
    <input {{ $attributes->merge($defaults) }}>
    {{-- Sempre presente: a busca de CEP escreve aqui o "CEP não encontrado" --}}
    <div class="invalid-feedback" id="{{ $id }}-erro">{{ $erro }}</div>
@endif
@if (filled($help))
    <div class="form-text" id="{{ $id }}-ajuda">{{ $help }}</div>
@endif
</div>

@if ($mask)
    @include('ginga::partials.mascara')
@endif
