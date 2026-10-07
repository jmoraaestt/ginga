@props([
    'name' => null,
    'label' => null,
    'options' => [],
    'value' => null,
    'placeholder' => null,
    'help' => null,
])

@use('Ginga\Support\Campo')

@php
    $chave = Campo::chave($name);
    $id = Campo::id($attributes->get('id'), $chave);
    $erro = Campo::erro($errors ?? null, $chave);
    $selecionado = Campo::antigo($chave, $value);
    $obrigatorio = Campo::obrigatorio($attributes);

    // Atributos com valor null ou false não são renderizados
    $defaults = [
        'class' => \Illuminate\Support\Arr::toCssClasses([
            'form-select',
            'is-invalid' => $erro,
        ]),
        'id' => $id,
        'name' => $name,
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
    <select {{ $attributes->merge($defaults) }}>
    @if ($placeholder !== null)
        <option value="">{{ $placeholder }}</option>
    @endif
    @foreach ($options as $opcao => $texto)
        <option value="{{ $opcao }}" @selected((string) $opcao === (string) $selecionado)>{{ $texto }}</option>
    @endforeach
        {{ $slot }}
    </select>
    <div class="invalid-feedback" id="{{ $id }}-erro">{{ $erro }}</div>
@if (filled($help))
    <div class="form-text" id="{{ $id }}-ajuda">{{ $help }}</div>
@endif
</div>
