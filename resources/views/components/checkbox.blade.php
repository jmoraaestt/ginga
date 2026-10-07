@props([
    'name' => null,
    'label' => null,
    'value' => '1',
    'checked' => false,
    'help' => null,
    'switch' => false,
    // Enviado quando o checkbox está desmarcado. null desliga
    'uncheckedValue' => '0',
])

@use('Ginga\Support\Campo')

@php
    $chave = Campo::chave($name);
    $id = Campo::id($attributes->get('id'), $chave);
    $erro = Campo::erro($errors ?? null, $chave);
    $marcado = in_array((string) $value, Campo::marcados($chave, $checked ? $value : null), true);
    $obrigatorio = Campo::obrigatorio($attributes);

    // Checkbox desmarcado não é enviado. O hidden manda "0" para o servidor saber que foi desmarcado.
    // Não vale para listas (name="itens[]") nem para campos desabilitados, que não devem mudar o valor salvo
    $desabilitado = Campo::ativo($attributes, 'disabled');
    $comOculto = $name && $uncheckedValue !== null && ! str_ends_with($name, '[]') && ! $desabilitado;

    // Atributos com valor null ou false não são renderizados
    $defaults = [
        'class' => \Illuminate\Support\Arr::toCssClasses([
            'form-check-input',
            'is-invalid' => $erro,
        ]),
        'type' => 'checkbox',
        'role' => $switch ? 'switch' : null,
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'checked' => $marcado,
        'aria-invalid' => $erro ? 'true' : null,
        'aria-describedby' => Campo::descricoes($id, $help, $erro),
    ];
@endphp


<div @class(['form-check mb-3', 'form-switch' => $switch])>
@if ($comOculto)
    <input type="hidden" name="{{ $name }}" value="{{ $uncheckedValue }}">
@endif
    <input {{ $attributes->merge($defaults) }}>
    <label class="form-check-label" for="{{ $id }}">
        {{ filled($label) ? $label : $slot }}
        @if ($obrigatorio)<span class="text-danger" aria-hidden="true">*</span>@endif
    </label>
    <div class="invalid-feedback" id="{{ $id }}-erro">{{ $erro }}</div>
@if (filled($help))
    <div class="form-text" id="{{ $id }}-ajuda">{{ $help }}</div>
@endif
</div>
