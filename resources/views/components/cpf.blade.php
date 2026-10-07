@props([
    'label' => 'CPF',
])

<x-ginga::input mask="cpf" :label="$label" {{ $attributes }} />
