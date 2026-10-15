{{--
    Bloco de código colorido no servidor, com botão de copiar.
    Para código Blade no slot, envolva com @verbatim ... @endverbatim, senão o Blade executa o exemplo.
--}}
@props([
    'lang' => 'blade',
    'codigo' => null,
    'titulo' => null,
])

@use('App\Docs\Codigo')

@php
    $codigo = $codigo === null ? Codigo::normalizar($slot->toHtml(), deSlot: true) : Codigo::normalizar($codigo);
    // Código longo começa recolhido, com um botão para ver tudo
    $longo = substr_count($codigo, "\n") >= 24;
    $rotulos = ['blade' => 'Blade', 'php' => 'PHP', 'html' => 'HTML', 'bash' => 'Terminal', 'css' => 'CSS', 'javascript' => 'JavaScript', 'json' => 'JSON', 'yaml' => 'YAML'];
@endphp

<div {{ $attributes->merge(['class' => 'docs-codigo' . ($longo ? ' docs-codigo-longo' : '')]) }}>
    <div class="docs-codigo-barra">
        <span>{{ $titulo ?? ($rotulos[$lang] ?? $lang) }}</span>
        <button type="button" class="docs-copiar" data-docs-copiar>
            <i class="bi bi-clipboard" aria-hidden="true"></i><span>Copiar</span>
        </button>
    </div>
    {{-- tabindex: blocos com rolagem horizontal precisam ser alcançáveis pelo teclado --}}
    <pre tabindex="0" @if ($lang === 'bash') class="docs-codigo-terminal" @endif><code>{{ Codigo::colorir($codigo, $lang) }}</code></pre>
@if ($longo)
    <button type="button" class="docs-codigo-expandir" aria-expanded="false" data-docs-expandir>
        <i class="bi bi-chevron-down" aria-hidden="true"></i><span>Mostrar código completo</span>
    </button>
@endif
</div>
