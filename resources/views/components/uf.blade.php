@props([
    'label' => 'Estado',
    'placeholder' => 'Selecione',
    // 'nome' mostra "São Paulo", 'sigla' mostra "SP". O valor enviado é sempre a sigla
    'formato' => 'nome',
])

@php
    $estados = \Ginga\Support\Brasil::ESTADOS;
    $opcoes = $formato === 'sigla' ? array_combine(array_keys($estados), array_keys($estados)) : $estados;
@endphp

<x-ginga::select :label="$label" :placeholder="$placeholder" :options="$opcoes" {{ $attributes }} />
