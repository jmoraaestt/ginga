@props([
    'label' => 'Valor',
    'prefix' => 'R$',
])

<x-ginga::input mask="dinheiro" :label="$label" :prefix="$prefix" {{ $attributes }} />
