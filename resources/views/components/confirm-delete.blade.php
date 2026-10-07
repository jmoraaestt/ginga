@props([
    'id' => 'ginga-confirmar-exclusao',
    'title' => 'Excluir registro?',
    'confirm' => 'Excluir',
    'cancel' => 'Cancelar',
])

{{-- Opcional: o delete-button já traz um modal padrão. Use este para trocar textos ou ter mais de um modal --}}
<x-ginga::modal :id="$id" :title="$title" centered {{ $attributes }}>
    <p class="mb-0">
    @if ($slot->hasActualContent())
        {{ $slot }}
    @else
        Tem certeza que deseja excluir <strong data-ginga-exclusao-item>este registro</strong>? Esta ação não pode ser desfeita.
    @endif
    </p>

    <x-slot:footer>
        <x-ginga::button variant="link" data-bs-dismiss="modal">{{ $cancel }}</x-ginga::button>
        <form method="POST" data-ginga-exclusao-form>
            @csrf
            @method('DELETE')
            <x-ginga::button type="submit" variant="danger">{{ $confirm }}</x-ginga::button>
        </form>
    </x-slot:footer>
</x-ginga::modal>

@include('ginga::partials.exclusao')
