@props([
    // Títulos das colunas. Para cabeçalhos mais elaborados, use o slot "head"
    'columns' => [],
    'empty' => 'Nenhum registro encontrado.',
    'caption' => null,
    'striped' => false,
    'hover' => true,
    'small' => false,
])

@php
    $classes = \Illuminate\Support\Arr::toCssClasses([
        'table align-middle mb-0',
        'table-striped' => $striped,
        'table-hover' => $hover,
        'table-sm' => $small,
    ]);
@endphp

<div class="table-responsive">
    <table {{ $attributes->merge(['class' => $classes]) }}>
    @if (filled($caption))
        <caption class="visually-hidden">{{ $caption }}</caption>
    @endif
    @isset($head)
        <thead>
            {{ $head }}
        </thead>
    @elseif ($columns)
        <thead>
            <tr>
            @foreach ($columns as $coluna)
                <th scope="col">{{ $coluna }}</th>
            @endforeach
            </tr>
        </thead>
    @endisset
        <tbody>
        {{-- Um @foreach sem itens deixa o slot só com espaços: mostra o estado vazio --}}
        @if ($slot->hasActualContent())
            {{ $slot }}
        @else
            <tr>
                {{-- colspan maior que o número de colunas ocupa a linha inteira --}}
                <td colspan="{{ count($columns) ?: 100 }}" class="text-center text-body-secondary py-5">
                    {{ $empty }}
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
