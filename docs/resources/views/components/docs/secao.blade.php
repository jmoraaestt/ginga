{{-- Título de seção com âncora. Também entra no "Nesta página" --}}
@props([
    'titulo',
    'id' => null,
    'nivel' => 2,
])

@php
    $id ??= \Illuminate\Support\Str::slug($titulo);
    \App\Docs\Indice::registrar($id, $titulo, $nivel);
@endphp

<h{{ $nivel }} id="{{ $id }}" {{ $attributes->merge(['class' => 'docs-titulo']) }}>
    {{ $titulo }}
    <a href="#{{ $id }}" class="docs-ancora" aria-label="Link para a seção {{ $titulo }}"><i class="bi bi-link-45deg" aria-hidden="true"></i></a>
</h{{ $nivel }}>
