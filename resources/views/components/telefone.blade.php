@props([
    'label' => 'Telefone',
])

<x-ginga::input mask="telefone" :label="$label" {{ $attributes }} />
