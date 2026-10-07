{{--
    Uma linha no layout mostra as mensagens da sessão como toasts:
    redirect()->with('sucesso', 'Cliente salvo.')  e também  'erro', 'aviso', 'info'  ou em inglês  'success', 'error', 'warning'.
    validation: false para não mostrar o toast "Corrija os campos destacados" depois de um erro de validação
--}}
@props([
    'position' => 'bottom-end',
    'validation' => true,
])

@php
    $tipos = [
        'success' => ['sucesso', 'success'],
        'danger' => ['erro', 'error'],
        'warning' => ['aviso', 'warning'],
        'primary' => ['info'],
    ];

    // Desenhados com linhas simples para não depender de biblioteca de ícones
    $icones = [
        'success' => '<circle cx="8" cy="8" r="7"/><path d="M5 8.25l2 2 4-4.5"/>',
        'danger' => '<circle cx="8" cy="8" r="7"/><path d="M5.75 5.75l4.5 4.5m0-4.5l-4.5 4.5"/>',
        'warning' => '<path d="M8 1.75L15 14.25H1z"/><path d="M8 6.25v3.5m0 2.25v.01"/>',
        'primary' => '<circle cx="8" cy="8" r="7"/><path d="M8 7.25v4m0-6.5v.01"/>',
    ];

    $mensagens = [];

    if (request()->hasSession()) {
        foreach ($tipos as $variante => $chaves) {
            foreach ($chaves as $chave) {
                $texto = session($chave);

                if (is_string($texto) && $texto !== '') {
                    $mensagens[] = [$variante, $texto];
                }
            }
        }
    }

    // Quantos campos têm erro, não quantas mensagens (um campo pode ter várias)
    $camposComErro = $validation && isset($errors) ? count($errors->keys()) : 0;
@endphp

@if ($mensagens || $camposComErro)
<x-ginga::toast-container :position="$position" {{ $attributes }}>
@foreach ($mensagens as [$variante, $texto])
    {{-- Erros ficam mais tempo na tela --}}
    <x-ginga::toast :variant="$variante" :delay="$variante === 'danger' ? 8000 : 5000">
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0" aria-hidden="true">{!! $icones[$variante] !!}</svg>
        </x-slot:icon>
        {{ $texto }}
    </x-ginga::toast>
@endforeach
@if ($camposComErro)
    <x-ginga::toast variant="danger" title="Não foi possível salvar" :delay="8000">
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0" aria-hidden="true">{!! $icones['danger'] !!}</svg>
        </x-slot:icon>
        {{ $camposComErro === 1 ? 'Corrija o campo destacado.' : "Corrija os {$camposComErro} campos destacados." }}
    </x-ginga::toast>
@endif
</x-ginga::toast-container>
@endif
