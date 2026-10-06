@props([
    'variant' => 'primary',
    'size' => null,
    'href' => null,
    'pill' => false,
    'block' => false,
    'loading' => false,
    'disabled' => false,
])

@php
    $isLink = filled($href);
    $isDisabled = $disabled || $loading;
    $tag = $isLink ? 'a' : 'button';

    // Mesma função usada por @class e $attributes->class(): inclui a classe quando o valor é verdadeiro
    $classes = \Illuminate\Support\Arr::toCssClasses([
        'btn',
        'btn-' . $variant,
        'btn-' . $size => $size,
        'rounded-pill' => $pill,
        'w-100' => $block,
        'disabled' => $isLink && $isDisabled,
    ]);

    // Atributos com valor null ou false não são renderizados
    $defaults = $isLink
        ? [
            'class' => $classes,
            'href' => $href,
            'role' => 'button',
            'aria-disabled' => $isDisabled ? 'true' : null,
            'tabindex' => $isDisabled ? '-1' : null,
            'aria-busy' => $loading ? 'true' : null,
        ]
        : [
            'class' => $classes,
            'type' => 'button',
            'disabled' => $isDisabled,
            'aria-busy' => $loading ? 'true' : null,
        ];
@endphp


<{{ $tag }} {{ $attributes->merge($defaults) }}>
@if ($loading)
    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
    <span class="visually-hidden" role="status">Carregando...</span>
@elseif (isset($iconLeft))
    {{ $iconLeft }}
@endif
    {{ $slot }}
@isset($iconRight)
    {{ $iconRight }}
@endisset
</{{ $tag }}>