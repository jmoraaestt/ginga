@props([
    'variant' => 'primary',
    'subtle' => false,
    'pill' => false,
])

@php
    // Mesma função usada por @class e $attributes->class(): inclui a classe quando o valor é verdadeiro
    $classes = \Illuminate\Support\Arr::toCssClasses([
        'badge',
        'text-bg-' . $variant => ! $subtle,
        // Versão suave: mesmas cores do alert
        'bg-' . $variant . '-subtle text-' . $variant . '-emphasis border border-' . $variant . '-subtle' => $subtle,
        'rounded-pill' => $pill,
    ]);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</span>
