@props([
    'variant' => 'primary',
    'title' => null,
    'dismissible' => false,
])

@php
    $hasIcon = isset($icon);

    // Mesma função usada por @class e $attributes->class(): inclui a classe quando o valor é verdadeiro
    $classes = \Illuminate\Support\Arr::toCssClasses([
        'alert',
        'alert-' . $variant,
        'alert-dismissible fade show' => $dismissible,
        'd-flex align-items-start gap-2' => $hasIcon,
    ]);

    // role="alert" é anunciado na hora pelo leitor de tela; para avisos não urgentes, passe role="status"
    $defaults = [
        'class' => $classes,
        'role' => 'alert',
    ];
@endphp


<div {{ $attributes->merge($defaults) }}>
@if ($hasIcon)
    {{ $icon }}
    <div class="flex-grow-1">
@endif
@if (filled($title))
    <div class="alert-heading fw-semibold mb-1">{{ $title }}</div>
@endif
    {{ $slot }}
@if ($hasIcon)
    </div>
@endif
@if ($dismissible)
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
@endif
</div>
