@props([
    'position' => 'bottom-end',
])

@php
    $positions = [
        'top-start' => 'top-0 start-0',
        'top-center' => 'top-0 start-50 translate-middle-x',
        'top-end' => 'top-0 end-0',
        'bottom-start' => 'bottom-0 start-0',
        'bottom-center' => 'bottom-0 start-50 translate-middle-x',
        'bottom-end' => 'bottom-0 end-0',
    ];

    $classes = \Illuminate\Support\Arr::toCssClasses([
        'toast-container position-fixed p-3',
        $positions[$position] ?? $positions['bottom-end'],
    ]);
@endphp


<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
