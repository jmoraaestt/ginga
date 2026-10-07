@props([
    'label' => 'CNPJ',
])

<x-ginga::input mask="cnpj" :label="$label" {{ $attributes }} />
