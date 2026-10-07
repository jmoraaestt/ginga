{{--
    level: nível do título no documento, 2 para h2, 3 para h3. O tamanho visual é sempre o mesmo
    flush: conteúdo sem o card-body, encostado nas bordas, para tabelas e list-groups
--}}
@props([
    'title' => null,
    'level' => 2,
    'flush' => false,
])

@php
    $temCabecalho = isset($header) || filled($title) || isset($actions);
@endphp

<div {{ $attributes->merge(['class' => 'card']) }}>
@if ($temCabecalho)
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
    @isset($header)
        {{ $header }}
    @else
        <h{{ $level }} class="card-title h6 mb-0">{{ $title }}</h{{ $level }}>
    @endisset
    @isset($actions)
        <div class="d-flex flex-wrap gap-2">{{ $actions }}</div>
    @endisset
    </div>
@endif
@if ($flush)
    {{ $slot }}
@else
    <div class="card-body">
        {{ $slot }}
    </div>
@endif
@isset($footer)
    <div class="card-footer">{{ $footer }}</div>
@endisset
</div>
