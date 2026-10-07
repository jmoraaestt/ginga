@props([
    // Texto => link. O último item é a página atual e não vira link
    'items' => [],
])

<nav aria-label="Trilha de navegação" {{ $attributes }}>
    <ol class="breadcrumb">
    @foreach ($items as $texto => $url)
        @if ($loop->last)
            <li class="breadcrumb-item active" aria-current="page">{{ is_int($texto) ? $url : $texto }}</li>
        @else
            <li class="breadcrumb-item"><a href="{{ $url }}">{{ $texto }}</a></li>
        @endif
    @endforeach
    </ol>
</nav>
