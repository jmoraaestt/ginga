@props([
    'paginator',
    // "Mostrando 1 a 10 de 57 resultados"
    'summary' => true,
])

@php
    $completo = $paginator instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    $numero = fn ($valor) => number_format($valor, 0, ',', '.');
    $mostrarResumo = $summary && $completo && $paginator->total() > 0;

    // linkCollection() traz anterior, páginas e "..." com os textos em inglês: aqui só as páginas
    $paginas = $completo ? $paginator->onEachSide(1)->linkCollection()->slice(1, -1) : collect();
@endphp

@if ($mostrarResumo || $paginator->hasPages())
<div {{ $attributes->merge(['class' => 'd-flex flex-wrap align-items-center justify-content-between gap-2']) }}>
@if ($mostrarResumo)
    <p class="small text-body-secondary mb-0" data-ginga-paginacao-resumo>
    @if ($paginator->total() === 1)
        1 resultado
    @else
        Mostrando {{ $numero($paginator->firstItem()) }} a {{ $numero($paginator->lastItem()) }} de {{ $numero($paginator->total()) }} resultados
    @endif
    </p>
@endif

@if ($paginator->hasPages())
    <nav aria-label="Paginação" class="ms-auto">
        <ul class="pagination flex-wrap mb-0">
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link">Anterior</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Anterior</a>
            </li>
        @endif

        @foreach ($paginas as $pagina)
            @if ($pagina['url'] === null)
                <li class="page-item disabled" aria-disabled="true"><span class="page-link">…</span></li>
            @elseif ($pagina['active'])
                <li class="page-item active" aria-current="page">
                    <span class="page-link"><span class="visually-hidden">Página </span>{{ $pagina['label'] }}</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $pagina['url'] }}"><span class="visually-hidden">Página </span>{{ $pagina['label'] }}</a>
                </li>
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Próxima</a>
            </li>
        @else
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link">Próxima</span>
            </li>
        @endif
        </ul>
    </nav>
@endif
</div>
@endif
