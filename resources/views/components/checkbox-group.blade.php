@props([
    'name' => null,
    'label' => null,
    'options' => [],
    'value' => [],
    'help' => null,
    'inline' => false,
    'switch' => false,
    // Só mostra o asterisco: exigir "pelo menos um" é papel da validação no servidor
    'required' => false,
])

@use('Ginga\Support\Campo')

@php
    $chave = Campo::chave($name);
    $id = Campo::id($attributes->get('id'), $chave);
    $erro = Campo::erro($errors ?? null, $chave, itens: true);
    $marcados = Campo::marcados($chave, $value);
    $nome = Campo::nomeLista($name);

    // class e id vão para o fieldset; os demais atributos (wire:model, disabled...) vão para cada checkbox
    $atributosItem = $attributes->except(['class', 'id']);
@endphp


<fieldset {{ $attributes->only('class')->merge([
    'class' => 'mb-3',
    'id' => $id,
    'aria-describedby' => Campo::descricoes($id, $help, $erro),
]) }}>
@if (filled($label))
    <legend class="form-label fs-6">
        {{ $label }}
        @if ($required)<span class="text-danger" aria-hidden="true">*</span>@endif
    </legend>
@endif
@foreach (Campo::opcoes($options) as $opcao => $texto)
    <div @class(['form-check', 'form-check-inline' => $inline, 'form-switch' => $switch])>
        <input {{ $atributosItem->merge([
            'class' => \Illuminate\Support\Arr::toCssClasses(['form-check-input', 'is-invalid' => $erro]),
            'type' => 'checkbox',
            'role' => $switch ? 'switch' : null,
            'id' => $id . '-' . $loop->index,
            'name' => $nome,
            'value' => $opcao,
            'checked' => in_array((string) $opcao, $marcados, true),
            'aria-invalid' => $erro ? 'true' : null,
        ]) }}>
        <label class="form-check-label" for="{{ $id }}-{{ $loop->index }}">{{ $texto }}</label>
    </div>
@endforeach
@if ($erro)
    {{-- d-block: a mensagem fica fora do .form-check, então o Bootstrap não a mostra sozinho --}}
    <div class="invalid-feedback d-block" id="{{ $id }}-erro">{{ $erro }}</div>
@endif
@if (filled($help))
    <div class="form-text" id="{{ $id }}-ajuda">{{ $help }}</div>
@endif
</fieldset>
