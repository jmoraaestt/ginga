{{--
    action: URL que recebe o DELETE, ex.: route('clientes.destroy', $cliente)
    item:   nome mostrado na confirmação, ex.: "Tem certeza que deseja excluir Maria Silva?"
    modal:  id de um <x-ginga::confirm-delete> personalizado. Sem ele, usa o modal padrão
--}}
@props([
    'action',
    'item' => null,
    'modal' => 'ginga-confirmar-exclusao',
    'variant' => 'outline-danger',
    'size' => 'sm',
])

<x-ginga::button
    :variant="$variant"
    :size="$size"
    aria-haspopup="dialog"
    :data-ginga-excluir="$action"
    :data-ginga-item="$item"
    :data-ginga-modal="$modal"
    :data-ginga-token="csrf_token()"
    {{ $attributes }}
>
@if ($slot->hasActualContent())
    {{ $slot }}
@else
    Excluir
@endif
    {{-- Numa tabela há vários "Excluir": o leitor de tela precisa saber qual --}}
@if (filled($item))
    <span class="visually-hidden">{{ $item }}</span>
@endif
</x-ginga::button>

{{-- O script vem antes do modelo: dentro do <template> ele não seria executado --}}
@include('ginga::partials.exclusao')
@once
    {{-- Modal padrão, usado só quando a página não tem um <x-ginga::confirm-delete> com o mesmo id --}}
    <template data-ginga-exclusao-modelo="ginga-confirmar-exclusao"><x-ginga::confirm-delete /></template>
@endonce
