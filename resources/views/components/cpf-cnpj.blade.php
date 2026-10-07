@props([
    'label' => 'CPF ou CNPJ',
])

<x-ginga::input mask="cpf-cnpj" :label="$label" {{ $attributes }} />
