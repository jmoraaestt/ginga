@props([
    'variant' => 'primary',
    'size' => null,
])

@php
    $classes = 'btn btn-' . $variant;

    if ($size) {
        $classes .= ' btn-' . $size;
    }
@endphp


<button {{ $attributes->merge(['class' => $classes, 'type' => 'button']) }}>
    {{ $slot }}
</button>